<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaporanKeluhan;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;
use Carbon\Carbon;

class LaporanKeluhanSeeder extends Seeder
{
    public function run()
    {
        // Gunakan Faker dengan format bahasa Indonesia
        $faker = Faker::create('id_ID');

        // 1. Bersihkan data lama agar tidak menumpuk
        Schema::disableForeignKeyConstraints();
        LaporanKeluhan::truncate();
        Schema::enableForeignKeyConstraints();

        // Bidang dan status disesuaikan dengan data aslimu
        $bidang = ['Bina Marga', 'SDA', 'Cipta Karya'];
        $status = ['pending', 'proses', 'selesai'];

        // 2. Buat 20 data dummy
        for ($i = 1; $i <= 20; $i++) {

            // Generate Koordinat GPS Acak (Area Kabupaten Subang)
            $lat = $faker->latitude(-6.7, -6.4);
            $lng = $faker->longitude(107.5, 107.9);

            $kategori = $faker->randomElement($bidang);

            // Trik API Gambar Dinamis: Sesuaikan kata kunci gambar dengan kategorinya
            $keywordFoto = 'infrastructure';
            if($kategori == 'Bina Marga') $keywordFoto = 'broken,road,street';
            if($kategori == 'SDA') $keywordFoto = 'river,flood,water';
            if($kategori == 'Cipta Karya') $keywordFoto = 'building,city,broken';

            // Generate waktu acak dari awal tahun 2026 sampai saat ini
            $tanggal = $faker->dateTimeBetween('2026-01-01', 'now');

            // 3. Masukkan ke Database menggunakan Eloquent
            LaporanKeluhan::create([
                'id_laporan'        => 'REP-' . date('Ymd', $tanggal->getTimestamp()) . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'id_pelapor'        => 1, // Asumsi ID 1 adalah user pelapor default
                'kategori_bidang'   => $kategori,
                'deskripsi_laporan' => $faker->realText(100), // Keluhan acak
                'lokasi_gps'        => "$lat, $lng",
                'alamat_map'        => $faker->address,

                // Menyuntikkan API Gambar (LoremFlickr)
                'foto_bukti'        => "https://loremflickr.com/800/600/$keywordFoto?random=$i",

                // Menyuntikkan Dummy Video (File MP4 Open Source berukuran kecil)
                'video_bukti'       => 'https://www.w3schools.com/html/mov_bbb.mp4',

                'status'            => $faker->randomElement($status),
                'created_at'        => Carbon::instance($tanggal),
                'updated_at'        => Carbon::instance($tanggal),
            ]);
        }
    }
}
