<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    /** Sesuai ERD, tabel ini tidak punya kolom updated_at. */
    public const UPDATED_AT = null;

    protected $table = 'registrations';
    protected $primaryKey = 'id_registration';

    protected $fillable = [
        'nama',
        'kontak',
        'alamat',
        'package_id',
        'tanggal_mulai',
        'jenis_pendaftaran',
        'status_pendaftaran',
        'pesan',
        'kode_hash',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'id_package');
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class, 'registration_id', 'id_registration');
    }

    // ── Mutators / Accessors ──────────────────────────────────────

    /**
     * Otomatis normalisasi nomor kontak saat disimpan.
     */
    protected function kontak(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => static::normalizeKontak($value),
        );
    }

    /**
     * Accessor: nama yang telah di-mask untuk keamanan privasi.
     * "Muhammad Fadil" → "Mu****** Fa***"
     */
    protected function maskedNama(): Attribute
    {
        return Attribute::make(
            get: fn () => collect(explode(' ', $this->nama))
                ->map(function (string $word) {
                    $len = mb_strlen($word);
                    if ($len <= 2) {
                        return $word;
                    }
                    return mb_substr($word, 0, 2) . str_repeat('*', $len - 2);
                })
                ->implode(' '),
        );
    }

    // ── Static Helpers (Keamanan E1) ──────────────────────────────

    /**
     * Generate kode pendaftaran acak yang aman secara kriptografis.
     * Format: MFC- + 16 karakter dari alfabet yang tidak ambigu.
     * Entropi: 30^16 ≈ 2^78 bit — tidak bisa ditebak.
     */
    public static function generateKode(): string
    {
        // Alfabet tanpa karakter ambigu (tanpa 0/O, 1/I/L)
        $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $max   = strlen($chars) - 1;
        $code  = '';

        for ($i = 0; $i < 16; $i++) {
            $code .= $chars[random_int(0, $max)];
        }

        return 'MFC-' . $code;
    }

    /**
     * Hash kode pendaftaran menggunakan HMAC-SHA256 + APP_KEY.
     * Deterministik (input sama → output sama) sehingga bisa dicari di DB.
     * Input dinormalisasi ke uppercase agar case-insensitive bagi pengguna.
     */
    public static function hashKode(string $kode): string
    {
        return hash_hmac('sha256', strtoupper(trim($kode)), config('app.key'));
    }

    /**
     * Normalisasi nomor kontak Indonesia ke format lokal (awalan 0).
     * - Hapus semua karakter non-digit.
     * - Konversi prefix internasional +62 / 62 → 0.
     * - Idempoten: menormalisasi nomor yang sudah normal tidak mengubahnya.
     */
    public static function normalizeKontak(string $kontak): string
    {
        $digits = preg_replace('/\D/', '', $kontak);

        // Prefix internasional Indonesia → format lokal
        if (str_starts_with($digits, '62') && strlen($digits) >= 10) {
            $digits = '0' . substr($digits, 2);
        }

        return $digits;
    }
}
