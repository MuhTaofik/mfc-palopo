<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan formulir login admin.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Memproses autentikasi login admin dengan proteksi rate limiting.
     * Maksimal 5 kali kegagalan login per menit.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $throttleKey = 'admin-login:' . Str::transliterate(Str::lower($credentials['username']) . '|' . $request->ip());

        // Cek jika akun / IP terkunci akibat terlalu banyak percobaan gagal
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'username' => "Terlalu banyak percobaan login gagal. Silakan coba lagi dalam {$seconds} detik.",
            ])->onlyInput('username');
        }

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali di Panel Admin MFC Palopo.');
        }

        // Catat satu kegagalan login dengan masa kedaluwarsa 60 detik (1 menit)
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Logout dari sesi admin.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Anda telah berhasil keluar dari sistem admin.');
    }

    /**
     * Menampilkan formulir ganti password admin.
     */
    public function showChangePasswordForm(): View
    {
        return view('admin.password');
    }

    /**
     * Memproses pembaruan password admin dengan verifikasi password lama.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password:admin'],
            'password'         => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.required'         => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required'                 => 'Password baru wajib diisi.',
            'password.min'                      => 'Password baru minimal berjumlah 8 karakter.',
            'password.confirmed'                => 'Konfirmasi password baru tidak cocok.',
            'password.different'                => 'Password baru harus berbeda dari password saat ini.',
        ]);

        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();
        $admin->update([
            'password_hash' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password admin berhasil diperbarui.');
    }
}
