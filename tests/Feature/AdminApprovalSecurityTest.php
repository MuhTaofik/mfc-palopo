<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Member;
use App\Models\Package;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Pengujian keamanan E4: Integritas Transaksi dan Race Condition
 * pada Alur Persetujuan Pendaftaran Admin (Admin Approval Flow).
 */
class AdminApprovalSecurityTest extends TestCase
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
     * Helper untuk membuat data pendaftaran.
     */
    private function createRegistration(array $overrides = []): Registration
    {
        $kode = Registration::generateKode();

        return Registration::create(array_merge([
            'nama'               => 'Ahmad Rifai',
            'kontak'             => '081234567890',
            'alamat'             => 'Jl. Andi Djemma No. 12',
            'package_id'         => $this->packageBulanan->id_package,
            'tanggal_mulai'      => Carbon::today()->toDateString(),
            'jenis_pendaftaran'  => 'Member Baru',
            'status_pendaftaran' => 'Menunggu',
            'pesan'              => 'Mohon segera diproses.',
            'kode_hash'          => Registration::hashKode($kode),
        ], $overrides));
    }

    /**
     * 1. Persetujuan pendaftaran Member Baru membuat 1 akun member baru dan status menjadi Diterima.
     */
    public function test_approving_new_member_creates_new_member_and_marks_registration_diterima(): void
    {
        $registration = $this->createRegistration([
            'nama'              => 'Budi Baru',
            'kontak'            => '081211112222',
            'jenis_pendaftaran' => 'Member Baru',
            'package_id'        => $this->packageBulanan->id_package,
            'tanggal_mulai'     => Carbon::today()->toDateString(),
        ]);

        $this->assertEquals(0, Member::count());

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.accept', $registration));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $registration->refresh();
        $this->assertEquals('Diterima', $registration->status_pendaftaran);

        $this->assertEquals(1, Member::count());
        $member = Member::first();
        $this->assertEquals('Budi Baru', $member->nama);
        $this->assertEquals('081211112222', $member->kontak);
        $this->assertEquals($this->packageBulanan->id_package, $member->package_id);
        $this->assertEquals($registration->id_registration, $member->registration_id);
        $this->assertEquals(Carbon::today()->toDateString(), $member->tanggal_daftar->toDateString());
        $this->assertEquals(Carbon::today()->addDays(30)->toDateString(), $member->tanggal_berakhir->toDateString());
        $this->assertEquals('Aktif', $member->status);
    }

    /**
     * 2. Pencegahan Double Approval (Race condition / double submit):
     * Pendaftaran yang sudah Diterima tidak dapat disetujui ulang dan tidak membuat member duplikat.
     */
    public function test_approving_registration_cannot_be_approved_twice_double_approval_prevention(): void
    {
        $registration = $this->createRegistration([
            'nama'              => 'Siti Aminah',
            'kontak'            => '081233334444',
            'jenis_pendaftaran' => 'Member Baru',
        ]);

        // Eksekusi persetujuan pertama
        $response1 = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.accept', $registration));
        $response1->assertRedirect();
        $response1->assertSessionHas('success');
        $this->assertEquals(1, Member::count());

        // Percobaan persetujuan kedua (misal: double click / concurrent request)
        $response2 = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.accept', $registration));

        $response2->assertRedirect();
        $response2->assertSessionHas('error', 'Pendaftaran ini sudah berstatus Diterima sebelumnya.');

        // Pastikan jumlah member tetap 1 (tidak terjadi duplikasi)
        $this->assertEquals(1, Member::count());
    }

    /**
     * 3. Pendaftaran yang sudah Ditolak tidak dapat disetujui.
     */
    public function test_cannot_approve_already_rejected_registration(): void
    {
        $registration = $this->createRegistration([
            'status_pendaftaran' => 'Ditolak',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.accept', $registration));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Pendaftaran ini sudah berstatus Ditolak sebelumnya.');
        $this->assertEquals(0, Member::count());
        $this->assertEquals('Ditolak', $registration->fresh()->status_pendaftaran);
    }

    /**
     * 4. Pendaftaran yang sudah Diterima tidak dapat ditolak.
     */
    public function test_cannot_reject_already_accepted_registration(): void
    {
        $registration = $this->createRegistration([
            'status_pendaftaran' => 'Diterima',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.reject', $registration));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Pendaftaran ini sudah berstatus Diterima sebelumnya.');
        $this->assertEquals('Diterima', $registration->fresh()->status_pendaftaran);
    }

    /**
     * 5. Pendaftaran yang sudah Ditolak tidak dapat ditolak ulang dua kali.
     */
    public function test_cannot_reject_already_rejected_registration_twice(): void
    {
        $registration = $this->createRegistration([
            'status_pendaftaran' => 'Menunggu',
        ]);

        // Penolakan pertama
        $response1 = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.reject', $registration));
        $response1->assertRedirect();
        $response1->assertSessionHas('success');
        $this->assertEquals('Ditolak', $registration->fresh()->status_pendaftaran);

        // Penolakan kedua
        $response2 = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.reject', $registration));
        $response2->assertRedirect();
        $response2->assertSessionHas('error', 'Pendaftaran ini sudah berstatus Ditolak sebelumnya.');
    }

    /**
     * 6. Alur Perpanjangan Member Aktif:
     * Jika member masih aktif (sisa masa aktif ada), perpanjangan menambahkan durasi
     * ke tanggal berakhir berjalan, tanpa membuat record member baru (tidak duplikasi).
     */
    public function test_approving_renewal_extends_active_member_without_creating_duplicate_member(): void
    {
        $kontak = '081299887766';
        $currentEnd = Carbon::today()->addDays(10); // Masih aktif 10 hari lagi

        $existingMember = Member::create([
            'nama'             => 'Hendro Member',
            'kontak'           => $kontak,
            'package_id'       => $this->packageBulanan->id_package,
            'registration_id'  => null,
            'tanggal_daftar'   => Carbon::today()->subDays(20)->toDateString(),
            'tanggal_berakhir' => $currentEnd->toDateString(),
            'status'           => 'Aktif',
        ]);

        $this->assertEquals(1, Member::count());

        // Pengajuan perpanjangan Paket Bulanan (30 hari)
        $renewalRegistration = $this->createRegistration([
            'nama'              => 'Hendro Member',
            'kontak'            => $kontak,
            'jenis_pendaftaran' => 'Perpanjangan',
            'package_id'        => $this->packageBulanan->id_package,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.accept', $renewalRegistration));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Pastikan tidak ada duplikasi data member (tetap 1)
        $this->assertEquals(1, Member::count());

        $existingMember->refresh();
        $expectedEnd = $currentEnd->copy()->addDays(30)->toDateString();
        $this->assertEquals($expectedEnd, $existingMember->tanggal_berakhir->toDateString());
        $this->assertEquals('Aktif', $existingMember->status);
        $this->assertEquals($renewalRegistration->id_registration, $existingMember->registration_id);

        $this->assertEquals('Diterima', $renewalRegistration->fresh()->status_pendaftaran);
    }

    /**
     * 7. Alur Perpanjangan Member Kedaluwarsa:
     * Jika masa aktif member sudah kedaluwarsa di masa lalu, tanggal berakhir baru
     * dihitung dari tanggal hari ini + durasi paket, tanpa duplikasi member.
     */
    public function test_approving_renewal_for_expired_member_extends_from_today_without_duplication(): void
    {
        $kontak = '081255556666';
        $pastEnd = Carbon::today()->subDays(15); // Sudah kedaluwarsa 15 hari lalu

        $existingMember = Member::create([
            'nama'             => 'Dewi Lestari',
            'kontak'           => $kontak,
            'package_id'       => $this->packageBulanan->id_package,
            'registration_id'  => null,
            'tanggal_daftar'   => Carbon::today()->subDays(45)->toDateString(),
            'tanggal_berakhir' => $pastEnd->toDateString(),
            'status'           => 'Nonaktif',
        ]);

        $this->assertEquals(1, Member::count());

        // Pengajuan perpanjangan
        $renewalRegistration = $this->createRegistration([
            'nama'              => 'Dewi Lestari',
            'kontak'            => $kontak,
            'jenis_pendaftaran' => 'Perpanjangan',
            'package_id'        => $this->packageBulanan->id_package,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.accept', $renewalRegistration));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Pastikan tetap 1 member
        $this->assertEquals(1, Member::count());

        $existingMember->refresh();
        // Karena sudah lewat, diperpanjang dari hari ini + 30 hari
        $expectedEnd = Carbon::today()->addDays(30)->toDateString();
        $this->assertEquals($expectedEnd, $existingMember->tanggal_berakhir->toDateString());
        $this->assertEquals('Aktif', $existingMember->status);

        $this->assertEquals('Diterima', $renewalRegistration->fresh()->status_pendaftaran);
    }

    /**
     * 8. Alur Fallback Perpanjangan jika Member Lama Belum Ada:
     * Jika pendaftar memilih 'Perpanjangan' namun kontaknya belum pernah ada di tabel members,
     * sistem secara aman melakukan fallback membuat akun member baru.
     */
    public function test_approving_renewal_when_member_not_found_falls_back_to_creating_new_member(): void
    {
        $this->assertEquals(0, Member::count());

        $renewalRegistration = $this->createRegistration([
            'nama'              => 'Member Tak Ditemukan',
            'kontak'            => '081277778888',
            'jenis_pendaftaran' => 'Perpanjangan',
            'package_id'        => $this->packageHarian->id_package,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.accept', $renewalRegistration));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(1, Member::count());
        $newMember = Member::first();
        $this->assertEquals('Member Tak Ditemukan', $newMember->nama);
        $this->assertEquals('081277778888', $newMember->kontak);
        $this->assertEquals('Aktif', $newMember->status);
        $this->assertEquals(Carbon::today()->addDays(1)->toDateString(), $newMember->tanggal_berakhir->toDateString());
    }

    /**
     * 9. Transaksi Database Atomik & Rollback:
     * Jika terjadi kegagalan sistem saat operasi pembuatan/pembaruan member,
     * seluruh transaksi di-rollback: status pendaftaran tetap 'Menunggu' dan
     * tidak ada data member yang tersimpan sebagian.
     */
    public function test_transaction_rolls_back_if_member_operation_fails(): void
    {
        $registration = $this->createRegistration([
            'nama'              => 'Uji Rollback',
            'kontak'            => '081200009999',
            'jenis_pendaftaran' => 'Member Baru',
        ]);

        $this->assertEquals(0, Member::count());

        // Simulasikan kegagalan sistem saat proses Member::create berlangsung
        Member::creating(function ($member) {
            throw new \RuntimeException('Database constraint violation failure simulation');
        });

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.registrations.accept', $registration));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Pastikan status pendaftaran TIDAK berubah (di-rollback ke 'Menunggu')
        $registration->refresh();
        $this->assertEquals('Menunggu', $registration->status_pendaftaran);

        // Pastikan tidak ada data member yang tersimpan
        $this->assertEquals(0, Member::count());
    }
}

