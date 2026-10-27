<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Membuat akun admin awal untuk pengujian panel admin.
     * Username: admin
     * Password: admin123
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['username' => 'admin'],
            ['password_hash' => Hash::make('admin123')]
        );
    }
}
