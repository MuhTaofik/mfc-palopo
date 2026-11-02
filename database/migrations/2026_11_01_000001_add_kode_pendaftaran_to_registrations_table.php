<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * E1 — Perbaikan Kebocoran Data Pribadi.
     *
     * Menambahkan kolom kode_hash (HMAC-SHA256 dari kode pendaftaran acak)
     * untuk menggantikan pencarian berbasis kontak/ID sequential yang rentan.
     *
     * Karakteristik Arsitektur Migration:
     * 1. Self-contained: Tidak bergantung pada Model Eloquent (menggunakan DB Query Builder).
     * 2. Transactional & Strict Consistency: Seluruh update database dibungkus DB::transaction.
     *    Kode plaintext ditulis ke CSV dan di-flush (fflush) sebelum record di-update.
     *    Jika terjadi kegagalan (termasuk saat commit transaksi DB), berkas CSV otomatis dihapus
     *    sehingga database dan CSV tidak pernah kehilangan konsistensi.
     * 3. Non-destructive exclusive export: Menggunakan mode eksklusif 'x' pada fopen agar
     *    berkas lama dijamin tidak pernah tertimpa pada level filesystem OS.
     * 4. Enforce constraint: Mengubah kolom kode_hash menjadi NOT NULL setelah backfill.
     * 5. Error handling ketat: Validasi eksplisit untuk fopen, fputcsv, fflush, dan fclose.
     */
    public function up(): void
    {
        // 1. Tambah kolom kode_hash (nullable sementara agar proses backfill dapat berjalan aman)
        if (! Schema::hasColumn('registrations', 'kode_hash')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('kode_hash', 64)->nullable()->after('id_registration');
                $table->unique('kode_hash');
                $table->index('kontak');
            });
        }

        // 2. Ambil hanya baris pendaftaran yang belum memiliki kode_hash (resilient backfill)
        $pending = DB::table('registrations')
            ->whereNull('kode_hash')
            ->orWhere('kode_hash', '')
            ->get();

        if ($pending->isNotEmpty()) {
            $appKey = (string) config('app.key');
            if (empty($appKey)) {
                throw new RuntimeException('Kunci aplikasi (APP_KEY) tidak ditemukan di konfigurasi. Proses migrasi dibatalkan.');
            }

            // Tentukan jalur berkas CSV unik (tidak menimpa berkas yang sudah ada)
            $csvPath = $this->resolveCsvPath();

            // Mode eksklusif 'x': gagal jika berkas sudah ada (perlindungan atomik OS)
            $csv = @fopen($csvPath, 'x');
            if ($csv === false) {
                throw new RuntimeException("Gagal membuka berkas tujuan ekspor CSV dalam mode eksklusif ('x') pada [{$csvPath}]. Berkas mungkin sudah ada atau direktori storage tidak memiliki izin tulis.");
            }

            $committed = false;

            try {
                // Bungkus seluruh operasi update ke dalam Database Transaction
                DB::transaction(function () use ($pending, $appKey, &$csv, $csvPath) {
                    // Tulis baris header CSV
                    if (fputcsv($csv, ['id_registration', 'nama', 'kontak', 'kode_pendaftaran']) === false) {
                        throw new RuntimeException('Gagal menulis baris header ke berkas CSV.');
                    }

                    // Periksa hasil fflush setelah menulis header
                    if (! fflush($csv)) {
                        throw new RuntimeException('Gagal melakukan flush baris header CSV ke disk.');
                    }

                    foreach ($pending as $row) {
                        // Generate kode acak kriptografis unik (loop hingga hash belum ada di database)
                        do {
                            $kode = $this->generateCryptoCode();
                            $hash = $this->hashCryptoCode($kode, $appKey);
                        } while (DB::table('registrations')->where('kode_hash', $hash)->exists());

                        $normalizedKontak = $this->normalizeContact((string) $row->kontak);

                        // LANGKAH 1: Tulis data pendaftaran ke berkas CSV
                        if (fputcsv($csv, [
                            $row->id_registration,
                            $row->nama,
                            $normalizedKontak,
                            $kode,
                        ]) === false) {
                            throw new RuntimeException("Gagal menulis data baris pendaftaran (ID: {$row->id_registration}) ke berkas CSV.");
                        }

                        // LANGKAH 2: Periksa hasil fflush($csv) ke disk sebelum memperbarui database
                        if (! fflush($csv)) {
                            throw new RuntimeException("Gagal melakukan flush buffer berkas CSV ke disk untuk pendaftaran ID: {$row->id_registration}.");
                        }

                        // LANGKAH 3: Perbarui record di database setelah data pasti tersimpan di CSV
                        DB::table('registrations')
                            ->where('id_registration', $row->id_registration)
                            ->update([
                                'kontak'    => $normalizedKontak,
                                'kode_hash' => $hash,
                            ]);
                    }

                    // LANGKAH 4: Pastikan berkas CSV berhasil ditutup dengan sempurna SEBELUM callback berakhir dan DB melakukan commit
                    if (! fclose($csv)) {
                        $csv = null;
                        throw new RuntimeException("Gagal menutup berkas CSV pada [{$csvPath}] secara sempurna. Transaksi dibatalkan.");
                    }
                    $csv = null;
                });

                // Menandai bahwa transaksi database (termasuk fase commit) telah berhasil sepenuhnya
                $committed = true;
            } finally {
                // Pastikan file handle tidak ditutup dua kali:
                // Jika terjadi exception sebelum fclose di dalam callback tercapai, tutup handle di sini
                if (is_resource($csv)) {
                    fclose($csv);
                    $csv = null;
                }

                // KONSISTENSI TOTAL: Jika transaksi database gagal di tengah jalan atau gagal saat commit,
                // hapus berkas CSV yang baru dibuat agar tidak meninggalkan arsip kode yang tidak tersimpan di DB.
                if (! $committed && file_exists($csvPath)) {
                    @unlink($csvPath);
                }
            }
        }

        // 3. Pastikan kolom kode_hash tidak lagi nullable setelah seluruh data terisi
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('kode_hash', 64)->nullable(false)->change();
        });
    }

    /**
     * Rollback migrasi.
     *
     * Keterbatasan Rollback:
     * 1. Kolom kode_hash dan indeks uniknya akan dihapus permanen.
     * 2. Normalisasi nomor kontak bersifat permanen (irreversible) karena nilai mentah
     *    sebelum normalisasi tidak dicadangkan ke kolom terpisah.
     * 3. Berkas ekspor CSV di storage/app/ sengaja TIDAK dihapus agar riwayat kode
     *    pendaftaran tidak hilang jika rollback terjadi secara tidak disengaja.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex(['kontak']);
            $table->dropUnique(['kode_hash']);
            $table->dropColumn('kode_hash');
        });
    }

    // ── Helper Internal Migration (Mandiri Tanpa Model Eloquent) ────

    /**
     * Menghasilkan kode pendaftaran acak yang aman secara kriptografis.
     * Format: MFC- + 16 karakter alfabet non-ambigu (total 20 karakter).
     */
    private function generateCryptoCode(): string
    {
        $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $max   = strlen($chars) - 1;
        $code  = '';

        for ($i = 0; $i < 16; $i++) {
            $code .= $chars[random_int(0, $max)];
        }

        return 'MFC-' . $code;
    }

    /**
     * Menghitung nilai hash deterministik HMAC-SHA256 dari kode pendaftaran.
     */
    private function hashCryptoCode(string $kode, string $appKey): string
    {
        return hash_hmac('sha256', strtoupper(trim($kode)), $appKey);
    }

    /**
     * Menormalisasi nomor kontak ke format standar Indonesia (awalan 0).
     */
    private function normalizeContact(string $kontak): string
    {
        $digits = preg_replace('/\D/', '', $kontak);

        if (str_starts_with($digits, '62') && strlen($digits) >= 10) {
            $digits = '0' . substr($digits, 2);
        }

        return $digits;
    }

    /**
     * Menentukan lokasi dan nama berkas CSV unik agar tidak menimpa berkas yang sudah ada.
     */
    private function resolveCsvPath(): string
    {
        $dir = storage_path('app');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $baseFile = $dir . DIRECTORY_SEPARATOR . 'kode-pendaftaran-lama.csv';

        // Jika berkas sudah ada sebelumnya, cari nama berkas bertanggal yang belum terpakai
        if (file_exists($baseFile)) {
            $timestamp = date('Ymd_His');
            $candidate = $dir . DIRECTORY_SEPARATOR . "kode-pendaftaran-lama_{$timestamp}.csv";
            $counter = 1;
            while (file_exists($candidate)) {
                $candidate = $dir . DIRECTORY_SEPARATOR . "kode-pendaftaran-lama_{$timestamp}_{$counter}.csv";
                $counter++;
            }
            return $candidate;
        }

        return $baseFile;
    }
};
