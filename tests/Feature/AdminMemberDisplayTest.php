<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Member;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Pengujian tampilan sisa hari keanggotaan dan akurasi status pada panel admin MFC Palopo:
 * 1. Sisa hari ditampilkan sebagai bilangan bulat (misal: "Sisa 33 hari"), tanpa angka desimal.
 * 2. Perhitungan berbasis tanggal kalender murni.
 * 3. Status Aktif dan Kedaluwarsa akurat.
 */
class AdminMemberDisplayTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private Package $packageBulanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

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
     * 1. Sisa hari keanggotaan ditampilkan sebagai bilangan bulat tanpa desimal (misal: "Sisa 33 hari").
     */
    public function test_remaining_days_is_displayed_as_integer_without_decimals(): void
    {
        // Buat member dengan masa aktif berakhir 33 hari dari hari ini
        $member = Member::create([
            'nama'             => 'Budi Member',
            'kontak'           => '081234567890',
            'package_id'       => $this->packageBulanan->id_package,
            'registration_id'  => null,
            'tanggal_daftar'   => Carbon::today()->subDays(5)->toDateString(),
            'tanggal_berakhir' => Carbon::today()->addDays(33)->toDateString(),
            'status'           => 'Aktif',
        ]);

        $this->assertIsInt($member->sisa_hari);
        $this->assertEquals(33, $member->sisa_hari);
        $this->assertFalse($member->is_expired);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.members.index'));

        $response->assertStatus(200);

        // Memastikan teks bilangan bulat muncul di antarmuka
        $response->assertSee('Sisa 33 hari');

        // Memastikan tidak ada teks angka desimal yang diikuti 'hari' pada halaman
        $content = $response->getContent();
        $this->assertDoesNotMatchRegularExpression('/\d+\.\d+\s*hari/i', $content, 'Tampilan sisa hari tidak boleh memuat angka desimal.');
    }

    /**
     * 2. Pengujian akurasi tanggal kalender untuk member yang berakhir hari ini:
     * Pada hari terakhirnya, member belum berstatus kedaluwarsa dan status badge tetap 'Aktif'.
     */
    public function test_member_ending_today_is_not_expired_and_remains_active(): void
    {
        $memberToday = Member::create([
            'nama'             => 'Member Hari Ini',
            'kontak'           => '081299998888',
            'package_id'       => $this->packageBulanan->id_package,
            'registration_id'  => null,
            'tanggal_daftar'   => Carbon::today()->subDays(30)->toDateString(),
            'tanggal_berakhir' => Carbon::today()->toDateString(),
            'status'           => 'Aktif',
        ]);

        $this->assertFalse($memberToday->is_expired, 'Member yang berakhir hari ini tidak boleh dianggap kedaluwarsa.');
        $this->assertEquals(0, $memberToday->sisa_hari);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.members.index'));

        $response->assertStatus(200);
        $response->assertSee('Sisa 0 hari');
        $response->assertSee('Aktif');
    }

    /**
     * 3. Pengujian member yang sudah lewat tanggal berakhir (kemarin atau sebelumnya):
     * Harus terdeteksi kedaluwarsa dan menampilkan 'Sudah Berakhir' serta badge 'Kedaluwarsa'.
     */
    public function test_member_expired_yesterday_shows_sudah_berakhir(): void
    {
        $memberExpired = Member::create([
            'nama'             => 'Member Kedaluwarsa',
            'kontak'           => '081277776666',
            'package_id'       => $this->packageBulanan->id_package,
            'registration_id'  => null,
            'tanggal_daftar'   => Carbon::today()->subDays(35)->toDateString(),
            'tanggal_berakhir' => Carbon::today()->subDays(1)->toDateString(),
            'status'           => 'Aktif',
        ]);

        $this->assertTrue($memberExpired->is_expired);
        $this->assertEquals(0, $memberExpired->sisa_hari);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.members.index'));

        $response->assertStatus(200);
        $response->assertSee('Sudah Berakhir');
        $response->assertSee('Kedaluwarsa');
    }

    /**
     * 4. Pengujian member nonaktif:
     * Member berstatus nonaktif menampilkan badge 'Nonaktif'.
     */
    public function test_nonactive_member_displays_nonaktif_badge(): void
    {
        Member::create([
            'nama'             => 'Member Nonaktif',
            'kontak'           => '081211110000',
            'package_id'       => $this->packageBulanan->id_package,
            'registration_id'  => null,
            'tanggal_daftar'   => Carbon::today()->subDays(10)->toDateString(),
            'tanggal_berakhir' => Carbon::today()->addDays(20)->toDateString(),
            'status'           => 'Nonaktif',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.members.index'));

        $response->assertStatus(200);
        $response->assertSee('Nonaktif');
    }

    /**
     * 5. Pengujian member belum dimulai (tanggal_mulai > hari ini):
     * - Menampilkan status badge 'Belum Dimulai'.
     * - Menampilkan teks tanggal mulai (misal: 'Mulai 12 Nov 2026').
     * - Jangan menampilkan hitungan sisa hari.
     */
    public function test_member_with_future_start_date_shows_belum_dimulai_and_no_sisa_hari(): void
    {
        $startDate = Carbon::today()->addDays(7);
        $endDate = $startDate->copy()->addDays(30);

        $memberFuture = Member::create([
            'nama'             => 'Member Belum Mulai',
            'kontak'           => '081244443333',
            'package_id'       => $this->packageBulanan->id_package,
            'registration_id'  => null,
            'tanggal_daftar'   => $startDate->toDateString(),
            'tanggal_berakhir' => $endDate->toDateString(),
            'status'           => 'Aktif',
        ]);

        $this->assertTrue($memberFuture->is_not_started);
        $this->assertFalse($memberFuture->is_expired);
        $this->assertEquals(0, $memberFuture->sisa_hari);
        $this->assertEquals('Belum Dimulai', $memberFuture->status_keanggotaan);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.members.index'));

        $response->assertStatus(200);

        // Memastikan badge Belum Dimulai muncul
        $response->assertSee('Belum Dimulai');

        // Memastikan tanggal mulai muncul (format d M Y)
        $expectedDateText = 'Mulai ' . $startDate->translatedFormat('d M Y');
        $response->assertSee($expectedDateText);

        // Memastikan TIDAK menampilkan hitungan sisa hari untuk member ini
        $content = $response->getContent();
        $this->assertStringNotContainsString('Sisa 30 hari', $content);
        $this->assertStringNotContainsString('Sisa 37 hari', $content);
    }
}

