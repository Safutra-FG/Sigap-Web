<?php

namespace App\Http\Controllers\AdminUniversal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama khusus Admin Universal.
     * Mengambil data statistik global sesuai kebutuhan fungsional SRS.
     */
    public function indeks()
    {
        // Statistik global untuk Admin Universal (Pusat)
        $statistik = [
            'total_laporan'    => DB::table('laporan_keluhan')->count(),
            'laporan_terbaru'  => DB::table('laporan_keluhan')->whereDate('created_at', today())->count(),
            'dalam_proses'     => DB::table('laporan_keluhan')->where('status', 'proses')->count(),
            'selesai'          => DB::table('laporan_keluhan')->where('status', 'selesai')->count(),
        ];

        // Mengirim data ke view khusus admin universal
        return view('admin_universal.beranda', compact('statistik'));
    }
}
