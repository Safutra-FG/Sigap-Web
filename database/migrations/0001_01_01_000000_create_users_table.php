<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('nomor_hp')->nullable();
            $table->string('password');
            // Role sesuai SRS: admin_universal, admin_bidang, pekerja_bidang, masyarakat
            $table->enum('peran', ['admin_universal', 'admin_bidang', 'pekerja_bidang', 'masyarakat']);
            $table->unsignedBigInteger('id_bidang')->nullable(); // Relasi ke tabel bidang
            $table->enum('status_akun', ['aktif', 'non-aktif'])->default('aktif');
            $table->timestamp('terakhir_login')->nullable();
            $table->string('foto_profil')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
