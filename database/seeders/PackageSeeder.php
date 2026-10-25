<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Data awal: dua paket sesuai desain Figma.
     * Aman dijalankan berulang (updateOrCreate tidak membuat data ganda).
     */
    public function run(): void
    {
        Package::updateOrCreate(
            ['nama_paket' => 'Paket Harian'],
            ['durasi_hari' => 1, 'harga' => 15000, 'harga_perpanjangan' => null]
        );

        Package::updateOrCreate(
            ['nama_paket' => 'Paket Bulanan'],
            ['durasi_hari' => 30, 'harga' => 125000, 'harga_perpanjangan' => 100000]
        );
    }
}
