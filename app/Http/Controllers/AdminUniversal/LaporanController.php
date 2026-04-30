<?php

namespace App\Http\Controllers\AdminUniversal;

use App\Http\Controllers\Controller;
use App\Models\LaporanKeluhan;
use App\Models\Bidang;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    // Menampilkan daftar semua laporan
    public function indeks()
    {
        // Mengambil data laporan beserta data pelapornya, diurutkan dari yang terbaru
        $semua_laporan = LaporanKeluhan::with('pelapor')->orderBy('created_at', 'desc')->get();

        $statistik = [
            'total' => LaporanKeluhan::count(),
            'terbaru' => LaporanKeluhan::where('status', 'pending')->count(),
            'proses' => LaporanKeluhan::whereIn('status', ['diteruskan', 'proses'])->count(),
            'selesai' => LaporanKeluhan::where('status', 'selesai')->count(),
        ];

        return view('admin_universal.laporan.index', compact('semua_laporan', 'statistik'));
    }

    // Menampilkan detail spesifik satu laporan
    public function detail($id)
    {
        $laporan = LaporanKeluhan::with('pelapor', 'bidangTujuan')->findOrFail($id);
        $bidang_aktif = Bidang::where('status', 'aktif')->get(); // Untuk dropdown disposisi

        return view('admin_universal.laporan.detail', compact('laporan', 'bidang_aktif'));
    }

    // Proses Meneruskan (Disposisi) Laporan ke Bidang
    public function disposisi(Request $request, $id)
    {
        $request->validate([
            'id_bidang_tujuan' => 'required'
        ]);

        $laporan = LaporanKeluhan::findOrFail($id);
        $laporan->update([
            'status' => 'diteruskan',
            'id_bidang_tujuan' => $request->id_bidang_tujuan
        ]);

        return redirect()->route('admin_universal.laporan')->with('sukses', 'Laporan berhasil didisposisikan ke bidang terkait!');
    }

    // Proses Menolak Laporan
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required'
        ]);

        $laporan = LaporanKeluhan::findOrFail($id);
        $laporan->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan
        ]);

        return redirect()->route('admin_universal.laporan')->with('sukses', 'Laporan telah ditolak.');
    }
}
