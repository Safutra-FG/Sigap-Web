<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bidang', function (Blueprint $table) {
            // Menambahkan kolom status_aktif dengan nilai default true (1)
            $table->boolean('status_aktif')->default(true);
        });
    }

    public function down()
    {
        Schema::table('bidang', function (Blueprint $table) {
            $table->dropColumn('status_aktif');
        });
    }
};
