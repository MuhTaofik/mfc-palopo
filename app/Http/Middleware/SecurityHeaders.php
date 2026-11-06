<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Menambahkan HTTP Security Headers pada seluruh respon web (E7 Keamanan).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Anti-Clickjacking: hanya izinkan framing dari origin yang sama
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 2. Anti-MIME Sniffing: cegah peramban menebak tipe konten di luar deklarasi
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 3. Referrer Policy: batasi informasi perujuk saat navigasi lintas-origin
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 4. Permissions Policy: nonaktifkan fitur perangkat keras sensitif yang tidak digunakan
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // 5. Content Security Policy (Mode Report-Only agar fitur lama tidak terganggu)
        // Mengizinkan Google Maps embed di /kontak dan Google Fonts
        $cspDirectives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: https: blob:",
            "frame-src 'self' https://www.google.com",
            "connect-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
        ];
        $response->headers->set('Content-Security-Policy-Report-Only', implode('; ', $cspDirectives));

        // 6. HSTS (HTTP Strict Transport Security)
        // Hanya diaktifkan pada lingkungan produksi HTTPS, tanpa includeSubDomains
        if (app()->environment('production') && ($request->isSecure() || $request->header('X-Forwarded-Proto') === 'https' || $request->server('HTTPS') === 'on')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        return $response;
    }
}
