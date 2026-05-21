<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sistem_logs', function (Blueprint $table) {
            $table->id(); // Membuat kolom ID otomatis
            $table->string('aktivitas'); // Kolom untuk mencatat teks aktivitas (contoh: "Laporan Baru Masuk")
            $table->string('kategori')->default('umum'); // Kolom untuk memfilter log (contoh: 'laporan' atau 'profil')
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // ID admin yang melakukan aktivitas
            $table->timestamps(); // Membuat kolom created_at dan updated_at
        });
    }
};
