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
        // 1. Validasi File
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $user = auth()->user();

        if ($request->hasFile('foto_profil')) {
            // 2. Hapus foto lama jika sebelumnya sudah punya foto
            if ($user->foto_profil) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto_profil);
            }

            // 3. Simpan foto baru ke folder 'storage/app/public/profil'
            $path = $request->file('foto_profil')->store('profil', 'public');

            // 4. Update nama file di database
            $user->update(['foto_profil' => $path]);

            // 5. Catat Aktivitas (Opsional)
            \App\Models\LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => 'Memperbarui foto profil sistem',
                'kategori' => 'Profil'
            ]);

            // 6. Kembalikan ke halaman dengan pesan SUKSES
            return redirect()->back()->with('sukses', 'Foto profil berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }

    /**
     * Memperbarui Data Profil (Nama, Email, Kontak)
     */
    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        // 1. Validasi input dari form modal
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            // Pastikan email unik, kecuali untuk email user ini sendiri
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nomor_hp' => 'nullable|string|max:20',
            'kantor_wilayah' => 'nullable|string|max:255',
        ]);

        // 2. Update data ke database
        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'nomor_hp' => $request->nomor_hp,
            'kantor_wilayah' => $request->kantor_wilayah,
        ]);

        // 3. Catat ke riwayat aktivitas (opsional tapi disarankan untuk SIGAP)
        \App\Models\LogAktivitas::create([
            'user_id' => $user->id,
            'aktivitas' => 'Memperbarui data identitas dan kontak profil',
            'kategori' => 'Profil'
        ]);

        return redirect()->back()->with('sukses', 'Data profil berhasil diperbarui!');
    }

    /**
     * Menghapus Foto Profil dan kembali ke inisial nama
     */
    public function hapusFoto()
    {
        $user = auth()->user();

        // Cek apakah user benar-benar punya foto profil
        if ($user->foto_profil) {
            // Hapus file fisik dari folder storage public
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto_profil);

            // Kosongkan nama file di database
            $user->update(['foto_profil' => null]);

            // Catat log aktivitas
            \App\Models\LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => 'Menghapus foto profil sistem',
                'kategori' => 'Profil'
            ]);
        }

        return redirect()->back()->with('sukses', 'Foto profil berhasil dihapus!');
    }

    // Fitur Hapus Semua Riwayat Log
    public function hapusLog()
    {
        LogAktivitas::where('user_id', Auth::id())->delete();
        return back()->with('sukses', 'Seluruh riwayat aktivitas berhasil dihapus dari sistem!');
    }
}
