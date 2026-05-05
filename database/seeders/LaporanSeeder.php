<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaporanKeluhan;
use App\Models\PenugasanPekerja;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class LaporanSeeder extends Seeder
{
    public function run()
    {
        // 1. Matikan sementara sistem keamanan relasi database
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan tabel anak (penugasan) dan tabel induk (laporan)
        PenugasanPekerja::truncate();
        LaporanKeluhan::truncate();

        // 3. Nyalakan kembali sistem keamanan relasinya
        Schema::enableForeignKeyConstraints();

        $data_laporan = [
            // --- DATA BULAN JANUARI ---
            [
                'id_laporan' => 'REP-20260115-001',
                'id_pelapor' => 1,
                'kategori_bidang' => 'Bina Marga',
                'deskripsi_laporan' => 'Jalan berlubang sangat dalam dan membahayakan pengendara motor saat malam hari.',
                'lokasi_gps' => '-6.5627, 107.7613',
                'alamat_map' => 'Jl. Letjen Suprapto, Pasirkareumbi, Subang',
                'foto_bukti' => 'dummy_jalan_lubang.jpg', // DITAMBAHKAN
                'status' => 'selesai',
                'created_at' => Carbon::createFromDate(2026, 1, 15),
                'updated_at' => Carbon::createFromDate(2026, 1, 20),
            ],
            [
                'id_laporan' => 'REP-20260122-002',
                'id_pelapor' => 1,
                'kategori_bidang' => 'SDA',
                'deskripsi_laporan' => 'Saluran irigasi tersumbat sampah dan menyebabkan air meluap ke persawahan.',
                'lokasi_gps' => '-6.5510, 107.7650',
                'alamat_map' => 'Area Sawah Dangdeur, Subang',
                'foto_bukti' => 'dummy_irigasi.jpg', // DITAMBAHKAN
                'status' => 'selesai',
                'created_at' => Carbon::createFromDate(2026, 1, 22),
                'updated_at' => Carbon::createFromDate(2026, 1, 28),
            ],

            // --- DATA BULAN FEBRUARI ---
            [
                'id_laporan' => 'REP-20260210-003',
                'id_pelapor' => 1,
                'kategori_bidang' => 'Cipta Karya',
                'deskripsi_laporan' => 'Lampu penerangan jalan umum (PJU) mati total sepanjang 500 meter.',
                'lokasi_gps' => '-6.5700, 107.7500',
                'alamat_map' => 'Jl. Otto Iskandardinata, Karanganyar, Subang',
                'foto_bukti' => 'dummy_pju_mati.jpg', // DITAMBAHKAN
                'status' => 'selesai',
                'created_at' => Carbon::createFromDate(2026, 2, 10),
                'updated_at' => Carbon::createFromDate(2026, 2, 15),
            ],
            [
                'id_laporan' => 'REP-20260228-004',
                'id_pelapor' => 1,
                'kategori_bidang' => 'Bina Marga',
                'deskripsi_laporan' => 'Aspal mengelupas parah akibat genangan air hujan yang terus menerus.',
                'lokasi_gps' => '-6.5820, 107.7710',
                'alamat_map' => 'Jl. Raya Cijambe, Subang',
                'foto_bukti' => 'dummy_aspal_rusak.jpg', // DITAMBAHKAN
                'status' => 'proses',
                'created_at' => Carbon::createFromDate(2026, 2, 28),
                'updated_at' => Carbon::createFromDate(2026, 3, 5),
            ],

            // --- DATA BULAN MARET ---
            [
                'id_laporan' => 'REP-20260305-005',
                'id_pelapor' => 1,
                'kategori_bidang' => 'SDA',
                'deskripsi_laporan' => 'Tanggul sungai mulai retak dan rawan jebol jika debit air tinggi.',
                'lokasi_gps' => '-6.5400, 107.7800',
                'alamat_map' => 'Bantaran Sungai Ciasem, Subang',
                'foto_bukti' => 'dummy_tanggul.jpg', // DITAMBAHKAN
                'status' => 'proses',
                'created_at' => Carbon::createFromDate(2026, 3, 5),
                'updated_at' => Carbon::createFromDate(2026, 3, 10),
            ],
            [
                'id_laporan' => 'REP-20260320-006',
                'id_pelapor' => 1,
                'kategori_bidang' => 'Cipta Karya',
                'deskripsi_laporan' => 'Fasilitas toilet umum di taman kota rusak dan air tidak mengalir.',
                'lokasi_gps' => '-6.5650, 107.7680',
                'alamat_map' => 'Alun-alun Kabupaten Subang',
                'foto_bukti' => 'dummy_toilet.jpg', // DITAMBAHKAN
                'status' => 'selesai',
                'created_at' => Carbon::createFromDate(2026, 3, 20),
                'updated_at' => Carbon::createFromDate(2026, 3, 22),
            ],

            // --- DATA BULAN APRIL (Bulan Saat Ini) ---
            [
                'id_laporan' => 'REP-20260402-007',
                'id_pelapor' => 1,
                'kategori_bidang' => 'Bina Marga',
                'deskripsi_laporan' => 'Jembatan penghubung desa ambles di bagian ujung pangkalnya.',
                'lokasi_gps' => '-6.5900, 107.7400',
                'alamat_map' => 'Jembatan Desa Cibogo, Subang',
                'foto_bukti' => 'dummy_jembatan.jpg', // DITAMBAHKAN
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'id_laporan' => 'REP-20260415-008',
                'id_pelapor' => 1,
                'kategori_bidang' => 'SDA',
                'deskripsi_laporan' => 'Gorong-gorong mampet menyebabkan banjir setinggi 30cm di jalan raya.',
                'lokasi_gps' => '-6.5580, 107.7550',
                'alamat_map' => 'Jl. KS Tubun, Cigadung, Subang',
                'foto_bukti' => 'dummy_banjir.jpg', // DITAMBAHKAN
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'id_laporan' => 'REP-20260428-009',
                'id_pelapor' => 1,
                'kategori_bidang' => 'Cipta Karya',
                'deskripsi_laporan' => 'Pipa saluran air bersih PDAM bocor merembes ke jalan.',
                'lokasi_gps' => '-6.5750, 107.7650',
                'alamat_map' => 'Perumnas Subang',
                'foto_bukti' => 'dummy_pipa_bocor.jpg', // DITAMBAHKAN
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($data_laporan as $item) {
            LaporanKeluhan::create($item);
        }
    }
}
