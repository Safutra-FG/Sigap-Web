<?php

namespace App\Http\Controllers\AdminBidang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Models\LaporanKeluhan; // Nanti diaktifkan saat tabel laporan sudah siap

class BerandaController extends Controller
{
    public function indeks()
    {
        // 1. Ambil data admin bidang yang sedang login
        $user = Auth::user();

        // 2. Ambil data bidang yang terkait dengan admin tersebut
        $bidang = $user->bidang;

        // 3. Keamanan Ekstra: Cegah masuk jika admin belum punya bidang
        if (!$bidang) {
            abort(403, 'Akses Ditolak: Akun Anda belum memiliki bidang yang ditugaskan. Hubungi Admin Universal.');
        }

        // Nanti statistik laporan khusus bidang ini ditaruh di sini
        // Contoh: $laporan_masuk = LaporanKeluhan::where('id_bidang_tujuan', $user->id_bidang)->count();

        // 4. Kirim data ke tampilan
        return view('admin_bidang.beranda.indeks', compact('user', 'bidang'));
    }
}
