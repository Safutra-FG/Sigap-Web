<?php

namespace App\Http\Controllers\AdminUniversal;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    // Tampil Daftar Pengguna [cite: 156]
    public function indeks()
    {
        // Ambil semua pengguna beserta data bidangnya
        $pengguna = User::with('bidang')->orderBy('created_at', 'desc')->get();
        // Ambil data bidang yang aktif untuk form tambah pengguna
        $bidang = Bidang::where('status', 'aktif')->get();

        return view('admin_universal.pengguna.index', compact('pengguna', 'bidang'));
    }

    // Simpan Pengguna Baru [cite: 156]
    public function simpan(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'username'   => 'required|unique:users,username',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'peran'      => 'required',
        ]);

        $data = $request->all();
        // Enkripsi kata sandi
        $data['password'] = Hash::make($request->password);

        // Jika peran admin universal, kosongkan id_bidang
        if ($request->peran == 'admin_universal') {
            $data['id_bidang'] = null;
        }

        User::create($data);

        return redirect()->route('admin_universal.pengguna')->with('sukses', 'Akun pengguna berhasil ditambahkan!');
    }

    // Hapus Pengguna [cite: 156]
    public function hapus($id)
    {
        $user = User::findOrFail($id);

        // Mencegah admin menghapus dirinya sendiri
        if (auth()->id() == $user->id) {
            return redirect()->route('admin_universal.pengguna')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();
        return redirect()->route('admin_universal.pengguna')->with('sukses', 'Akun berhasil dihapus!');
    }
}
