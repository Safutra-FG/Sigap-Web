<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menambahkan nilai 'disurvei' ke ENUM status laporan_keluhan.
     *
     * Alur status yang benar setelah perubahan ini:
     *   pending → diteruskan → disurvei → menunggu_validasi → proses → selesai
     *                                    ↘ ditunda / ditolak
     *
     * Status 'disurvei' menandakan pekerja UPTD sudah ditugaskan dan
     * sedang melakukan survey lapangan. Setelah survey selesai dan
     * dilaporkan, status berubah ke 'menunggu_validasi' untuk dikonfirmasi admin.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE laporan_keluhan MODIFY COLUMN status ENUM(
            'pending',
            'diteruskan',
            'dikembalikan',
            'disurvei',
            'menunggu_validasi',
            'ditolak',
            'ditunda',
            'proses',
            'terkendala',
            'revisi',
            'selesai'
        ) DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE laporan_keluhan MODIFY COLUMN status ENUM(
            'pending',
            'diteruskan',
            'dikembalikan',
            'menunggu_validasi',
            'ditolak',
            'ditunda',
            'proses',
            'terkendala',
            'revisi',
            'selesai'
        ) DEFAULT 'pending'");
    }
};

