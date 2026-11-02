<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |----------------------------------------------------------------------
        | Rate Limiter: Cek Status Pendaftaran (E1 — Keamanan)
        |----------------------------------------------------------------------
        | Membatasi percobaan verifikasi status per IP untuk mencegah
        | brute-force pencarian kode pendaftaran atau nomor kontak.
        | Batas: 5 request per menit per alamat IP.
        | Rate limiting per kode ditangani tambahan di controller.
        */
        RateLimiter::for('cek-status', function (Request $request) {
            return Limit::perMinute(5)->by('cek-status|' . $request->ip());
        });

        /*
        |----------------------------------------------------------------------
        | Rate Limiter: Login Admin (E3 — Keamanan)
        |----------------------------------------------------------------------
        | Membatasi flood request login admin per alamat IP di level HTTP middleware.
        | Proteksi 5 kegagalan login per menit ditangani secara spesifik di controller.
        */
        RateLimiter::for('admin-login', function (Request $request) {
            return Limit::perMinute(15)->by('admin-login|' . $request->ip());
        });
    }
}
