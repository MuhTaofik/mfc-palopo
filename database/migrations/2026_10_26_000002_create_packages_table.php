<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel packages: data paket dan harga (ERD Tugas 4).
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id('id_package');
            $table->string('nama_paket', 50)->unique();
            $table->unsignedInteger('durasi_hari');
            $table->unsignedInteger('harga');
            $table->unsignedInteger('harga_perpanjangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
