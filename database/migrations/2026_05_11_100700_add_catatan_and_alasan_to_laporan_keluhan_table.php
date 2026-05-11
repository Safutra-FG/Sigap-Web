<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_keluhan', function (Blueprint $table) {
            // Menambahkan kolom catatan_disposisi dan alasan_penolakan
            // Tipe text digunakan karena isinya bisa berupa kalimat panjang
            $table->text('catatan_disposisi')->nullable()->after('status');
            $table->text('alasan_penolakan')->nullable()->after('catatan_disposisi');
        });
    }

    public function down()
    {
        Schema::table('laporan_keluhan', function (Blueprint $table) {
            $table->dropColumn(['catatan_disposisi', 'alasan_penolakan']);
        });
    }
};
