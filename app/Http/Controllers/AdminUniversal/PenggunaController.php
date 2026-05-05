<?php

namespace App\Http\Controllers\AdminUniversal;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    // Tampil Daftar Pengguna
    public function indeks()
    {
        // Ambil semua pengguna beserta data bidangnya
        $pengguna = User::with('bidang')->orderBy('created_at', 'desc')->get();
        // Ambil data bidang yang aktif untuk form tambah pengguna
        $bidang = Bidang::where('status', 'aktif')->get();

        return view('admin_universal.pengguna.index', compact('pengguna', 'bidang'));
    }

    // Simpan Pengguna Baru
    public function simpan(Request $request)
    {
        // 1. Validasi Super Ketat
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|max:50|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6',
            'peran'        => 'required|in:admin_universal,admin_bidang,pekerja',
            'bidang_id'    => 'nullable' // Tangkap input dari form
        ], [
            'email.unique'   => 'Gagal: Alamat email ini sudah terdaftar pada akun lain!',
            'username.unique'=> 'Gagal: Nama pengguna (username) ini sudah dipakai, cari yang lain!',
            'password.min'   => 'Gagal: Kata sandi terlalu pendek. Wajib minimal 6 karakter demi keamanan.',
        ]);

        // 2. Proses simpan ke database (PERHATIKAN BARIS id_bidang)
        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'peran'        => $request->peran,
            'id_bidang'    => $request->bidang_id, // KIRI: Nama di Database, KANAN: Nama di HTML
        ]);

        // Catat Log
        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menambahkan pengguna baru: {$request->nama_lengkap}",
            'kategori' => 'Akun'
        ]);

        return redirect()->route('admin_universal.pengguna')->with('sukses', 'Pengguna baru berhasil ditambahkan!');
    }

    // Hapus Pengguna
    public function hapus($id)
    {
        $user = User::findOrFail($id);

        // Mencegah admin menghapus dirinya sendiri
        if (auth()->id() == $user->id) {
            return redirect()->route('admin_universal.pengguna')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        // (Opsional) Jika kamu ingin mencatat aktivitas penghapusan ke Log Profil
        $nama = $user->nama_lengkap;
        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus akun pengguna: {$nama}",
            'kategori' => 'Akun'
        ]);

        $user->delete();
        return redirect()->route('admin_universal.pengguna')->with('sukses', 'Akun berhasil dihapus!');
    }
}
