<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_keluhan', function (Blueprint $table) {
            $table->id();
            $table->string('id_laporan')->unique(); // Contoh: #SK2601 [cite: 154]
            $table->foreignId('id_pelapor')->constrained('users')->onDelete('cascade');
            $table->string('kategori_bidang'); // FR-01
            $table->text('deskripsi_laporan');
            $table->string('lokasi_gps'); // Koordinat WGS 84 [cite: 205]
            $table->string('alamat_map');
            $table->string('foto_bukti');
            $table->enum('status', ['pending', 'diteruskan', 'proses', 'selesai', 'ditolak', 'terkendala'])->default('pending');
            $table->foreignId('id_bidang_tujuan')->nullable()->constrained('bidang');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_keluhan');
    }
};
