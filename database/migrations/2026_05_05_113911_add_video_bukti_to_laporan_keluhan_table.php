<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_keluhan', function (Blueprint $table) {
            // Menambahkan kolom video_bukti bertipe string (untuk menyimpan path/link file)
            // Kolom ini dibuat nullable() karena tidak semua warga wajib mengunggah video
            // after('foto_bukti') akan meletakkan kolom ini rapi di sebelah kolom foto
            $table->string('video_bukti')->nullable()->after('foto_bukti');
        });
    }

    public function down()
    {
        Schema::table('laporan_keluhan', function (Blueprint $table) {
            // Logika untuk menghapus kolom jika migrasi dibatalkan (rollback)
            $table->dropColumn('video_bukti');
        });
    }
};
