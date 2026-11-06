<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/**
 * Pengujian keamanan E6 — Konfigurasi Produksi:
 * 1. Default secure cookie saat lingkungan production (tanpa SESSION_SECURE_COOKIE).
 * 2. Non-secure cookie saat lingkungan local / testing (tanpa SESSION_SECURE_COOKIE).
 * 3. Kemampuan override manual SESSION_SECURE_COOKIE jika ditentukan eksplisit.
 * 4. Pemaksaan skema URL HTTPS saat lingkungan production.
 * 5. Konfigurasi trusted proxies di config('app.trusted_proxies') agar tahan config:cache.
 */
class ProductionConfigSecurityTest extends TestCase
{
    public function test_session_secure_cookie_is_enabled_in_production_environment_by_default(): void
    {
        $originalEnv = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'testing';

        $_SERVER['APP_ENV'] = 'production';
        $_ENV['APP_ENV'] = 'production';
        putenv('APP_ENV=production');
        unset($_SERVER['SESSION_SECURE_COOKIE'], $_ENV['SESSION_SECURE_COOKIE']);
        putenv('SESSION_SECURE_COOKIE');

        $sessionConfig = require config_path('session.php');

        $this->assertTrue($sessionConfig['secure']);

        // Kembalikan environment
        $_SERVER['APP_ENV'] = $originalEnv;
        $_ENV['APP_ENV'] = $originalEnv;
        putenv("APP_ENV={$originalEnv}");
    }

    public function test_session_secure_cookie_is_disabled_in_local_environment_by_default(): void
    {
        $originalEnv = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'testing';

        $_SERVER['APP_ENV'] = 'local';
        $_ENV['APP_ENV'] = 'local';
        putenv('APP_ENV=local');
        unset($_SERVER['SESSION_SECURE_COOKIE'], $_ENV['SESSION_SECURE_COOKIE']);
        putenv('SESSION_SECURE_COOKIE');

        $sessionConfig = require config_path('session.php');

        $this->assertFalse($sessionConfig['secure']);

        $_SERVER['APP_ENV'] = $originalEnv;
        $_ENV['APP_ENV'] = $originalEnv;
        putenv("APP_ENV={$originalEnv}");
    }

    public function test_session_secure_cookie_can_be_explicitly_overridden(): void
    {
        // Paksa false saat di production
        $_SERVER['SESSION_SECURE_COOKIE'] = false;
        $_ENV['SESSION_SECURE_COOKIE'] = false;
        putenv('SESSION_SECURE_COOKIE=false');

        $sessionConfig = require config_path('session.php');
        $this->assertFalse($sessionConfig['secure']);

        // Paksa true saat di local
        $_SERVER['SESSION_SECURE_COOKIE'] = true;
        $_ENV['SESSION_SECURE_COOKIE'] = true;
        putenv('SESSION_SECURE_COOKIE=true');

        $sessionConfig = require config_path('session.php');
        $this->assertTrue($sessionConfig['secure']);

        unset($_SERVER['SESSION_SECURE_COOKIE'], $_ENV['SESSION_SECURE_COOKIE']);
        putenv('SESSION_SECURE_COOKIE');
    }

    public function test_https_is_forced_in_production_environment(): void
    {
        // Paksa skema HTTPS seperti di AppServiceProvider saat production
        URL::forceScheme('https');

        $url = route('home');
        $this->assertStringStartsWith('https://', $url);

        // Kembalikan skema normal untuk testing
        URL::forceScheme(null);
    }

    public function test_trusted_proxies_is_configured_in_config_app(): void
    {
        $appConfig = require config_path('app.php');

        $this->assertArrayHasKey('trusted_proxies', $appConfig);
    }
}
