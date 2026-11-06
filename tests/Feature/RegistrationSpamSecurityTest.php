<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

/**
 * Pengujian keamanan E5 — Perlindungan Formulir Pendaftaran dari Spam:
 * 1. Rate limiting 5 pendaftaran per 10 menit per IP.
 * 2. Deteksi bot via field Honeypot.
 * 3. Validasi waktu pengisian formulir minimal 3 detik (anti fast-bot).
 * 4. Pencegahan pendaftaran duplikat untuk nomor kontak yang masih 'Menunggu'.
 * 5. Izin pendaftaran ulang jika pendaftaran sebelumnya 'Ditolak' atau 'Diterima' (Perpanjangan).
 * 6. Validasi ketat nomor WhatsApp, batas maksimal alamat, dan rentang tanggal mulai latihan.
 */
class RegistrationSpamSecurityTest extends TestCase
{
    use RefreshDatabase;

    private Package $package;

    protected function setUp(): void
    {
        parent::setUp();

        RateLimiter::clear('pendaftaran|127.0.0.1');

        $this->package = Package::create([
            'nama_paket'         => 'Paket Bulanan',
            'durasi_hari'        => 30,
            'harga'              => 100000,
            'harga_perpanjangan' => 100000,
        ]);
    }

    /**
     * Helper untuk membuat payload formulir pendaftaran yang sah.
     */
    private function validPayload(array $overrides = []): array
    {
        $time = time() - 5; // 5 detik yang lalu (memenuhi syarat >= 3 detik)
        $token = hash_hmac('sha256', (string) $time, (string) config('app.key'));

        return array_merge([
            '_hp_website'       => '',
            '_form_time'        => $time,
            '_form_token'       => $token,
            'nama'              => 'Muhammad Fadil',
            'kontak'            => '081234567890',
            'alamat'            => 'Jl. Andi Djemma No. 10, Palopo',
            'package_id'        => $this->package->id_package,
            'tanggal_mulai'     => now()->addDays(1)->format('Y-m-d'),
            'jenis_pendaftaran' => 'Member Baru',
            'pesan'             => 'Pendaftaran awal',
        ], $overrides);
    }

