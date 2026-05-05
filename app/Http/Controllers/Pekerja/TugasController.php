<?php

namespace App\Http\Controllers\Pekerja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenugasanPekerja;
use App\Models\LaporanKeluhan;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    // Menampilkan daftar tugas untuk pekerja yang sedang login
    public function indeks()
    {
        $user = Auth::user();

        // Ambil tugas yang diberikan khusus untuk pekerja ini
        $daftar_tugas = PenugasanPekerja::with(['laporan.pelapor', 'admin'])
            ->where('id_pekerja', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pekerja.beranda', compact('daftar_tugas'));
    }

    // Memperbarui progres pekerjaan di lapangan
    public function updateProgres(Request $request, $id)
    {
        $request->validate([
            'progres_persen' => 'required|numeric|min:0|max:100',
            'status_tugas' => 'required'
        ]);

        $penugasan = PenugasanPekerja::findOrFail($id);
        $penugasan->update([
            'progres_persen' => $request->progres_persen,
            'status_tugas' => $request->status_tugas
        ]);

        // Jika progres sudah 100% atau status Selesai, update juga laporan utamanya
        if ($request->progres_persen == 100 || $request->status_tugas == 'selesai') {
            $penugasan->update(['status_tugas' => 'selesai', 'progres_persen' => 100]);

            $laporan = LaporanKeluhan::find($penugasan->id_laporan);
            if($laporan) {
                $laporan->update(['status' => 'selesai']);
            }
        } elseif ($request->status_tugas == 'terkendala') {
            // Jika terkendala di lapangan
            $laporan = LaporanKeluhan::find($penugasan->id_laporan);
            if($laporan) {
                $laporan->update(['status' => 'terkendala']);
            }
        }

        return redirect()->route('pekerja.beranda')->with('sukses', 'Progres pekerjaan berhasil diperbarui!');
    }
}
