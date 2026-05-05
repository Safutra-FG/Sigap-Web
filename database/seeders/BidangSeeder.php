<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // TAMBAHKAN BARIS INI

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Matikan sementara pengecekan kunci tamu (Foreign Key)
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan tabel bidang dengan aman
        DB::table('bidang')->truncate();

        // 3. Masukkan 5 Bidang PUPR sesuai dengan struktur terbaru
        $bidang = [
            ['nama_bidang' => 'Jalan', 'kepala_bidang' => 'Drs. Budi Santoso', 'status' => 'aktif'],
            ['nama_bidang' => 'Jembatan', 'kepala_bidang' => 'Rizky Fauzi, S.T.', 'status' => 'aktif'],
            ['nama_bidang' => 'Sumber Daya Air (SDA)', 'kepala_bidang' => 'Ir. H. Agus Pratama', 'status' => 'aktif'],
            ['nama_bidang' => 'Cipta Karya', 'kepala_bidang' => 'Siti Aminah, M.T.', 'status' => 'aktif'],
            ['nama_bidang' => 'Tata Ruang', 'kepala_bidang' => 'Hendra Wijaya, S.T.', 'status' => 'aktif'],
        ];

        DB::table('bidang')->insert($bidang);

        // 4. Nyalakan kembali pengecekan kunci tamu demi keamanan database
        Schema::enableForeignKeyConstraints();
    }
}