    public function test_legitimate_submission_with_valid_timing_passes(): void
    {
        $response = $this->post(route('pendaftaran.store'), $this->validPayload());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('cek-status'));
        $this->assertDatabaseHas('registrations', [
            'nama'   => 'Muhammad Fadil',
            'kontak' => '081234567890',
            'status_pendaftaran' => 'Menunggu',
        ]);
    }

    public function test_rate_limiting_blocks_after_five_submissions_in_ten_minutes(): void
    {
        // 5 pengiriman pertama berhasil diterima
        for ($i = 1; $i <= 5; $i++) {
            $payload = $this->validPayload([
                'kontak' => '08123456789' . $i,
            ]);

            $response = $this->post(route('pendaftaran.store'), $payload);
            $response->assertRedirect(route('cek-status'));
        }

        // Pengiriman ke-6 dari IP yang sama harus diblokir dengan HTTP 429 Too Many Requests
        $payloadKeEnam = $this->validPayload([
            'kontak' => '081234567899',
        ]);

        $blockedResponse = $this->post(route('pendaftaran.store'), $payloadKeEnam);
        $blockedResponse->assertStatus(429);
    }

    public function test_honeypot_field_submission_is_rejected(): void
    {
        $payload = $this->validPayload([
            '_hp_website' => 'http://spam-spambot-link.com',
        ]);

        $response = $this->from(route('pendaftaran'))->post(route('pendaftaran.store'), $payload);

        $response->assertSessionHasErrors('nama');
        $this->assertDatabaseCount('registrations', 0);
    }

    public function test_submission_faster_than_three_seconds_is_rejected(): void
    {
        $now = time(); // 0 detik yang lalu (< 3 detik)
        $token = hash_hmac('sha256', (string) $now, (string) config('app.key'));

        $payload = $this->validPayload([
            '_form_time'  => $now,
            '_form_token' => $token,
        ]);

        $response = $this->from(route('pendaftaran'))->post(route('pendaftaran.store'), $payload);

        $response->assertSessionHasErrors('nama');
        $this->assertDatabaseCount('registrations', 0);
    }

    public function test_submission_with_forged_or_invalid_time_token_is_rejected(): void
    {
        $payload = $this->validPayload([
            '_form_time'  => time() - 10,
            '_form_token' => 'invalid_forged_token_12345',
        ]);

        $response = $this->from(route('pendaftaran'))->post(route('pendaftaran.store'), $payload);

        $response->assertSessionHasErrors('nama');
        $this->assertDatabaseCount('registrations', 0);
    }

    public function test_duplicate_pending_registration_with_same_contact_is_blocked(): void
    {
        // Pendaftaran pertama berhasil dibuat dengan status 'Menunggu'
        $this->post(route('pendaftaran.store'), $this->validPayload([
            'kontak' => '081234567890',
        ]))->assertRedirect(route('cek-status'));

        $this->assertDatabaseCount('registrations', 1);

        // Pendaftaran kedua dengan nomor kontak yang sama (variasi format +62)
        $duplicateResponse = $this->from(route('pendaftaran'))->post(route('pendaftaran.store'), $this->validPayload([
            'kontak' => '+6281234567890',
        ]));

        $duplicateResponse->assertSessionHasErrors('kontak');
        $errors = session('errors')->get('kontak');
        $this->assertStringContainsString('sudah memiliki pendaftaran yang sedang menunggu verifikasi', $errors[0]);

        // Jumlah entri tidak boleh bertambah di database
        $this->assertDatabaseCount('registrations', 1);
    }

    public function test_re_registration_is_allowed_if_previous_registration_was_rejected(): void
    {
        // Buat pendaftaran lama yang statusnya 'Ditolak'
        Registration::create([
            'kode_hash'          => Registration::hashKode('MFC-OLDREJECT12345'),
            'nama'               => 'Muhammad Fadil',
            'kontak'             => '081234567890',
            'alamat'             => 'Jl. Andi Djemma No. 10',
            'package_id'         => $this->package->id_package,
            'tanggal_mulai'      => now()->format('Y-m-d'),
            'jenis_pendaftaran'  => 'Member Baru',
            'status_pendaftaran' => 'Ditolak',
        ]);

        // Pengunjung mendaftar ulang dengan kontak yang sama
        $response = $this->post(route('pendaftaran.store'), $this->validPayload([
            'kontak' => '081234567890',
        ]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('cek-status'));

        // Harus ada 2 record (1 Ditolak dan 1 Menunggu)
        $this->assertEquals(2, Registration::where('kontak', '081234567890')->count());
        $this->assertEquals(1, Registration::where('kontak', '081234567890')->where('status_pendaftaran', 'Menunggu')->count());
    }

    public function test_renewal_registration_is_allowed_if_previous_registration_was_accepted(): void
    {
        // Buat pendaftaran lama yang statusnya 'Diterima'
        Registration::create([
            'kode_hash'          => Registration::hashKode('MFC-OLDACCEPT1234'),
            'nama'               => 'Muhammad Fadil',
            'kontak'             => '081234567890',
            'alamat'             => 'Jl. Andi Djemma No. 10',
            'package_id'         => $this->package->id_package,
            'tanggal_mulai'      => now()->subDays(30)->format('Y-m-d'),
            'jenis_pendaftaran'  => 'Member Baru',
            'status_pendaftaran' => 'Diterima',
        ]);

        // Pengunjung mengajukan perpanjangan keanggotaan
        $response = $this->post(route('pendaftaran.store'), $this->validPayload([
            'kontak'            => '081234567890',
            'jenis_pendaftaran' => 'Perpanjangan',
        ]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('cek-status'));

        // Harus ada 2 record (1 Diterima dan 1 Menunggu Perpanjangan)
        $this->assertEquals(2, Registration::where('kontak', '081234567890')->count());
        $this->assertEquals(1, Registration::where('kontak', '081234567890')->where('status_pendaftaran', 'Menunggu')->count());
    }

    public function test_invalid_contact_numbers_fail_validation(): void
    {
        $invalidNumbers = [
            'abcde',
            '12345',
            '08123',             // terlalu pendek (< 10 digit)
            '071234567890',      // bukan nomor seluler 08 / 628
            '08123456789012345', // terlalu panjang (> 15 digit)
        ];

        foreach ($invalidNumbers as $kontak) {
            $response = $this->from(route('pendaftaran'))->post(route('pendaftaran.store'), $this->validPayload([
                'kontak' => $kontak,
            ]));

            $response->assertSessionHasErrors('kontak');
        }
    }

    public function test_address_exceeding_300_characters_fails_validation(): void
    {
        $longAddress = str_repeat('a', 301);

        $response = $this->from(route('pendaftaran'))->post(route('pendaftaran.store'), $this->validPayload([
            'alamat' => $longAddress,
        ]));

        $response->assertSessionHasErrors('alamat');
    }

    public function test_past_date_fails_validation(): void
    {
        $pastDate = now()->subDays(30)->format('Y-m-d');

        $response = $this->from(route('pendaftaran'))->post(route('pendaftaran.store'), $this->validPayload([
            'tanggal_mulai' => $pastDate,
        ]));

        $response->assertSessionHasErrors('tanggal_mulai');
    }

    public function test_date_too_far_in_future_fails_validation(): void
    {
        $farFutureDate = now()->addDays(65)->format('Y-m-d');

        $response = $this->from(route('pendaftaran'))->post(route('pendaftaran.store'), $this->validPayload([
            'tanggal_mulai' => $farFutureDate,
        ]));

        $response->assertSessionHasErrors('tanggal_mulai');
    }
}

