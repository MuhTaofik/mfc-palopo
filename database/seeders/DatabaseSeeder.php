<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan seeder aplikasi.
     * Akun admin dibuat terpisah pada tahap login (kata sandi di-hash).
     */
    public function run(): void
    {
        $this->call([
            PackageSeeder::class,
        ]);
    }
}
