<?php

namespace App\Http\Controllers\AdminUniversal;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use Illuminate\Http\Request;

class BidangController extends Controller
{
    // Tampil Daftar Bidang
    public function index(\Illuminate\Http\Request $request)
    {
        // KUNCI DINAMIS: Tambahkan whereHas('pengguna') di sini!
        $query = \App\Models\Bidang::withCount('laporans');

        // Filter status menggunakan kolom bahasa Indonesia 'status_aktif'
        if ($request->status == 'aktif') {
            $query->where('status_aktif', true);
        } elseif ($request->status == 'nonaktif') {
            $query->where('status_aktif', false);
        }

        $semua_bidang = $query->paginate(10);
        return view('admin_universal.bidang.index', compact('semua_bidang'));
    }

    public function exportCsv()
    {
        // Pastikan CSV juga hanya mengekspor bidang yang ada penggunanya
        $bidangs = \App\Models\Bidang::withCount('laporans')->whereHas('pengguna')->get();

        $fileName = 'Data_Bidang_Infrastruktur_' . date('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Kode Bidang', 'Nama Bidang', 'Deskripsi', 'Total Laporan', 'Status Aktif'];

        $callback = function() use($bidangs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // Header CSV

            foreach ($bidangs as $b) {
                $status = $b->status_aktif ? 'Aktif' : 'Tidak Aktif';
                fputcsv($file, [
                    $b->id, $b->kode_bidang, $b->nama_bidang, $b->deskripsi ?? '-', $b->laporans_count, $status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Simpan Bidang Baru
    public function simpan(Request $request)
    {
        $request->validate([
            'kode_bidang' => 'required',
            'nama_bidang' => 'required',
            'ikon' => 'required',
            'deskripsi' => 'required'
        ]);

        Bidang::create($request->all());

        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menambahkan bidang infrastruktur baru: {$request->nama_bidang}",
            'kategori' => 'Bidang'
        ]);

        return redirect()->route('admin_universal.bidang')->with('sukses', 'Bidang berhasil ditambahkan!');
    }

    // Update Data Bidang
    public function perbarui(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'kode_bidang' => 'required',
            'nama_bidang' => 'required',
            'ikon' => 'required',
            'deskripsi' => 'required',
            'status_aktif' => 'required|boolean'
        ]);

        $bidang = Bidang::findOrFail($id);

        // 2. Simpan status lama untuk mengecek apakah ada perubahan
        $statusLama = $bidang->status_aktif;

        // 3. Update data bidang dengan data dari form
        $bidang->update($request->all());

        // ====================================================================
        // 4. LOGIKA CASCADE: Jika status bidang berubah, sinkronkan akunnya!
        // ====================================================================
        if ($statusLama != $request->status_aktif) {

            // Tentukan teks status akun baru. (Ubah 'nonaktif' jadi 'non-aktif' jika di databasemu menggunakan strip)
            $statusAkunBaru = $request->status_aktif ? 'aktif' : 'nonaktif';

            // Cari semua user yang ada di bidang ini, lalu update statusnya secara massal
            \App\Models\User::where('id_bidang', $id)->update([
                'status_akun' => $statusAkunBaru
            ]);
        }

        // 5. Catat Log
        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Memperbarui data dan status bidang: {$request->nama_bidang}",
            'kategori' => 'Bidang'
        ]);

        return redirect()->route('admin_universal.bidang')->with('sukses', 'Data bidang dan status pengguna berhasil diperbarui!');
    }

    // Hapus Bidang
    public function hapus($id)
    {
        $bidang = Bidang::findOrFail($id);

        // BONUS FIX: Simpan nama bidang sebelum dihapus untuk dipakai di Log Aktivitas
        $nama = $bidang->nama_bidang;

        $bidang->delete();

        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus bidang infrastruktur: {$nama}", // Sekarang tidak akan error lagi
            'kategori' => 'Bidang'
        ]);

        return redirect()->route('admin_universal.bidang')->with('sukses', 'Bidang berhasil dihapus!');
    }

    // Menampilkan Halaman Edit Bidang
    public function edit($id)
    {
        $bidang = \App\Models\Bidang::findOrFail($id);
        return view('admin_universal.bidang.edit', compact('bidang'));
    }
}
