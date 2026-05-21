<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Ambil profil user yang login.
     */
    public function getProfil(Request $request)
    {
        return response()->json($this->formatUser($request->user()));
    }

    /**
     * Update nama & nomor HP.
     */
    public function updateProfil(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $request->user()->update([
            'nama_lengkap' => $request->name,
            'nomor_hp'     => $request->phone,
        ]);

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user'    => $this->formatUser($request->user()->fresh()),
        ]);
    }

    /**
     * Upload / ganti foto profil.
     */
    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        // Hapus foto lama dari storage
        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $path = $request->file('foto')->store('profil', 'public');
        $user->update(['foto_profil' => $path]);

        return response()->json([
            'message'  => 'Foto profil diperbarui.',
            'foto_url' => asset('storage/' . $path),
        ]);
    }

    /**
     * Ganti password — memerlukan password saat ini.
     */
    public function gantiPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Password saat ini tidak benar.',
            ], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Password berhasil diubah.']);
    }

    /**
     * Daftar pegawai — khusus admin_universal.
     */
    public function getDaftarPegawai(Request $request)
    {
        $user = $request->user();
        if ($user->peran !== 'admin_universal') {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $pegawai = User::whereIn('peran', ['pekerja_bidang', 'admin_bidang', 'admin_universal'])
                       ->select('id', 'nama_lengkap', 'username', 'email', 'nomor_hp', 'peran', 'foto_profil', 'id_bidang')
                       ->get()
                       ->map(fn($u) => $this->formatUser($u));

        return response()->json($pegawai);
    }

    // ─── HELPER ───────────────────────────────────────────────────────────────
    private function formatUser(User $user): array
    {
        return [
            'id'       => $user->id,
            'name'     => $user->nama_lengkap,
            'username' => $user->username,
            'email'    => $user->email,
            'phone'    => $user->nomor_hp,
            'role'     => match ($user->peran) {
                'masyarakat'      => 'masyarakat',
                'pekerja_bidang'  => 'pegawai',
                'admin_bidang'    => 'pegawai',
                'admin_universal' => 'pegawai',
                default           => 'masyarakat',
            },
            'peran'    => $user->peran,
            'foto_url' => $user->foto_profil
                          ? asset('storage/' . $user->foto_profil)
                          : null,
            'bidang_id' => $user->id_bidang,
        ];
    }
}
