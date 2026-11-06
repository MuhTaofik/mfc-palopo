<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /**
     * Menampilkan daftar seluruh pendaftaran dengan opsi filter status.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $query = Registration::with('package')->latest('id_registration');

        if ($status && in_array($status, ['Menunggu', 'Diterima', 'Ditolak'])) {
            $query->where('status_pendaftaran', $status);
        }

        $registrations = $query->paginate(15);

        return view('admin.registrations.index', [
            'registrations' => $registrations,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Menyetujui pendaftaran dan otomatis menjadikannya member di tabel members.
     * Menggunakan transaksi database atomik dan pessimistic locking (lockForUpdate)
     * untuk mencegah race condition, double approval, serta menangani jenis pendaftaran
     * Perpanjangan secara tepat tanpa menduplikasi data member lama.
     */
    public function accept(Registration $registration): RedirectResponse
    {
        try {
            $result = DB::transaction(function () use ($registration) {
                // Kunci baris pendaftaran untuk mencegah race condition / double approval
                $lockedRegistration = Registration::where('id_registration', $registration->id_registration)
                    ->lockForUpdate()
                    ->first();

                if (! $lockedRegistration) {
                    return [
                        'type' => 'error',
                        'message' => 'Data pendaftaran tidak ditemukan.',
                    ];
                }

                if ($lockedRegistration->status_pendaftaran === 'Diterima') {
                    return [
                        'type' => 'error',
                        'message' => 'Pendaftaran ini sudah berstatus Diterima sebelumnya.',
                    ];
                }

                if ($lockedRegistration->status_pendaftaran === 'Ditolak') {
                    return [
                        'type' => 'error',
                        'message' => 'Pendaftaran ini sudah berstatus Ditolak sebelumnya.',
                    ];
                }

                if ($lockedRegistration->status_pendaftaran !== 'Menunggu') {
                    return [
                        'type' => 'error',
                        'message' => "Pendaftaran ini sudah diproses sebelumnya dengan status {$lockedRegistration->status_pendaftaran}.",
                    ];
                }

                $package = $lockedRegistration->package;
                $durasiHari = $package ? $package->durasi_hari : 30;
                $normalizedKontak = Registration::normalizeKontak($lockedRegistration->kontak);

                // Alur Perpanjangan: Cari akun member lama berdasarkan nomor kontak
                if ($lockedRegistration->jenis_pendaftaran === 'Perpanjangan') {
                    $existingMember = Member::where('kontak', $normalizedKontak)
                        ->orWhere('kontak', $lockedRegistration->kontak)
                        ->lockForUpdate()
                        ->latest('id_member')
                        ->first();

                    if ($existingMember) {
                        $currentEnd = Carbon::parse($existingMember->tanggal_berakhir)->startOfDay();
                        $today = Carbon::today();

                        // Hitung tanggal berakhir berdasarkan sisa masa aktif atau hari ini jika sudah kedaluwarsa
                        if ($currentEnd->greaterThanOrEqualTo($today)) {
                            $newEnd = $currentEnd->copy()->addDays($durasiHari);
                        } else {
                            $newEnd = $today->copy()->addDays($durasiHari);
                        }

                        // Perbarui data member yang ada tanpa membuat duplikasi baris
                        $existingMember->update([
                            'nama' => $lockedRegistration->nama,
                            'kontak' => $normalizedKontak,
                            'package_id' => $lockedRegistration->package_id,
                            'registration_id' => $lockedRegistration->id_registration,
                            'tanggal_berakhir' => $newEnd->toDateString(),
                            'status' => 'Aktif',
                        ]);

                        $lockedRegistration->update([
                            'status_pendaftaran' => 'Diterima',
                        ]);

                        return [
                            'type' => 'success',
                            'message' => "Pendaftaran perpanjangan {$lockedRegistration->nama} berhasil DITERIMA. Masa aktif member diperpanjang hingga {$newEnd->translatedFormat('d F Y')}.",
                        ];
                    }
                }

                // Alur Member Baru (atau fallback jika member lama perpanjangan belum terdaftar)
                $startDate = Carbon::parse($lockedRegistration->tanggal_mulai);
                if ($startDate->isPast()) {
                    $startDate = Carbon::today();
                }
                $endDate = $startDate->copy()->addDays($durasiHari);

                // Update status pendaftaran
                $lockedRegistration->update([
                    'status_pendaftaran' => 'Diterima',
                ]);

                // Buat record member resmi di tabel members
                Member::create([
                    'nama' => $lockedRegistration->nama,
                    'kontak' => $normalizedKontak,
                    'package_id' => $lockedRegistration->package_id,
                    'registration_id' => $lockedRegistration->id_registration,
                    'tanggal_daftar' => $startDate->toDateString(),
                    'tanggal_berakhir' => $endDate->toDateString(),
                    'status' => 'Aktif',
                ]);

                return [
                    'type' => 'success',
                    'message' => "Pendaftaran {$lockedRegistration->nama} berhasil DITERIMA dan resmi terdaftar sebagai Member Aktif MFC Palopo.",
                ];
            });

            return back()->with($result['type'], $result['message']);
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses persetujuan pendaftaran. Data tidak diubah.');
        }
    }

    /**
     * Menolak pendaftaran dengan transaksi database dan penguncian baris.
     */
    public function reject(Registration $registration): RedirectResponse
    {
        try {
            $result = DB::transaction(function () use ($registration) {
                $lockedRegistration = Registration::where('id_registration', $registration->id_registration)
                    ->lockForUpdate()
                    ->first();

                if (! $lockedRegistration) {
                    return [
                        'type' => 'error',
                        'message' => 'Data pendaftaran tidak ditemukan.',
                    ];
                }

                if ($lockedRegistration->status_pendaftaran === 'Diterima') {
                    return [
                        'type' => 'error',
                        'message' => 'Pendaftaran ini sudah berstatus Diterima sebelumnya.',
                    ];
                }

                if ($lockedRegistration->status_pendaftaran === 'Ditolak') {
                    return [
                        'type' => 'error',
                        'message' => 'Pendaftaran ini sudah berstatus Ditolak sebelumnya.',
                    ];
                }

                if ($lockedRegistration->status_pendaftaran !== 'Menunggu') {
                    return [
                        'type' => 'error',
                        'message' => "Pendaftaran ini sudah diproses sebelumnya dengan status {$lockedRegistration->status_pendaftaran}.",
                    ];
                }

                $lockedRegistration->update([
                    'status_pendaftaran' => 'Ditolak',
                ]);

                return [
                    'type' => 'success',
                    'message' => "Pendaftaran {$lockedRegistration->nama} telah berstatus DITOLAK.",
                ];
            });

            return back()->with($result['type'], $result['message']);
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan sistem saat menolak pendaftaran.');
        }
    }

    /**
     * Menghapus data pendaftaran secara permanen.
     */
    public function destroy(Registration $registration): RedirectResponse
    {
        $nama = $registration->nama;

        DB::transaction(function () use ($registration) {
            // Amankan relasi: lepaskan foreign key pada tabel members jika ada
            Member::where('registration_id', $registration->id_registration)
                ->update(['registration_id' => null]);

            $registration->delete();
        });

        return back()->with('success', "Data pendaftaran atas nama \"{$nama}\" berhasil dihapus.");
    }
}
