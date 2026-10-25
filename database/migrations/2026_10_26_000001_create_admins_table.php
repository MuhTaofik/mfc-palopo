<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel admins: akun admin untuk login (ERD Tugas 4).
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id('id_admin');
            $table->string('username', 50)->unique();
            $table->string('password_hash');
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
