<?php

namespace Tests\Feature;

use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pengujian keamanan E7 — Keamanan Cookie dan HTTP Security Headers:
 * 1. X-Frame-Options: SAMEORIGIN
 * 2. X-Content-Type-Options: nosniff
 * 3. Referrer-Policy: strict-origin-when-cross-origin
 * 4. Permissions-Policy
 * 5. Content-Security-Policy-Report-Only (dengan izin Google Maps & Google Fonts)
 * 6. HSTS kondisional hanya pada production HTTPS (tanpa includeSubDomains)
 */
class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PackageSeeder::class);
    }

    public function test_standard_security_headers_are_present_on_web_responses(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. X-Frame-Options
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');

        // 2. X-Content-Type-Options
        $response->assertHeader('X-Content-Type-Options', 'nosniff');

        // 3. Referrer-Policy
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 4. Permissions-Policy
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    }

    public function test_csp_report_only_header_is_configured_correctly(): void
    {
        $response = $this->get(route('kontak'));

        $response->assertStatus(200);
        $csp = $response->headers->get('Content-Security-Policy-Report-Only');

        $this->assertNotNull($csp, 'Header Content-Security-Policy-Report-Only harus ada pada response.');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("frame-src 'self' https://www.google.com", $csp);
        $this->assertStringContainsString("https://fonts.googleapis.com", $csp);
        $this->assertStringContainsString("https://fonts.gstatic.com", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString("base-uri 'self'", $csp);
    }

    public function test_hsts_is_not_present_in_local_or_testing_environment(): void
    {
        $response = $this->get(route('home'));

        $response->assertHeaderMissing('Strict-Transport-Security');
    }

    public function test_hsts_is_present_in_production_environment_with_https(): void
    {
        $this->app['env'] = 'production';

        $response = $this->withServerVariables([
            'HTTPS' => 'on',
            'SERVER_PORT' => '443',
        ])->get('https://localhost/');

        $response->assertHeader('Strict-Transport-Security', 'max-age=31536000');
        $this->assertStringNotContainsString('includeSubDomains', (string) $response->headers->get('Strict-Transport-Security'));
    }
}
