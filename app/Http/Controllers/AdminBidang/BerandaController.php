<?php

namespace App\Http\Controllers\AdminBidang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKeluhan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    public function indeks()
    {
        $user = Auth::user();

        // Mengambil statistik spesifik hanya untuk bidang admin yang sedang login
        $statistik = [
            // Laporan yang baru didisposisikan ke bidang ini
            'laporan_baru' => LaporanKeluhan::where('id_bidang_tujuan', $user->id_bidang)
                                ->where('status', 'diteruskan')->count(),

            // Laporan yang sedang dikerjakan pekerja
            'dalam_pengerjaan' => LaporanKeluhan::where('id_bidang_tujuan', $user->id_bidang)
                                ->where('status', 'proses')->count(),

            // Total pekerja UPTD yang ada di bidang ini
            'total_pekerja' => User::where('peran', 'pekerja_bidang')
                                ->where('id_bidang', $user->id_bidang)->count(),
        ];

        return view('admin_bidang.beranda', compact('statistik'));
    }
}
