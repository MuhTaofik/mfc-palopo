<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel members: data member gym (ERD Tugas 4).
     * Dibuat setelah packages dan registrations karena memiliki kunci asing ke keduanya.
     */
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id('id_member');
            $table->string('nama', 100);
            $table->string('kontak', 20);
            $table->foreignId('package_id')
                ->constrained('packages', 'id_package')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('registration_id')
                ->nullable()
                ->constrained('registrations', 'id_registration')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->date('tanggal_daftar');
            $table->date('tanggal_berakhir');
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->timestamps();

            $table->index('tanggal_berakhir');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
