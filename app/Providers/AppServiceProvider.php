<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
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

        /*
        |----------------------------------------------------------------------
        | Rate Limiter: Pendaftaran Publik (E5 — Keamanan)
        |----------------------------------------------------------------------
        | Membatasi pengiriman formulir pendaftaran maksimal 5 kali per 10 menit
        | per alamat IP untuk mencegah banjir spam dan penolakan layanan (DoS).
        */
        RateLimiter::for('pendaftaran', function (Request $request) {
            return Limit::perMinutes(10, 5)->by($request->ip());
        });

        /*
        |----------------------------------------------------------------------
        | Penegakan HTTPS di Lingkungan Produksi (E6 — Keamanan)
        |----------------------------------------------------------------------
        | Memastikan seluruh tautan, aset, dan pengalihan menggunakan skema
        | HTTPS yang terenkripsi saat aplikasi dijalankan di lingkungan produksi.
        */
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        /*
        |----------------------------------------------------------------------
        | Konfigurasi Trusted Proxies (E6 — Keamanan)
        |----------------------------------------------------------------------
        | Membaca TRUSTED_PROXIES dari config('app.trusted_proxies') agar tetap
        | berfungsi saat config:cache aktif di produksi.
        */
        $trustedProxies = config('app.trusted_proxies');
        if (! empty($trustedProxies)) {
            $proxies = array_values(array_filter(array_map('trim', explode(',', (string) $trustedProxies))));
            TrustProxies::at(count($proxies) === 1 && $proxies[0] === '*' ? '*' : $proxies);
        }
    }
}
