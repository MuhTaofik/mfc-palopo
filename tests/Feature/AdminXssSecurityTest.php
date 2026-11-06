<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Member;
use App\Models\Package;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Pengujian keamanan untuk perbaikan E2 — Stored Cross-Site Scripting (XSS)
 * pada Panel Admin MFC Palopo.
 */
class AdminXssSecurityTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private Package $packageHarian;
    private Package $packageBulanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

        // Seed data dasar paket gym
        $this->packageHarian = Package::create([
            'nama_paket'         => 'Paket Harian',
            'durasi_hari'        => 1,
            'harga'              => 15000,
            'harga_perpanjangan' => null,
        ]);

        $this->packageBulanan = Package::create([
            'nama_paket'         => 'Paket Bulanan',
            'durasi_hari'        => 30,
            'harga'              => 100000,
            'harga_perpanjangan' => 100000,
        ]);

        // Buat akun admin untuk pengujian terautentikasi
        $this->admin = Admin::create([
            'username'      => 'admin_test',
            'password_hash' => Hash::make('password123'),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    //  1. VALIDASI INPUT NAMA DI PENDAFTARAN PUBLIK (DEFENSE-IN-DEPTH)
    // ═══════════════════════════════════════════════════════════════

    public function test_legitimate_indonesian_names_pass_validation(): void
    {
        $validNames = [
            'Muhammad Fadil',
            'H. Ahmad Dahlan, S.Kom.',
            'Andi O\'Connor',
            'Siti-Nurhaliza',
            'I Gusti Ngurah Rai',
            'La Ode D\'Silva',
        ];

        foreach ($validNames as $index => $name) {
            $response = $this->post(route('pendaftaran.store'), [
                'nama'              => $name,
                'kontak'            => '08123456789' . $index,
                'alamat'            => 'Jl. Contoh Alamat No. ' . $index,
                'package_id'        => $this->packageHarian->id_package,
                'tanggal_mulai'     => now()->format('Y-m-d'),
                'jenis_pendaftaran' => 'Member Baru',
            ]);

            $response->assertSessionHasNoErrors();
            $response->assertRedirect(route('cek-status'));
        }
    }

    public function test_names_containing_xss_scripts_fail_validation(): void
    {
        $maliciousNames = [
            '<script>alert("XSS")</script>',
            'Budi"); alert(1); //',
            'Budi\'); alert(1); //',
            'John" onfocus="alert(1)',
            'attacker<img src=x onerror=alert(1)>',
            'Robert; DROP TABLE members; --',
        ];

        foreach ($maliciousNames as $index => $name) {
            $response = $this->post(route('pendaftaran.store'), [
                'nama'              => $name,
                'kontak'            => '08999999999' . $index,
                'alamat'            => 'Jl. Test Alamat',
                'package_id'        => $this->packageHarian->id_package,
                'tanggal_mulai'     => now()->format('Y-m-d'),
                'jenis_pendaftaran' => 'Member Baru',
            ]);

            $response->assertSessionHasErrors('nama');
        }
    }

    // ═══════════════════════════════════════════════════════════════
    //  2. PERLINDUNGAN STORED XSS TERHADAP DATA EKSISTING DI DATABASE
    // ═══════════════════════════════════════════════════════════════

    /**
     * Menguji jika data berbahaya sudah terlanjur tersimpan di database
     * (misal via data lama atau direct DB insert), halaman admin merendernya
     * dengan aman tanpa mengeksekusi JavaScript di event handler onclick.
     */
    public function test_existing_malicious_registration_renders_safely_on_dashboard(): void
    {
        $payload = 'Budi\'); alert(\'XSS_DASHBOARD\'); //';
        $kode = Registration::generateKode();

        Registration::create([
            'nama'               => $payload,
            'kontak'             => '08111222333',
            'alamat'             => 'Jl. Labu No. 1',
            'package_id'         => $this->packageHarian->id_package,
            'tanggal_mulai'      => now()->format('Y-m-d'),
            'jenis_pendaftaran'  => 'Member Baru',
            'status_pendaftaran' => 'Menunggu',
            'kode_hash'          => Registration::hashKode($kode),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.dashboard'));

        $response->assertOk();
        $content = $response->getContent();

        // 1. Tidak boleh ada onclick yang menginterpolasi raw payload secara langsung
        $this->assertStringNotContainsString(
            "confirm('Konfirmasi terima pendaftaran " . $payload,
            $content,
            'Dashboard tidak boleh memuat nama mentah di dalam string confirm()',
        );

        // 2. Tombol harus menggunakan data-confirm dan data-name
        $this->assertStringContainsString('data-confirm="Konfirmasi terima pendaftaran', $content);
        $this->assertStringContainsString('onclick="return confirm(this.dataset.confirm)"', $content);
        $this->assertStringContainsString('data-name=', $content);
        $this->assertStringContainsString('onclick="openDeleteModal(this.dataset.action, this.dataset.name, this.dataset.type)"', $content);

        // 3. Tidak ada pemanggilan addslashes() lama
        $this->assertStringNotContainsString('addslashes', $content);
    }

    public function test_existing_malicious_registration_renders_safely_on_registrations_index(): void
    {
        $payload = 'Siti" onfocus="alert(1)" class="xss';
        $kode = Registration::generateKode();

        Registration::create([
            'nama'               => $payload,
            'kontak'             => '08222333444',
            'alamat'             => 'Jl. Anggrek No. 2',
            'package_id'         => $this->packageBulanan->id_package,
            'tanggal_mulai'      => now()->format('Y-m-d'),
            'jenis_pendaftaran'  => 'Member Baru',
            'status_pendaftaran' => 'Menunggu',
            'kode_hash'          => Registration::hashKode($kode),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.registrations.index'));

        $response->assertOk();
        $content = $response->getContent();

        // Tidak boleh memecah atribut onclick
        $this->assertStringNotContainsString(
            'onclick="return confirm(\'Tolak pendaftaran ' . $payload,
            $content,
        );

        // Harus menggunakan data-confirm dan data-name ter-escape
        $this->assertStringContainsString('onclick="return confirm(this.dataset.confirm)"', $content);
        $this->assertStringContainsString('onclick="openDeleteModal(this.dataset.action, this.dataset.name, this.dataset.type)"', $content);
    }

    public function test_existing_malicious_member_renders_safely_on_members_index(): void
    {
        $payload = 'Member\'); document.location=\'http://attacker.com\'; //';

        Member::create([
            'nama'             => $payload,
            'kontak'           => '08333444555',
            'package_id'       => $this->packageBulanan->id_package,
            'registration_id'  => null,
            'tanggal_daftar'   => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->addDays(30)->format('Y-m-d'),
            'status'           => 'Aktif',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.members.index'));

        $response->assertOk();
        $content = $response->getContent();

        // Tidak boleh ada interpolasi kode JavaScript mentah di confirm
        $this->assertStringNotContainsString(
            "confirm('Perpanjang masa aktif membership " . $payload,
            $content,
        );

        // Memverifikasi implementasi dataset aman pada halaman member
        $this->assertStringContainsString('data-confirm="Perpanjang masa aktif membership', $content);
        $this->assertStringContainsString('onclick="return confirm(this.dataset.confirm)"', $content);
        $this->assertStringContainsString('data-name=', $content);
        $this->assertStringContainsString('onclick="openDeleteModal(this.dataset.action, this.dataset.name, this.dataset.type)"', $content);
    }

    // ═══════════════════════════════════════════════════════════════
    //  3. VERIFIKASI SELURUH FITUR DAN AKSI ADMIN TETAP BERFUNGSI
    // ═══════════════════════════════════════════════════════════════

    public function test_admin_can_accept_registration(): void
    {
        $kode = Registration::generateKode();
        $reg = Registration::create([
            'nama'               => 'Calon Member Sah',
            'kontak'             => '0812999888',
            'alamat'             => 'Jl. Ratulangi',
            'package_id'         => $this->packageBulanan->id_package,
            'tanggal_mulai'      => now()->format('Y-m-d'),
            'jenis_pendaftaran'  => 'Member Baru',
            'status_pendaftaran' => 'Menunggu',
            'kode_hash'          => Registration::hashKode($kode),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.accept', $reg));

        $response->assertRedirect();
        $this->assertEquals('Diterima', $reg->fresh()->status_pendaftaran);
        $this->assertDatabaseHas('members', ['nama' => 'Calon Member Sah']);
    }

    public function test_admin_can_reject_registration(): void
    {
        $kode = Registration::generateKode();
        $reg = Registration::create([
            'nama'               => 'Calon Ditolak',
            'kontak'             => '0812777666',
            'alamat'             => 'Jl. Veteran',
            'package_id'         => $this->packageHarian->id_package,
            'tanggal_mulai'      => now()->format('Y-m-d'),
            'jenis_pendaftaran'  => 'Member Baru',
            'status_pendaftaran' => 'Menunggu',
            'kode_hash'          => Registration::hashKode($kode),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.reject', $reg));

        $response->assertRedirect();
        $this->assertEquals('Ditolak', $reg->fresh()->status_pendaftaran);
    }

    public function test_admin_can_extend_and_toggle_member(): void
    {
        $member = Member::create([
            'nama'             => 'Member Aktif',
            'kontak'           => '0812555444',
            'package_id'       => $this->packageHarian->id_package,
            'registration_id'  => null,
            'tanggal_daftar'   => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->format('Y-m-d'),
            'status'           => 'Aktif',
        ]);

        // Perpanjang
        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.members.extend', $member));
        $response->assertRedirect();

        // Toggle Status (Aktif -> Nonaktif)
        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.members.toggle-status', $member));
        $response->assertRedirect();
        $this->assertEquals('Nonaktif', $member->fresh()->status);
    }

    public function test_admin_can_destroy_registration_and_member(): void
    {
        $kode = Registration::generateKode();
        $reg = Registration::create([
            'nama'               => 'Mau Dihapus',
            'kontak'             => '0812111000',
            'alamat'             => 'Jl. Merdeka',
            'package_id'         => $this->packageHarian->id_package,
            'tanggal_mulai'      => now()->format('Y-m-d'),
            'jenis_pendaftaran'  => 'Member Baru',
            'status_pendaftaran' => 'Ditolak',
            'kode_hash'          => Registration::hashKode($kode),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.registrations.destroy', $reg));
        $response->assertRedirect();
        $this->assertDatabaseMissing('registrations', ['id_registration' => $reg->id_registration]);

        $member = Member::create([
            'nama'             => 'Member Dihapus',
            'kontak'           => '0812333222',
            'package_id'       => $this->packageHarian->id_package,
            'registration_id'  => null,
            'tanggal_daftar'   => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->format('Y-m-d'),
            'status'           => 'Nonaktif',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.members.destroy', $member));
        $response->assertRedirect();
        $this->assertDatabaseMissing('members', ['id_member' => $member->id_member]);
    }
}

