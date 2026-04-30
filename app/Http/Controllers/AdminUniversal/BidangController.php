<?php

namespace App\Http\Controllers\AdminUniversal;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use Illuminate\Http\Request;

class BidangController extends Controller
{
    // Tampil Daftar Bidang
    public function indeks()
    {
        $semua_bidang = Bidang::all();
        return view('admin_universal.bidang.index', compact('semua_bidang'));
    }

    // Simpan Bidang Baru
    public function simpan(Request $request)
    {
        $request->validate([
            'nama_bidang' => 'required',
            'status' => 'required'
        ]);

        Bidang::create($request->all());

        return redirect()->route('admin_universal.bidang.index')->with('sukses', 'Bidang berhasil ditambahkan!');
    }

    // Update Data Bidang
    public function perbarui(Request $request, $id)
    {
        $bidang = Bidang::findOrFail($id);
        $bidang->update($request->all());

        return redirect()->route('admin_universal.bidang.index')->with('sukses', 'Data bidang berhasil diperbarui!');
    }

    // Hapus Bidang
    public function hapus($id)
    {
        $bidang = Bidang::findOrFail($id);
        $bidang->delete();

        return redirect()->route('admin_universal.bidang.index')->with('sukses', 'Bidang berhasil dihapus!');
    }
}
