<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel registrations: pendaftaran online dari pengunjung (ERD Tugas 4).
     * Sesuai ERD, tabel ini hanya punya created_at (tanpa updated_at).
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id('id_registration');
            $table->string('nama', 100);
            $table->string('kontak', 20);
            $table->text('alamat');
            $table->foreignId('package_id')
                ->constrained('packages', 'id_package')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->date('tanggal_mulai');
            $table->enum('jenis_pendaftaran', ['Member Baru', 'Perpanjangan'])->default('Member Baru');
            $table->enum('status_pendaftaran', ['Menunggu', 'Diterima', 'Ditolak'])->default('Menunggu');
            $table->text('pesan')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('status_pendaftaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
