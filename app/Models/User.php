<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nama_lengkap', 'username', 'email', 'nomor_hp',
        'password', 'peran', 'id_bidang', 'status_akun',
        'foto_profil', 'terakhir_login',
        'otp_code', 'otp_expires_at', // untuk reset password mobile
        'kantor_wilayah',
    ];

    protected $hidden = [
        'password', 'remember_token', 'otp_code',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'otp_expires_at'    => 'datetime',
            'terakhir_login'    => 'datetime',
        ];
    }

    // Relasi ke tabel bidang
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'id_bidang', 'id');
    }
}
