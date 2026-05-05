<?php

namespace App\Http\Controllers\AdminBidang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\LaporanKeluhan; // Nanti kita aktifkan saat tabel laporan sudah siap

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data user yang sedang login
        $user = auth()->user();

        // 2. Ambil data bidang yang terkait dengan user tersebut (Memanfaatkan relasi yang sudah kita buat)
        $bidang = $user->bidang;

        // Validasi keamanan ekstra: Pastikan user ini benar-benar punya bidang
        if (!$bidang) {
            abort(403, 'Akses Ditolak: Akun Anda belum memiliki bidang yang ditugaskan.');
        }

        // Nanti kita akan tambahkan query laporan keluhan khusus bidang ini di sini...
        // $laporan_masuk = LaporanKeluhan::where('id_bidang_tujuan', $user->id_bidang)->count();

        // 3. Kirim data ke tampilan
        return view('admin_bidang.dashboard.index', compact('user', 'bidang'));
    }
}
