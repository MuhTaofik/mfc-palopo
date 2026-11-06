<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Package;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Pengujian logika harga pendaftaran MFC Palopo:
 * 1. Member Baru + Paket Harian = Rp15.000.
 * 2. Member Baru + Paket Bulanan = Rp125.000 (Rp25.000 pendaftaran awal + Rp100.000 paket bulanan).
 * 3. Perpanjangan + Paket Harian = Rp15.000 tanpa biaya tambahan.
 * 4. Perpanjangan + Paket Bulanan = Rp100.000.
 */
class RegistrationPricingSecurityTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private Package $packageHarian;
    private Package $packageBulanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

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

        $this->admin = Admin::create([
            'username'      => 'admin_test',
            'password_hash' => Hash::make('password123'),
        ]);
    }

    /**
     * Helper untuk membuat pendaftaran uji.
     */
    private function createRegistration(Package $package, string $jenis, string $kontak = '081234567890'): Registration
    {
        $kode = Registration::generateKode();

        return Registration::create([
            'nama'               => 'Pendaftar Uji',
            'kontak'             => $kontak,
            'alamat'             => 'Jl. Dr. Ratulangi No. 10',
            'package_id'         => $package->id_package,
            'tanggal_mulai'      => Carbon::today()->toDateString(),
            'jenis_pendaftaran'  => $jenis,
            'status_pendaftaran' => 'Menunggu',
            'pesan'              => 'Pengujian harga',
            'kode_hash'          => Registration::hashKode($kode),
        ]);
    }

    /**
     * Aturan 1: Member Baru + Paket Harian = Rp15.000.
     */
    public function test_member_baru_paket_harian_total_is_15000(): void
    {
        $reg = $this->createRegistration($this->packageHarian, 'Member Baru');

        $this->assertEquals(15000, $reg->total_biaya);
        $this->assertEquals(0, $reg->biaya_pendaftaran_awal);
    }

    /**
     * Aturan 2: Member Baru + Paket Bulanan = Rp125.000 (Rp25.000 pendaftaran awal + Rp100.000 paket).
     */
    public function test_member_baru_paket_bulanan_total_is_125000_with_25000_admin_fee(): void
    {
        $reg = $this->createRegistration($this->packageBulanan, 'Member Baru');

        $this->assertEquals(125000, $reg->total_biaya);
        $this->assertEquals(25000, $reg->biaya_pendaftaran_awal);
        $this->assertEquals(100000, $reg->package->harga);
    }

    /**
     * Aturan 3: Perpanjangan + Paket Harian = Rp15.000 tanpa biaya tambahan.
     */
    public function test_perpanjangan_paket_harian_total_is_15000_without_additional_fee(): void
    {
        $reg = $this->createRegistration($this->packageHarian, 'Perpanjangan');

        $this->assertEquals(15000, $reg->total_biaya);
        $this->assertEquals(0, $reg->biaya_pendaftaran_awal);
    }

    /**
     * Aturan 4: Perpanjangan + Paket Bulanan = Rp100.000 tanpa biaya tambahan.
     */
    public function test_perpanjangan_paket_bulanan_total_is_100000_without_additional_fee(): void
    {
        $reg = $this->createRegistration($this->packageBulanan, 'Perpanjangan');

        $this->assertEquals(100000, $reg->total_biaya);
        $this->assertEquals(0, $reg->biaya_pendaftaran_awal);
    }

    /**
     * Perbaikan UI: Pilihan Jenis Pendaftaran tidak lagi memuat harga langsung pada label.
     * Ringkasan biaya harus ada dan transparan.
     */
    public function test_registration_form_ui_has_no_hardcoded_prices_on_jenis_pendaftaran(): void
    {
        $response = $this->get(route('pendaftaran'));

        $response->assertStatus(200);

        // Memastikan label tombol radio tidak memuat harga lama yang membingungkan
        $response->assertDontSee('Member Baru &bull; Rp125.000/bln', false);
        $response->assertDontSee('Perpanjangan &bull; Rp100.000/bln', false);

        // Memastikan label bersih
        $response->assertSee('Member Baru');
        $response->assertSee('Perpanjangan');

        // Memastikan elemen ringkasan biaya transparan ada
        $response->assertSee('id="ringkasan-biaya-container"', false);
        $response->assertSee('id="ringkasan-total"', false);
        $response->assertSee('id="row-biaya-pendaftaran"', false);
    }

    /**
     * Perbaikan Backend & Panel Admin:
     * Harga yang ditampilkan di panel admin konsisten dengan total sebenarnya untuk keempat kombinasi.
     */
    public function test_admin_panel_displays_accurate_pricing_for_all_four_combinations(): void
    {
        // Buat 4 kombinasi pendaftaran
        $reg1 = $this->createRegistration($this->packageHarian, 'Member Baru', '081200000001');
        $reg2 = $this->createRegistration($this->packageBulanan, 'Member Baru', '081200000002');
        $reg3 = $this->createRegistration($this->packageHarian, 'Perpanjangan', '081200000003');
        $reg4 = $this->createRegistration($this->packageBulanan, 'Perpanjangan', '081200000004');

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.registrations.index'));

        $response->assertStatus(200);

        // Memastikan total harga untuk Member Baru Bulanan menampilkan Rp 125.000 dan keterangan biaya daftar
        $response->assertSee('Rp 125.000');
        $response->assertSee('Termasuk Biaya Daftar Rp 25.000');

        // Memastikan total harga untuk Perpanjangan Bulanan menampilkan Rp 100.000
        $response->assertSee('Rp 100.000');

        // Memastikan total harga untuk Paket Harian menampilkan Rp 15.000
        $response->assertSee('Rp 15.000');
    }

    /**
     * Validasi Perhitungan Server-side:
     * Perhitungan biaya dihitung secara deterministik oleh server melalui method hitungBiaya.
     */
    public function test_server_side_calculation_method(): void
    {
        $harianBaru = Registration::hitungBiaya($this->packageHarian, 'Member Baru');
        $this->assertEquals(15000, $harianBaru['harga_paket']);
        $this->assertEquals(0, $harianBaru['biaya_admin']);
        $this->assertEquals(15000, $harianBaru['total']);

        $bulananBaru = Registration::hitungBiaya($this->packageBulanan, 'Member Baru');
        $this->assertEquals(100000, $bulananBaru['harga_paket']);
        $this->assertEquals(25000, $bulananBaru['biaya_admin']);
        $this->assertEquals(125000, $bulananBaru['total']);

        $harianPerpanjangan = Registration::hitungBiaya($this->packageHarian, 'Perpanjangan');
        $this->assertEquals(15000, $harianPerpanjangan['harga_paket']);
        $this->assertEquals(0, $harianPerpanjangan['biaya_admin']);
        $this->assertEquals(15000, $harianPerpanjangan['total']);

        $bulananPerpanjangan = Registration::hitungBiaya($this->packageBulanan, 'Perpanjangan');
        $this->assertEquals(100000, $bulananPerpanjangan['harga_paket']);
        $this->assertEquals(0, $bulananPerpanjangan['biaya_admin']);
        $this->assertEquals(100000, $bulananPerpanjangan['total']);
    }
}

