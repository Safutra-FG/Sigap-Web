<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom kantor_wilayah yang boleh kosong (nullable)
            // after('foto_profil') akan meletakkannya rapi setelah kolom foto_profil
            $table->string('kantor_wilayah')->nullable()->after('foto_profil');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn('kantor_wilayah');
        });
    }
};
