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
            'kode_bidang' => 'required|string|max:50|unique:bidang,kode_bidang',
            'nama_bidang' => 'required|string|max:255',
            'ikon' => 'required|string|max:50',
            'deskripsi' => 'required|string'
        ], [
            'kode_bidang.required' => 'Kode bidang wajib diisi.',
            'kode_bidang.unique'   => 'Kode bidang sudah digunakan.',
            'nama_bidang.required' => 'Nama bidang wajib diisi.',
            'ikon.required'        => 'Ikon bidang wajib diisi.',
            'deskripsi.required'   => 'Deskripsi bidang wajib diisi.'
        ]);

        Bidang::create([
            'kode_bidang' => $request->kode_bidang,
            'nama_bidang' => $request->nama_bidang,
            'ikon'        => $request->ikon,
            'deskripsi'   => $request->deskripsi
        ]);

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
            'kode_bidang' => 'required|string|max:50|unique:bidang,kode_bidang,'.$id,
            'nama_bidang' => 'required|string|max:255',
            'ikon' => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'status_aktif' => 'required|boolean'
        ], [
            'kode_bidang.required' => 'Kode bidang wajib diisi.',
            'kode_bidang.unique'   => 'Kode bidang sudah digunakan.',
            'nama_bidang.required' => 'Nama bidang wajib diisi.',
            'ikon.required'        => 'Ikon bidang wajib diisi.',
            'deskripsi.required'   => 'Deskripsi bidang wajib diisi.',
            'status_aktif.required'=> 'Status aktif wajib dipilih.'
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
            $statusAkunBaru = $request->status_aktif ? 'aktif' : 'non-aktif';

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
