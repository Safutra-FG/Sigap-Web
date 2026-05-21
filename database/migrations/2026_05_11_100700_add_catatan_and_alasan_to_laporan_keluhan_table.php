<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_keluhan', function (Blueprint $table) {
            // Cek apakah kolom catatan_disposisi SUDAH ADA atau BELUM
            if (!Schema::hasColumn('laporan_keluhan', 'catatan_disposisi')) {
                $table->text('catatan_disposisi')->nullable()->after('status');
            }

            // Asumsi ada kolom alasan_penolakan juga di file ini, lakukan pengecekan yang sama
            if (!Schema::hasColumn('laporan_keluhan', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('laporan_keluhan', function (Blueprint $table) {
            $table->dropColumn(['catatan_disposisi', 'alasan_penolakan']);
        });
    }
};
