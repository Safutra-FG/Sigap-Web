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

        // 1. Data Pekerja di Lapangan (Hanya yang statusnya 'proses')
        $laporan_berjalan = LaporanKeluhan::with(['pekerja', 'pelapor'])
            ->where('kategori_bidang', $namaBidangAdmin)
            ->where('status', 'proses')
            ->get();

        // 2. Daftar Antrean Tugas (Diurutkan dari yang terbaru di-update)
        $antrean_tugas = LaporanKeluhan::with('pekerja')
            ->where('kategori_bidang', $namaBidangAdmin)
            ->whereIn('status', ['diteruskan', 'proses'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // 3. Kalkulasi Ringkasan Hari Ini
        $hari_ini = Carbon::today();
        $tugas_selesai = LaporanKeluhan::where('kategori_bidang', $namaBidangAdmin)
            ->where('status', 'selesai')
            ->whereDate('updated_at', $hari_ini)
            ->count();

        $total_tugas = LaporanKeluhan::where('kategori_bidang', $namaBidangAdmin)
            ->whereIn('status', ['diteruskan', 'proses', 'selesai'])
            ->count(); // Total semua tugas aktif di bidang ini

        // Efisiensi sederhana (Persentase selesai dari total tugas)
        $efisiensi = $total_tugas > 0 ? round(($tugas_selesai / $total_tugas) * 100) : 100;

        // 4. Data Peringatan (Tugas 'proses' yang belum selesai lebih dari 24 jam)
        $peringatan = LaporanKeluhan::with('pekerja')
            ->where('kategori_bidang', $namaBidangAdmin)
            ->where('status', 'proses')
            ->where('updated_at', '<', Carbon::now()->subHours(24))
            ->get();

        return view('admin_bidang.monitoring.index', compact(
            'laporan_berjalan', 'antrean_tugas', 'tugas_selesai', 'total_tugas', 'efisiensi', 'peringatan'
        ));
    }
}
