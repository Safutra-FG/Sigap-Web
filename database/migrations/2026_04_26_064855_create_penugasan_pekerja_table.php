<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penugasan_pekerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_laporan')->constrained('laporan_keluhan')->onDelete('cascade');
            $table->foreignId('id_admin_bidang')->constrained('users');
            $table->foreignId('id_pekerja')->constrained('users');
            $table->text('instruksi_tambahan')->nullable();
            $table->integer('progres_persen')->default(0);
            $table->enum('status_tugas', ['ditugaskan', 'dikerjakan', 'selesai', 'terkendala'])->default('ditugaskan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasan_pekerja');
    }
};
