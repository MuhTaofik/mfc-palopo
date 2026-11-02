<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
     * Menghasilkan kode pendaftaran acak yang aman secara kriptografis.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', "regex:/^[\pL\s\.\,\'\-]+$/u"],
            'kontak' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'package_id' => ['required', 'exists:packages,id_package'],
            'tanggal_mulai' => ['required', 'date'],
            'jenis_pendaftaran' => ['required', 'in:Member Baru,Perpanjangan'],
            'pesan' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.regex' => 'Nama lengkap hanya boleh memuat huruf, spasi, titik, koma, tanda petik, dan tanda hubung.',
            'kontak.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'package_id.required' => 'Silakan pilih salah satu paket latihan.',
            'package_id.exists' => 'Paket latihan yang dipilih tidak valid.',
            'tanggal_mulai.required' => 'Tanggal mulai latihan wajib diisi.',
            'jenis_pendaftaran.required' => 'Pilih jenis pendaftaran Anda.',
        ]);

        $validated['status_pendaftaran'] = 'Menunggu';

        // Generate kode pendaftaran acak (kriptografis, 16 karakter + prefix)
        do {
            $kodePendaftaran = Registration::generateKode();
            $kodeHash = Registration::hashKode($kodePendaftaran);
        } while (Registration::where('kode_hash', $kodeHash)->exists());

        $validated['kode_hash'] = $kodeHash;

        // kontak dinormalisasi otomatis oleh mutator pada model
        Registration::create($validated);

        return redirect()->route('cek-status')
            ->with('success', 'Pendaftaran Anda berhasil dikirim! Simpan kode pendaftaran di bawah ini untuk memantau status Anda.')
            ->with('kode_pendaftaran', $kodePendaftaran);
    }

    /**
     * Menampilkan halaman form cek status pendaftaran (GET).
     */
    public function showCheckStatus(): View
    {
        return view('pages.cek-status', [
            'registration' => null,
        ]);
    }

    /**
     * Memproses verifikasi status pendaftaran (POST).
     *
     * Keamanan E1:
     * - Rate limiting per IP (middleware) dan per kode (manual, sebelum query DB).
     * - Verifikasi ganda: kode pendaftaran + nomor kontak (exact match).
     * - Pesan kesalahan generik — tidak membedakan field yang salah.
     * - Nama di-mask, kontak & ID internal tidak ditampilkan.
     */
    public function checkStatus(Request $request): View
    {
        $validated = $request->validate([
            'kode_pendaftaran' => ['required', 'string', 'max:50'],
            'kontak' => ['required', 'string', 'max:30'],
        ], [
            'kode_pendaftaran.required' => 'Kode pendaftaran wajib diisi.',
            'kontak.required' => 'Nomor WhatsApp wajib diisi.',
        ]);

        // ── Rate limiting per kode (sebelum query DB) ────────────
        $kodeKey = 'cek-status-kode:' . hash('sha256', $validated['kode_pendaftaran']);

        if (RateLimiter::tooManyAttempts($kodeKey, 3)) {
            return view('pages.cek-status', [
                'registration' => null,
                'error' => 'Terlalu banyak percobaan untuk kode ini. Silakan coba lagi dalam beberapa menit.',
            ]);
        }

        RateLimiter::hit($kodeKey, 60);

        // ── Verifikasi ganda: hash kode + exact match kontak ─────
        $kodeHash = Registration::hashKode($validated['kode_pendaftaran']);
        $kontakNormalized = Registration::normalizeKontak($validated['kontak']);

        $registration = Registration::with('package')
            ->where('kode_hash', $kodeHash)
            ->where('kontak', $kontakNormalized)
            ->first();

        if (! $registration) {
            return view('pages.cek-status', [
                'registration' => null,
                'error' => 'Kode pendaftaran atau nomor WhatsApp tidak sesuai. Silakan periksa kembali.',
            ]);
        }

        return view('pages.cek-status', [
            'registration' => $registration,
        ]);
    }
}
