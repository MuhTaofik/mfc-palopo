<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /**
     * Menampilkan form pendaftaran online pengunjung.
     */
    public function create(Request $request): View
    {
        $packages = Package::orderBy('durasi_hari', 'asc')->get();
        $selectedPackageId = $request->query('paket');

        return view('pages.pendaftaran', [
            'packages' => $packages,
            'selectedPackageId' => $selectedPackageId,
        ]);
    }

    /**
     * Memproses penyimpanan pendaftaran baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'kontak' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'package_id' => ['required', 'exists:packages,id_package'],
            'tanggal_mulai' => ['required', 'date'],
            'jenis_pendaftaran' => ['required', 'in:Member Baru,Perpanjangan'],
            'pesan' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'kontak.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'package_id.required' => 'Silakan pilih salah satu paket latihan.',
            'package_id.exists' => 'Paket latihan yang dipilih tidak valid.',
            'tanggal_mulai.required' => 'Tanggal mulai latihan wajib diisi.',
            'jenis_pendaftaran.required' => 'Pilih jenis pendaftaran Anda.',
        ]);

        $validated['status_pendaftaran'] = 'Menunggu';

        $registration = Registration::create($validated);

        return redirect()->route('cek-status', ['kontak' => $registration->kontak])
            ->with('success', 'Pendaftaran Anda berhasil dikirim! Silakan tunggu konfirmasi admin atau kunjungi langsung gym MFC Palopo.');
    }

    /**
     * Halaman cek status pendaftaran untuk pengunjung.
     */
    public function checkStatus(Request $request): View
    {
        $query = $request->query('kontak');
        $registrations = collect();

        if ($query) {
            $registrations = Registration::with('package')
                ->where('kontak', 'like', "%{$query}%")
                ->orWhere('id_registration', $query)
                ->latest('id_registration')
                ->get();
        }

        return view('pages.cek-status', [
            'query' => $query,
            'registrations' => $registrations,
        ]);
    }
}
