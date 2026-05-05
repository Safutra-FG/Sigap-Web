<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'bidang';

    // Kolom yang boleh diisi
    protected $fillable = [
        'kode_bidang',
        'nama_bidang',
        'deskripsi',
        'ikon',
        'kepala_bidang',
        'jumlah_uptd',
        'status',
        'status_aktif'
    ];

    // Relasi ke Laporan Keluhan
    public function laporans()
    {
        // Menghubungkan model Bidang dengan model Laporan (LaporanKeluhan)
        return $this->hasMany(\App\Models\LaporanKeluhan::class, 'kategori_bidang', 'nama_bidang');
    }

    // Relasi ke Akun Pengguna
    public function pengguna()
    {
        // Menghubungkan model Bidang dengan model User menggunakan kolom 'id_bidang'
        return $this->hasMany(\App\Models\User::class, 'id_bidang', 'id');
    }
}
