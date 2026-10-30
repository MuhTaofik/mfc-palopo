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
     */
    public function accept(Registration $registration): RedirectResponse
    {
        if ($registration->status_pendaftaran === 'Diterima') {
            return back()->with('error', 'Pendaftaran ini sudah berstatus Diterima sebelumnya.');
        }

        $package = $registration->package;
        $durasiHari = $package ? $package->durasi_hari : 30;

        $startDate = Carbon::parse($registration->tanggal_mulai);
        // Jika tanggal mulai pendaftaran sudah lewat dari hari ini, mulai dari hari ini
        if ($startDate->isPast()) {
            $startDate = Carbon::today();
        }
        $endDate = $startDate->copy()->addDays($durasiHari);

        // Update status pendaftaran
        $registration->update([
            'status_pendaftaran' => 'Diterima',
        ]);

        // Buat record member resmi di tabel members
        Member::create([
            'nama' => $registration->nama,
            'kontak' => $registration->kontak,
            'package_id' => $registration->package_id,
            'registration_id' => $registration->id_registration,
            'tanggal_daftar' => $startDate->toDateString(),
            'tanggal_berakhir' => $endDate->toDateString(),
            'status' => 'Aktif',
        ]);

        return back()->with('success', "Pendaftaran {$registration->nama} berhasil DITERIMA dan resmi terdaftar sebagai Member Aktif MFC Palopo.");
    }

    /**
     * Menolak pendaftaran.
     */
    public function reject(Registration $registration): RedirectResponse
    {
        $registration->update([
            'status_pendaftaran' => 'Ditolak',
        ]);

        return back()->with('success', "Pendaftaran {$registration->nama} telah berstatus DITOLAK.");
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
