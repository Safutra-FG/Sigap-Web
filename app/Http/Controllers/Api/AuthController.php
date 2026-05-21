<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register — hanya untuk masyarakat (self-register).
     * Pegawai/Admin dibuat oleh admin_universal via web dashboard.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'username'              => 'required|string|unique:users',
            'phone'                 => 'nullable|string|max:20',
            'password'              => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'nama_lengkap' => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'nomor_hp'     => $request->phone,
            'password'     => Hash::make($request->password),
            'peran'        => 'masyarakat',
            'status_akun'  => 'aktif',
        ]);

        $token = $user->createToken('sigap_mobile')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'user'    => $this->formatUser($user),
            'token'   => $token,
        ], 201);
    }

    /**
     * Login — mendukung login via email atau username.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',  // bisa email atau username
            'password' => 'required|string',
        ]);

        // Coba login via email dulu, fallback ke username
        $user = User::where('email', $request->email)
                    ->orWhere('username', $request->email)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email/username atau password salah.',
            ], 401);
        }

        if ($user->status_akun === 'non-aktif') {
            return response()->json([
                'message' => 'Akun Anda dinonaktifkan. Hubungi admin.',
            ], 403);
        }

        // Hapus token lama, buat yang baru
        $user->tokens()->delete();
        $token = $user->createToken('sigap_mobile')->plainTextToken;

        // Update waktu login terakhir
        $user->update(['terakhir_login' => now()]);

        return response()->json([
            'user'  => $this->formatUser($user),
            'token' => $token,
        ]);
    }

    /**
     * Logout — cabut token aktif.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil.']);
    }

    /**
     * Me — kembalikan data user yang sedang login.
     */
    public function me(Request $request)
    {
        return response()->json($this->formatUser($request->user()));
    }

    /**
     * Kirim OTP ke email untuk reset password.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp  = rand(100000, 999999);
        $user = User::where('email', $request->email)->first();

        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // TODO: kirim email dengan Mail::to($user->email)->send(new OtpMail($otp));
        // Untuk sementara, OTP bisa dilihat di log: storage/logs/laravel.log
        \Log::info("OTP untuk {$user->email}: {$otp}");

        return response()->json(['message' => 'Kode OTP telah dikirim ke email.']);
    }

    /**
     * Verifikasi kode OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string',
        ]);

        $user = User::where('email', $request->email)
                    ->where('otp_code', $request->code)
                    ->where('otp_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Kode OTP tidak valid atau sudah kedaluwarsa.',
            ], 422);
        }

        return response()->json(['message' => 'Kode valid.', 'verified' => true]);
    }

    /**
     * Reset password dengan kode OTP.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'code'     => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)
                    ->where('otp_code', $request->code)
                    ->where('otp_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Token tidak valid atau kedaluwarsa.',
            ], 422);
        }

        $user->update([
            'password'       => Hash::make($request->password),
            'otp_code'       => null,
            'otp_expires_at' => null,
        ]);

        return response()->json(['message' => 'Password berhasil direset. Silakan login.']);
    }

    // ─── HELPER: Format user untuk response mobile ────────────────────────────
    private function formatUser(User $user): array
    {
        return [
            'id'        => $user->id,
            'name'      => $user->nama_lengkap,
            'username'  => $user->username,
            'email'     => $user->email,
            'phone'     => $user->nomor_hp,
            'role'      => $this->mapPeran($user->peran), // 'masyarakat' | 'pegawai'
            'foto_url'  => $user->foto_profil
                           ? asset('storage/' . $user->foto_profil)
                           : null,
            'bidang_id' => $user->id_bidang,
        ];
    }

    // Map peran DB → role mobile
    private function mapPeran(string $peran): string
    {
        return match ($peran) {
            'masyarakat'    => 'masyarakat',
            'pekerja_bidang'=> 'pegawai',
            'admin_bidang'  => 'pegawai',
            'admin_universal' => 'pegawai',
            default         => 'masyarakat',
        };
    }
}
