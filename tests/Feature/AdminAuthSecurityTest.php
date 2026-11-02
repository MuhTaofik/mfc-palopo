<?php

namespace Tests\Feature;

use App\Models\Admin;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

/**
 * Pengujian keamanan untuk perbaikan E3 — Keamanan Akun Admin MFC Palopo:
 * - Rate limiting brute-force login (maksimal 5 percobaan gagal per menit)
 * - Pembaruan password terautentikasi dengan verifikasi password lama
 * - Validasi kekuatan & konfirmasi password baru
 * - Idempotensi AdminSeeder (tidak menimpa akun & password yang sudah ada)
 * - Artisan command admin:reset-password untuk administrasi darurat via CLI
 */
class AdminAuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        RateLimiter::clear('admin-login|127.0.0.1');

        $this->admin = Admin::create([
            'username'      => 'admin_test',
            'password_hash' => Hash::make('CurrentPassword123'),
        ]);
    }

    public function test_admin_can_login_with_correct_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'username' => 'admin_test',
            'password' => 'CurrentPassword123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->admin, 'admin');
    }

    public function test_login_rate_limiting_blocks_after_five_failed_attempts(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->from('/admin/login')->post('/admin/login', [
                'username' => 'admin_test',
                'password' => 'WrongPassword',
            ]);

            $response->assertSessionHasErrors('username');
            $this->assertGuest('admin');
        }

        // Percobaan ke-6 harus di-block oleh rate limiter
        $blockedResponse = $this->from('/admin/login')->post('/admin/login', [
            'username' => 'admin_test',
            'password' => 'WrongPassword',
        ]);

        $blockedResponse->assertSessionHasErrors('username');
        $errors = session('errors')->get('username');
        $this->assertStringContainsString('Terlalu banyak percobaan login', $errors[0]);
    }

    public function test_guest_cannot_access_change_password_page(): void
    {
        $response = $this->get('/admin/password');

        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_view_change_password_page(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/password');

        $response->assertStatus(200);
        $response->assertSee('Ganti Password');
        $response->assertSee('Password Saat Ini');
        $response->assertSee('Password Baru');
        $response->assertSee('Konfirmasi Password Baru');
    }

    public function test_admin_cannot_update_password_with_incorrect_current_password(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->from('/admin/password')
            ->put('/admin/password', [
                'current_password'      => 'SalahTotal123',
                'password'              => 'BrandNewPassword123',
                'password_confirmation' => 'BrandNewPassword123',
            ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('CurrentPassword123', $this->admin->fresh()->password_hash));
    }

    public function test_admin_cannot_update_password_with_unconfirmed_new_password(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->from('/admin/password')
            ->put('/admin/password', [
                'current_password'      => 'CurrentPassword123',
                'password'              => 'BrandNewPassword123',
                'password_confirmation' => 'DifferentPassword123',
            ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('CurrentPassword123', $this->admin->fresh()->password_hash));
    }

    public function test_admin_cannot_update_password_with_short_new_password(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->from('/admin/password')
            ->put('/admin/password', [
                'current_password'      => 'CurrentPassword123',
                'password'              => 'short',
                'password_confirmation' => 'short',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_admin_cannot_reuse_same_password_as_new_password(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->from('/admin/password')
            ->put('/admin/password', [
                'current_password'      => 'CurrentPassword123',
                'password'              => 'CurrentPassword123',
                'password_confirmation' => 'CurrentPassword123',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_admin_can_successfully_update_password(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->from('/admin/password')
            ->put('/admin/password', [
                'current_password'      => 'CurrentPassword123',
                'password'              => 'BrandNewPassword123',
                'password_confirmation' => 'BrandNewPassword123',
            ]);

        $response->assertRedirect('/admin/password');
        $response->assertSessionHas('success');

        $this->assertTrue(Hash::check('BrandNewPassword123', $this->admin->fresh()->password_hash));
    }

    public function test_admin_seeder_does_not_overwrite_existing_admin_password(): void
    {
        $existingAdmin = Admin::create([
            'username'      => 'admin',
            'password_hash' => Hash::make('CustomAdminExistingPass123'),
        ]);

        $this->seed(AdminSeeder::class);

        $freshAdmin = Admin::where('username', 'admin')->first();
        $this->assertNotNull($freshAdmin);
        $this->assertTrue(Hash::check('CustomAdminExistingPass123', $freshAdmin->password_hash));
        $this->assertEquals(2, Admin::count()); // admin_test + admin
    }

    public function test_admin_seeder_fails_and_throws_exception_when_default_password_is_not_set(): void
    {
        // Pastikan tidak ada akun dengan username 'admin'
        Admin::where('username', 'admin')->delete();

        config(['auth.admin.default_password' => null]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Variabel ADMIN_DEFAULT_PASSWORD belum dikonfigurasi');

        $this->seed(AdminSeeder::class);
    }

    public function test_admin_seeder_creates_admin_when_default_password_is_set(): void
    {
        // Pastikan tidak ada akun dengan username 'admin'
        Admin::where('username', 'admin')->delete();

        config([
            'auth.admin.default_user'     => 'admin',
            'auth.admin.default_password' => 'StrongConfiguredPassword789',
        ]);

        $this->seed(AdminSeeder::class);

        $createdAdmin = Admin::where('username', 'admin')->first();
        $this->assertNotNull($createdAdmin);
        $this->assertTrue(Hash::check('StrongConfiguredPassword789', $createdAdmin->password_hash));
    }

    public function test_artisan_admin_reset_password_command_updates_password(): void
    {
        $this->artisan('admin:reset-password', [
            'username'   => 'admin_test',
            '--password' => 'NewCliSecretPassword456',
        ])
            ->expectsOutput("Password untuk admin 'admin_test' berhasil diperbarui.")
            ->assertExitCode(0);

        $this->assertTrue(Hash::check('NewCliSecretPassword456', $this->admin->fresh()->password_hash));
    }

    public function test_artisan_admin_reset_password_command_fails_for_nonexistent_user(): void
    {
        $this->artisan('admin:reset-password', [
            'username'   => 'user_tidak_ada',
            '--password' => 'SomePassword123',
        ])
            ->expectsOutput("Akun admin dengan username 'user_tidak_ada' tidak ditemukan.")
            ->assertExitCode(1);
    }
}

