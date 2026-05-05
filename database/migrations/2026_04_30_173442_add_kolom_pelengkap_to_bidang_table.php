<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bidang', function (Blueprint $table) {
            $table->string('kode_bidang')->nullable()->after('id');
            $table->text('deskripsi')->nullable()->after('nama_bidang');
            $table->string('ikon')->nullable()->after('deskripsi');
        });
    }

    public function down()
    {
        Schema::table('bidang', function (Blueprint $table) {
            $table->dropColumn(['kode_bidang', 'deskripsi', 'ikon']);
        });
    }
};
