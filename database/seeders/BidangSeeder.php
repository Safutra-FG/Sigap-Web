<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        $bidang = [
            ['nama_bidang' => 'Sumber Daya Air (SDA)', 'kepala_bidang' => 'Ir. H. Agus Pratama', 'status' => 'aktif'],
            ['nama_bidang' => 'Bina Marga', 'kepala_bidang' => 'Drs. Budi Santoso', 'status' => 'aktif'],
            ['nama_bidang' => 'Cipta Karya', 'kepala_bidang' => 'Siti Aminah, M.T.', 'status' => 'aktif'],
            ['nama_bidang' => 'Tata Ruang', 'kepala_bidang' => 'Hendra Wijaya, S.T.', 'status' => 'aktif'],
            ['nama_bidang' => 'Jalan & Jembatan', 'kepala_bidang' => 'Rizky Fauzi, S.T.', 'status' => 'aktif'],
        ];

        DB::table('bidang')->insert($bidang);
    }
}
