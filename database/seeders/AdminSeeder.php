<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminSeeder extends Seeder
{
    /**
     * Membuat akun admin awal hanya jika belum ada.
     * Kredensial awal diambil dari konfigurasi config('auth.admin.*')
     * agar tetap berfungsi normal saat config:cache aktif di produksi.
     * Tidak menyimpan kata sandi plaintext ke log server.
     */
    public function run(): void
    {
        $username = config('auth.admin.default_user', 'admin');

        // Jika akun admin sudah ada di database, pertahankan dan lewati (idempotent)
        if (Admin::where('username', $username)->exists()) {
            $this->command?->info("Akun admin '{$username}' sudah ada. Seeder dilewati.");

            return;
        }

        $password = config('auth.admin.default_password');

        if (empty($password)) {
            $errorMessage = "Pembuatan akun admin '{$username}' gagal: Variabel ADMIN_DEFAULT_PASSWORD belum dikonfigurasi di file .env / config.";
            $this->command?->error($errorMessage);
            $this->command?->line("Silakan tentukan ADMIN_DEFAULT_PASSWORD pada file .env atau buat kata sandi akun melalui perintah:");
            $this->command?->line("  php artisan admin:reset-password {$username}");

            throw new RuntimeException($errorMessage);
        }

        Admin::create([
            'username'      => $username,
            'password_hash' => Hash::make($password),
        ]);

        $this->command?->info("Akun admin '{$username}' berhasil dibuat.");
    }
}
