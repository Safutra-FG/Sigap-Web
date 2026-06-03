<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanSelesaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $id_pelapor = 1;
        $id_pekerja = 16;
        $id_admin_bidang = 14;

        // Daftar 5 Bidang berdasarkan data yang sudah kamu buat
        $daftar_bidang = [
            [
                'id_bidang' => 1,
                'kategori' => 'Jalan',
                'kasus' => [
                    'Jalan aspal berlubang parah, membahayakan pengendara roda dua.',
                    'Aspal jalan terkelupas dan bergelombang setelah hujan deras berhari-hari.',
                    'Bahu jalan ambles akibat erosi air selokan di pinggir jalan utama.',
                    'Marka jalan sudah pudar dan tidak terlihat saat malam hari.'
                ]
            ],
            [
                'id_bidang' => 3,
                'kategori' => 'Jembatan',
                'kasus' => [
                    'Besi pembatas jembatan keropos dan beberapa bagian patah.',
                    'Ada retakan pada pilar penyangga jembatan penghubung desa.',
                    'Lantai jembatan kayu sudah lapuk dan bolong di bagian tengah.',
                    'Pondasi jembatan tergerus arus sungai yang deras.'
                ]
            ],
            [
                'id_bidang' => 4,
                'kategori' => 'Sumber Daya Air',
                'kasus' => [
                    'Tanggul irigasi retak rambut, air merembes deras ke sawah warga.',
                    'Pintu air bendungan macet tidak bisa dibuka tutup akibat karat.',
                    'Saluran air tersumbat tumpukan sampah, menyebabkan banjir lokal.',
                    'Bronjong penahan tebing sungai jebol sepanjang 5 meter.'
                ]
            ],
            [
                'id_bidang' => 5,
                'kategori' => 'Cipta Karya',
                'kasus' => [
                    'Fasilitas toilet umum di taman kota rusak dan air tidak mengalir.',
                    'Plafon gedung serbaguna milik desa ambruk sebagian.',
                    'Paving block di area pejalan kaki alun-alun banyak yang hancur.',
                    'Pompa air mancur taman mati total sudah seminggu.'
                ]
            ],
            [
                'id_bidang' => 6,
                'kategori' => 'Tata Ruang',
                'kasus' => [
                    'Terdapat pembangunan liar yang melanggar garis sempadan sungai.',
                    'Alih fungsi lahan terbuka hijau menjadi bangunan komersial tanpa izin.',
                    'Pelanggaran tata letak papan reklame besar yang menutupi rambu lalu lintas.',
                    'Pendirian lapak semi-permanen di atas trotoar jalan utama.'
                ]
            ]
        ];

        // Looping untuk membuat 20 data (Mulai dari ID 053 sampai 072)
        for ($i = 53; $i <= 72; $i++) {

            // Mengambil secara acak (random) 1 bidang dari 5 bidang yang tersedia
            $bidang_random = $daftar_bidang[array_rand($daftar_bidang)];
            // Mengambil kasus acak dari bidang yang terpilih
            $deskripsi_random = $bidang_random['kasus'][array_rand($bidang_random['kasus'])];

            // Format ID Laporan menjadi urut: REP-YYYYMMDD-053, 054, dst
            $format_id = str_pad($i, 3, '0', STR_PAD_LEFT);
            $nomor_laporan = 'REP-' . Carbon::now()->format('Ymd') . '-' . $format_id;

            // Variasi waktu pembuatan laporan (1-48 jam yang lalu)
            $waktu_buat = Carbon::now()->subHours(rand(1, 48));

            // --- 1. INSERT KE TABEL LAPORAN KELUHAN ---
            $id_laporan_masuk = DB::table('laporan_keluhan')->insertGetId([
                'id_laporan' => $nomor_laporan,
                'id_pelapor' => $id_pelapor,
                'kategori_bidang' => $bidang_random['kategori'],
                'deskripsi_laporan' => $deskripsi_random,
                'lokasi_gps' => '-6.' . rand(5000, 6000) . ', 107.' . rand(7000, 8000), // Random koordinat area Subang
                'alamat_map' => 'Lokasi survei di area ' . $bidang_random['kategori'] . ', Kabupaten Subang',
                'foto_bukti' => 'contoh_foto_survei.jpg',
                'video_bukti' => '',
                'status' => 'selesai',
                'catatan_disposisi' => 'Laporan masuk dari masyarakat, segera ditangani oleh tim lapangan.',
                'id_bidang_tujuan' => $bidang_random['id_bidang'],
                'created_at' => $waktu_buat,
                'updated_at' => Carbon::now(),
            ]);

            // --- 2. INSERT KE TABEL PENUGASAN PEKERJA ---
            DB::table('penugasan_pekerja')->insert([
                'id_laporan' => $id_laporan_masuk,
                'id_admin_bidang' => $id_admin_bidang, // Merujuk ke Karyawan Bidang terkait
                'id_pekerja' => $id_pekerja,
                'instruksi_tambahan' => 'Pastikan perbaikan sesuai standar dan dokumentasikan hasil akhirnya.',
                'progres_persen' => 100,
                'status_tugas' => 'selesai',
                'created_at' => $waktu_buat,
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
