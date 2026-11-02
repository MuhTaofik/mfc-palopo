<?php

use App\Models\Admin;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:reset-password {username? : Username akun admin yang ingin direset} {--password= : Password baru (opsional)}', function () {
    $username = $this->argument('username');

    if (! $username) {
        $username = $this->ask('Masukkan username admin');
    }

    $admin = Admin::where('username', $username)->first();

    if (! $admin) {
        $this->error("Akun admin dengan username '{$username}' tidak ditemukan.");

        return 1;
    }

    $password = $this->option('password');

    if (! $password) {
        $password = $this->secret('Masukkan password baru (minimal 8 karakter)');
        $confirmation = $this->secret('Konfirmasi password baru');

        if ($password !== $confirmation) {
            $this->error('Konfirmasi password baru tidak cocok.');

            return 1;
        }
    }

    if (strlen($password) < 8) {
        $this->error('Password harus memiliki minimal 8 karakter.');

        return 1;
    }

    $admin->password_hash = Hash::make($password);
    $admin->save();

    $this->info("Password untuk admin '{$username}' berhasil diperbarui.");

    return 0;
})->purpose('Reset password akun pengelola admin secara aman via CLI');
