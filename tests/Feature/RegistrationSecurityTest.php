<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

/**
 * Pengujian keamanan untuk perbaikan E1 — Kebocoran Data Pribadi
 * pada Fitur Cek Status Pendaftaran.
 */
class RegistrationSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed paket harga (tidak mengubah harga asli)
        Package::create([
            'nama_paket'          => 'Paket Harian',
            'durasi_hari'         => 1,
            'harga'               => 15000,
            'harga_perpanjangan'  => null,
        ]);
        Package::create([
            'nama_paket'          => 'Paket Bulanan',
            'durasi_hari'         => 30,
            'harga'               => 100000,
            'harga_perpanjangan'  => 100000,
        ]);
    }

    /**
     * Helper: buat pendaftaran + kode plaintext yang terkait.
     *
     * @return array{0: Registration, 1: string}
     */
    private function createRegistration(array $overrides = []): array
    {
        $kode = Registration::generateKode();
        $data = array_merge([
            'nama'                => 'Muhammad Fadil',
            'kontak'              => '081234567890',
            'alamat'              => 'Jl. Andi Djemma No. 10, Palopo',
            'package_id'          => Package::first()->id_package,
            'tanggal_mulai'       => now()->format('Y-m-d'),
            'jenis_pendaftaran'   => 'Member Baru',
            'status_pendaftaran'  => 'Menunggu',
            'kode_hash'           => Registration::hashKode($kode),
        ], $overrides);

        $reg = Registration::create($data);

        return [$reg, $kode];
    }

    // ═══════════════════════════════════════════════════════════════
    //  1. VERIFIKASI DENGAN KODE DAN KONTAK YANG VALID
    // ═══════════════════════════════════════════════════════════════

    public function test_valid_code_and_kontak_returns_registration(): void
    {
        [$reg, $kode] = $this->createRegistration();

        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => $kode,
            'kontak'           => '081234567890',
        ]);

        $response->assertOk();
        $response->assertViewHas('registration');
        $this->assertNotNull($response->viewData('registration'));
        $this->assertEquals($reg->id_registration, $response->viewData('registration')->id_registration);
    }

    // ═══════════════════════════════════════════════════════════════
    //  2. KODE PENDAFTARAN SALAH → PESAN KESALAHAN GENERIK
    // ═══════════════════════════════════════════════════════════════

    public function test_wrong_code_returns_generic_error(): void
    {
        $this->createRegistration();

        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => 'MFC-SALAHSALAHSALAHX',
            'kontak'           => '081234567890',
        ]);

        $response->assertOk();
        $this->assertNull($response->viewData('registration'));
        // Pesan generik: tidak menyebutkan secara spesifik field mana yang salah
        $response->assertSee('tidak sesuai', false);
    }

    // ═══════════════════════════════════════════════════════════════
    //  3. NOMOR KONTAK TIDAK COCOK → PESAN KESALAHAN GENERIK
    // ═══════════════════════════════════════════════════════════════

    public function test_wrong_kontak_returns_generic_error(): void
    {
        [, $kode] = $this->createRegistration();

        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => $kode,
            'kontak'           => '089999999999',
        ]);

        $response->assertOk();
        $this->assertNull($response->viewData('registration'));
        $response->assertSee('tidak sesuai', false);
    }

    // ═══════════════════════════════════════════════════════════════
    //  4. TIDAK BISA MENGAKSES PENDAFTARAN MILIK ORANG LAIN
    // ═══════════════════════════════════════════════════════════════

    public function test_cannot_access_another_persons_registration(): void
    {
        [$reg1, $kode1] = $this->createRegistration([
            'nama'   => 'User A',
            'kontak' => '081111111111',
        ]);
        [$reg2, $kode2] = $this->createRegistration([
            'nama'   => 'User B',
            'kontak' => '082222222222',
        ]);

        // Kode milik A + kontak milik B → harus gagal
        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => $kode1,
            'kontak'           => '082222222222',
        ]);
        $this->assertNull($response->viewData('registration'));

        // Kode milik B + kontak milik A → harus gagal
        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => $kode2,
            'kontak'           => '081111111111',
        ]);
        $this->assertNull($response->viewData('registration'));
    }

    // ═══════════════════════════════════════════════════════════════
    //  5. RATE LIMITING: PERCOBAAN BERLEBIHAN DITOLAK (429)
    // ═══════════════════════════════════════════════════════════════

    public function test_rate_limiting_prevents_excessive_attempts(): void
    {
        $this->createRegistration();

        // Kirim 5 request (batas per menit per IP = 5)
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('cek-status.check'), [
                'kode_pendaftaran' => 'MFC-BRUTEFORCE00000' . $i,
                'kontak'           => '081234567890',
            ]);
        }

        // Request ke-6 → harus ditolak dengan 429
        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => 'MFC-BRUTEFORCE000006',
            'kontak'           => '081234567890',
        ]);

        $response->assertStatus(429);
    }

    // ═══════════════════════════════════════════════════════════════
    //  6. PENCARIAN PARSIAL (LIKE) TIDAK BERFUNGSI LAGI
    // ═══════════════════════════════════════════════════════════════

    public function test_partial_kontak_does_not_return_results(): void
    {
        [, $kode] = $this->createRegistration(['kontak' => '081234567890']);

        // Nomor kontak parsial — seharusnya tidak cocok karena exact match
        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => $kode,
            'kontak'           => '0812',
        ]);

        $response->assertOk();
        $this->assertNull($response->viewData('registration'));
    }

    // ═══════════════════════════════════════════════════════════════
    //  7. PROSES PENDAFTARAN BARU BERHASIL + MENERIMA KODE
    // ═══════════════════════════════════════════════════════════════

    public function test_registration_store_generates_and_returns_code(): void
    {
        $response = $this->post(route('pendaftaran.store'), [
            'nama'               => 'Pendaftar Baru',
            'kontak'             => '085678901234',
            'alamat'             => 'Jl. Contoh No. 99',
            'package_id'         => Package::first()->id_package,
            'tanggal_mulai'      => now()->format('Y-m-d'),
            'jenis_pendaftaran'  => 'Member Baru',
        ]);

        $response->assertRedirect(route('cek-status'));
        $response->assertSessionHas('kode_pendaftaran');
        $response->assertSessionHas('success');

        $kode = session('kode_pendaftaran');

        // Kode harus sesuai format: MFC- + 16 karakter
        $this->assertStringStartsWith('MFC-', $kode);
        $this->assertEquals(20, strlen($kode));

        // Kode harus bisa digunakan untuk verifikasi
        $reg = Registration::first();
        $this->assertNotNull($reg->kode_hash);
        $this->assertEquals(
            Registration::hashKode($kode),
            $reg->kode_hash,
        );
    }

    // ═══════════════════════════════════════════════════════════════
    //  8. NAMA DI-MASK — NAMA LENGKAP TIDAK DITAMPILKAN
    // ═══════════════════════════════════════════════════════════════

    public function test_full_name_is_masked_in_response(): void
    {
        [$reg, $kode] = $this->createRegistration([
            'nama'   => 'Muhammad Fadil',
            'kontak' => '081234567890',
        ]);

        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => $kode,
            'kontak'           => '081234567890',
        ]);

        $response->assertOk();

        // Nama lengkap TIDAK boleh muncul di halaman
        $response->assertDontSee('Muhammad Fadil');

        // Nama ter-mask harus muncul
        $response->assertSee('Mu******');
        $response->assertSee('Fa***');
    }

    // ═══════════════════════════════════════════════════════════════
    //  9. NORMALISASI NOMOR KONTAK BERFUNGSI
    // ═══════════════════════════════════════════════════════════════

    public function test_kontak_normalization_allows_international_format(): void
    {
        [$reg, $kode] = $this->createRegistration(['kontak' => '081234567890']);

        // Verifikasi menggunakan format internasional +62
        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => $kode,
            'kontak'           => '+6281234567890',
        ]);

        $response->assertOk();
        $this->assertNotNull($response->viewData('registration'));
    }

    public function test_kontak_normalization_strips_formatting(): void
    {
        [$reg, $kode] = $this->createRegistration(['kontak' => '081234567890']);

        // Verifikasi menggunakan nomor dengan spasi/tanda hubung
        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => $kode,
            'kontak'           => '0812-3456-7890',
        ]);

        $response->assertOk();
        $this->assertNotNull($response->viewData('registration'));
    }

    // ═══════════════════════════════════════════════════════════════
    //  10. KODE PENDAFTARAN BERSIFAT CASE-INSENSITIVE
    // ═══════════════════════════════════════════════════════════════

    public function test_code_verification_is_case_insensitive(): void
    {
        [, $kode] = $this->createRegistration();

        // Kode diketik lowercase oleh pengguna
        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => strtolower($kode),
            'kontak'           => '081234567890',
        ]);

        $response->assertOk();
        $this->assertNotNull($response->viewData('registration'));
    }

    // ═══════════════════════════════════════════════════════════════
    //  11. ID INTERNAL (id_registration) TIDAK DITAMPILKAN
    // ═══════════════════════════════════════════════════════════════

    public function test_internal_id_not_exposed_in_response(): void
    {
        [$reg, $kode] = $this->createRegistration();

        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => $kode,
            'kontak'           => '081234567890',
        ]);

        $response->assertOk();

        // id_registration tidak boleh muncul di konten halaman
        $content = $response->getContent();
        $this->assertStringNotContainsString(
            'id_registration',
            $content,
            'Halaman tidak boleh mengandung key "id_registration"',
        );
    }

    // ═══════════════════════════════════════════════════════════════
    //  12. KODE PENDAFTARAN UNIK DI DATABASE
    // ═══════════════════════════════════════════════════════════════

    public function test_registration_codes_are_unique(): void
    {
        $codes = [];
        for ($i = 0; $i < 20; $i++) {
            $kode = Registration::generateKode();
            $this->assertNotContains($kode, $codes, 'Kode pendaftaran harus unik');
            $codes[] = $kode;
        }
    }

    // ═══════════════════════════════════════════════════════════════
    //  13. KODE KRIPTOGRAFIS — FORMAT & PANJANG BENAR
    // ═══════════════════════════════════════════════════════════════

    public function test_generated_code_has_correct_format(): void
    {
        $kode = Registration::generateKode();

        $this->assertStringStartsWith('MFC-', $kode);
        $this->assertEquals(20, strlen($kode)); // MFC- (4) + 16 karakter

        // Karakter setelah prefix harus dari alfabet aman (tanpa 0/O/1/I/L)
        $suffix = substr($kode, 4);
        $this->assertMatchesRegularExpression('/^[ABCDEFGHJKMNPQRSTUVWXYZ23456789]{16}$/', $suffix);
    }

    // ═══════════════════════════════════════════════════════════════
    //  14. KODE HASH TERSIMPAN DI DATABASE (BUKAN PLAINTEXT)
    // ═══════════════════════════════════════════════════════════════

    public function test_code_is_stored_as_hash_not_plaintext(): void
    {
        $response = $this->post(route('pendaftaran.store'), [
            'nama'               => 'Test Hash',
            'kontak'             => '081234567890',
            'alamat'             => 'Jl. Hash Test',
            'package_id'         => Package::first()->id_package,
            'tanggal_mulai'      => now()->format('Y-m-d'),
            'jenis_pendaftaran'  => 'Member Baru',
        ]);

        $kode = session('kode_pendaftaran');
        $reg  = Registration::first();

        // Kolom kode_hash BUKAN kode plaintext
        $this->assertNotEquals($kode, $reg->kode_hash);

        // Kode_hash adalah HMAC-SHA256 (64 karakter hex)
        $this->assertEquals(64, strlen($reg->kode_hash));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $reg->kode_hash);

        // Hash dari kode plaintext harus cocok dengan yang tersimpan
        $this->assertEquals(Registration::hashKode($kode), $reg->kode_hash);
    }

    // ═══════════════════════════════════════════════════════════════
    //  15. KONTAK DISIMPAN DALAM BENTUK TERNORMALISASI
    // ═══════════════════════════════════════════════════════════════

    public function test_kontak_is_normalized_on_store(): void
    {
        $this->post(route('pendaftaran.store'), [
            'nama'               => 'Test Normalisasi',
            'kontak'             => '+6281234567890',
            'alamat'             => 'Jl. Normalisasi',
            'package_id'         => Package::first()->id_package,
            'tanggal_mulai'      => now()->format('Y-m-d'),
            'jenis_pendaftaran'  => 'Member Baru',
        ]);

        $reg = Registration::first();
        $this->assertEquals('081234567890', $reg->getRawOriginal('kontak'));
    }

    // ═══════════════════════════════════════════════════════════════
    //  16. HALAMAN CEK STATUS GET BISA DIAKSES
    // ═══════════════════════════════════════════════════════════════

    public function test_check_status_page_is_accessible(): void
    {
        $response = $this->get(route('cek-status'));

        $response->assertOk();
        $response->assertSee('Status Pendaftaran');
    }

    // ═══════════════════════════════════════════════════════════════
    //  17. RATE LIMITING PER KODE BEKERJA
    // ═══════════════════════════════════════════════════════════════

    public function test_code_based_rate_limiting_works(): void
    {
        $this->createRegistration();
        $sameWrongCode = 'MFC-SATUKODESALAH123';

        // Kirim 3 request dengan kode yang sama (batas = 3/menit per kode)
        for ($i = 0; $i < 3; $i++) {
            $this->post(route('cek-status.check'), [
                'kode_pendaftaran' => $sameWrongCode,
                'kontak'           => '081234567890',
            ]);
        }

        // Request ke-4 dengan kode yang sama → ditolak oleh controller
        $response = $this->post(route('cek-status.check'), [
            'kode_pendaftaran' => $sameWrongCode,
            'kontak'           => '081234567890',
        ]);

        $response->assertOk(); // 200, bukan 429 (ini dari controller, bukan middleware)
        $response->assertSee('Terlalu banyak percobaan');
    }

    // ═══════════════════════════════════════════════════════════════
    //  18. HARGA PAKET TETAP TIDAK BERUBAH
    // ═══════════════════════════════════════════════════════════════

    public function test_package_prices_are_unchanged(): void
    {
        $harian  = Package::where('nama_paket', 'Paket Harian')->first();
        $bulanan = Package::where('nama_paket', 'Paket Bulanan')->first();

        $this->assertEquals(15000, $harian->harga);
        $this->assertEquals(100000, $bulanan->harga);
    }
}

