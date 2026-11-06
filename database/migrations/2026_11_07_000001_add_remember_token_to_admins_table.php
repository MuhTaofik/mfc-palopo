<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom remember_token pada tabel admins
     * agar fitur "Ingat Saya di Perangkat Ini" dapat menyimpan dan
     * memverifikasi token sesi jangka panjang.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->rememberToken()->after('password_hash');
        });
    }

    /**
     * Membalikkan migrasi jika diperlukan.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropRememberToken();
        });
    }
};

