<?php

namespace App\Http\Controllers\AdminBidang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKeluhan;
use App\Models\User;
use App\Models\PenugasanPekerja;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // Tampil daftar laporan khusus bidang yang sedang login
    public function indeks()
    {
        $user = Auth::user();

        // Hanya ambil laporan yang ditujukan ke id_bidang milik admin ini
        $laporan_masuk = LaporanKeluhan::with('pelapor')
            ->where('id_bidang_tujuan', $user->id_bidang)
            ->whereIn('status', ['diteruskan', 'proses', 'selesai'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin_bidang.laporan.index', compact('laporan_masuk'));
    }

    // Tampil detail laporan dan form penugasan
    public function detail($id)
    {
        $user = Auth::user();
        $laporan = LaporanKeluhan::with('pelapor')->where('id_bidang_tujuan', $user->id_bidang)->findOrFail($id);

        // Ambil data pekerja yang berada di bidang yang sama
        $pekerja = User::where('peran', 'pekerja_bidang')
                       ->where('id_bidang', $user->id_bidang)
                       ->where('status_akun', 'aktif')
                       ->get();

        return view('admin_bidang.laporan.detail', compact('laporan', 'pekerja'));
    }

    // Proses Menugaskan Pekerja
    public function tugaskan(Request $request, $id)
    {
        $request->validate([
            'id_pekerja' => 'required'
        ]);

        $user = Auth::user();
        $laporan = LaporanKeluhan::where('id_bidang_tujuan', $user->id_bidang)->findOrFail($id);

        // Buat data penugasan baru
        PenugasanPekerja::create([
            'id_laporan' => $laporan->id,
            'id_admin_bidang' => $user->id,
            'id_pekerja' => $request->id_pekerja,
            'instruksi_tambahan' => $request->instruksi_tambahan,
            'status_tugas' => 'ditugaskan'
        ]);

        // Ubah status laporan utama menjadi proses
        $laporan->update(['status' => 'proses']);

        return redirect()->route('admin_bidang.laporan')->with('sukses', 'Pekerja berhasil ditugaskan ke lokasi laporan!');
    }
}
