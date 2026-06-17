<?php

namespace App\Http\Controllers\AdminBidang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKeluhan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function indeks()
    {
        $user = Auth::user();
        $namaBidangAdmin = $user->bidang->nama_bidang ?? '';

        // 1. Data Pekerja di Lapangan (Status aktif: disurvei, menunggu_validasi, proses)
        $laporan_berjalan = LaporanKeluhan::with(['pekerja', 'pelapor'])
            ->where('kategori_bidang', $namaBidangAdmin)
            ->whereIn('status', ['disurvei', 'menunggu_validasi', 'proses', 'terkendala', 'revisi'])
            ->get();

        // 2. Daftar Antrean Tugas (semua status aktif, diurutkan dari yang terbaru di-update)
        $antrean_tugas = LaporanKeluhan::with('pekerja')
            ->where('kategori_bidang', $namaBidangAdmin)
            ->whereIn('status', ['diteruskan', 'disurvei', 'menunggu_validasi', 'proses', 'terkendala', 'revisi', 'ditunda'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // 3. Kalkulasi Ringkasan & Persentase
        $hari_ini = Carbon::today();

        // Tugas selesai hari ini
        $tugas_selesai = LaporanKeluhan::where('kategori_bidang', $namaBidangAdmin)
            ->where('status', 'selesai')
            ->whereDate('updated_at', $hari_ini)
            ->count();

        // Total seluruh laporan aktif + selesai di bidang ini (sebagai basis persentase)
        $total_laporan = LaporanKeluhan::where('kategori_bidang', $namaBidangAdmin)
            ->whereIn('status', ['diteruskan', 'disurvei', 'menunggu_validasi', 'proses', 'terkendala', 'revisi', 'ditunda', 'selesai'])
            ->count();

        // Jumlah laporan yang sudah selesai (semua waktu)
        $total_selesai = LaporanKeluhan::where('kategori_bidang', $namaBidangAdmin)
            ->where('status', 'selesai')
            ->count();

        // Efisiensi = persentase laporan selesai dari total laporan yang pernah ditangani
        $efisiensi = $total_laporan > 0 ? round(($total_selesai / $total_laporan) * 100) : 0;

        // Untuk kompatibilitas variabel lama di view
        $total_tugas = $total_laporan;

        // 4. Data Peringatan (Tugas aktif yang belum ada update lebih dari 24 jam)
        $peringatan = LaporanKeluhan::with('pekerja')
            ->where('kategori_bidang', $namaBidangAdmin)
            ->whereIn('status', ['disurvei', 'proses', 'terkendala'])
            ->where('updated_at', '<', Carbon::now()->subHours(24))
            ->get();

        return view('admin_bidang.monitoring.index', compact(
            'laporan_berjalan', 'antrean_tugas', 'tugas_selesai', 'total_tugas', 'efisiensi', 'peringatan',
            'total_selesai', 'total_laporan'
        ));
    }
}
