<?php

namespace App\Http\Controllers\AdminUniversal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\LogAktivitas;

class ProfilController extends Controller
{
    // Menampilkan halaman profil & Log nyata
    public function indeks()
    {
        $user = Auth::user();

        // Mengambil log nyata milik admin yang sedang login
        $aktivitas_terbaru = LogAktivitas::where('user_id', $user->id)->latest()->take(3)->get();
        $semua_aktivitas = LogAktivitas::where('user_id', $user->id)->latest()->get();

        return view('admin_universal.profil.index', compact('user', 'aktivitas_terbaru', 'semua_aktivitas'));
    }

    // Fitur Ganti Foto (Otomatis hapus foto lama)
    public function updateFoto(Request $request)
    {
        $request->validate(['foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
        $user = Auth::user();

        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama dari storage jika ada
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            // Simpan foto baru dan update database
            $path = $request->file('foto_profil')->store('profil', 'public');
            $user->foto_profil = $path;

            // Catat log aktivitas
            LogAktivitas::create(['user_id' => $user->id, 'aktivitas' => 'Memperbarui foto profil', 'kategori' => 'Akun']);

            $user->save();
        }

        return back()->with('sukses', 'Foto profil berhasil diperbarui!');
    }

    // Fitur Hapus Semua Riwayat Log
    public function hapusLog()
    {
        LogAktivitas::where('user_id', Auth::id())->delete();
        return back()->with('sukses', 'Seluruh riwayat aktivitas berhasil dihapus dari sistem!');
    }
}
