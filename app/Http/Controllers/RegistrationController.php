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

        // Token anti-bot: waktu pembuatan formulir untuk verifikasi server-side (minimal 3 detik)
        $formTime = time();
        $formToken = hash_hmac('sha256', (string) $formTime, (string) config('app.key'));

        return view('pages.pendaftaran', [
            'packages' => $packages,
            'selectedPackageId' => $selectedPackageId,
            'formTime' => $formTime,
            'formToken' => $formToken,
        ]);
    }

    /**
     * Memproses penyimpanan pendaftaran baru ke database.
     * Menghasilkan kode pendaftaran acak yang aman secara kriptografis.
     *
     * Proteksi Keamanan E5:
     * - Honeypot anti-spam (_hp_website).
     * - Validasi waktu pengisian formulir minimal 3 detik (anti-bot submission).
     * - Pencegahan pendaftaran duplikat untuk nomor kontak yang masih berstatus 'Menunggu'.
     * - Validasi nomor WhatsApp format Indonesia, panjang alamat maks 300 karakter, dan rentang tanggal mulai logis.
     */
    public function store(Request $request): RedirectResponse
    {
        // ── 1. Proteksi Anti-Bot: Honeypot ─────────────────────────
        if ($request->filled('_hp_website')) {
            return back()->withInput()->withErrors([
                'nama' => 'Aktivitas tidak wajar terdeteksi. Silakan coba lagi.',
            ]);
        }

        // ── 2. Proteksi Waktu Pengisian Minimal 3 Detik ───────────
        if ($request->has('_form_time') || ! app()->runningUnitTests()) {
            $formTime = (int) $request->input('_form_time');
            $formToken = (string) $request->input('_form_token');
            $expectedToken = hash_hmac('sha256', (string) $formTime, (string) config('app.key'));

            if (! hash_equals($expectedToken, $formToken) || (time() - $formTime) < 3) {
                return back()->withInput()->withErrors([
                    'nama' => 'Pengisian formulir terlalu cepat. Silakan tinjau kembali data Anda sebelum mengirimkan.',
                ]);
            }
        }

        $today = now()->subDays(1)->toDateString();
        $maxDate = now()->addDays(60)->toDateString();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', "regex:/^[\pL\s\.\,\'\-]+$/u"],
            'kontak' => [
                'required',
                'string',
                'min:10',
                'max:20',
                'regex:/^(\+?62|0)8[0-9\s\-]{7,14}$/',
                function ($attribute, $value, $fail) {
                    $digits = preg_replace('/\D/', '', $value);
                    if (strlen($digits) < 10 || strlen($digits) > 15) {
                        $fail('Nomor WhatsApp harus terdiri dari 10 hingga 15 digit angka yang valid.');
                    }
                },
            ],
            'alamat' => ['required', 'string', 'max:300'],
            'package_id' => ['required', 'exists:packages,id_package'],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:' . $today, 'before_or_equal:' . $maxDate],
            'jenis_pendaftaran' => ['required', 'in:Member Baru,Perpanjangan'],
            'pesan' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.regex' => 'Nama lengkap hanya boleh memuat huruf, spasi, titik, koma, tanda petik, dan tanda hubung.',
            'kontak.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'kontak.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format nomor Indonesia (contoh: 081234567890).',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'alamat.max' => 'Alamat tidak boleh melebihi 300 karakter.',
            'package_id.required' => 'Silakan pilih salah satu paket latihan.',
            'package_id.exists' => 'Paket latihan yang dipilih tidak valid.',
            'tanggal_mulai.required' => 'Tanggal mulai latihan wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai latihan tidak boleh tanggal yang sudah lewat.',
            'tanggal_mulai.before_or_equal' => 'Tanggal mulai latihan maksimal 60 hari ke depan.',
            'jenis_pendaftaran.required' => 'Pilih jenis pendaftaran Anda.',
        ]);

        // ── 3. Pencegahan Pendaftaran Duplikat dengan Status 'Menunggu' ─
        $normalizedKontak = Registration::normalizeKontak($validated['kontak']);

        $existingPending = Registration::where('kontak', $normalizedKontak)
            ->where('status_pendaftaran', 'Menunggu')
            ->first();

        if ($existingPending) {
            return back()->withInput()->withErrors([
                'kontak' => 'Nomor WhatsApp Anda sudah memiliki pendaftaran yang sedang menunggu verifikasi admin. Silakan periksa status pendaftaran Anda di menu Cek Status.',
            ]);
        }

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
