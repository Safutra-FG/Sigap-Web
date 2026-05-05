<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKeluhan extends Model
{
    use HasFactory;

    protected $table = 'laporan_keluhan';

    protected $fillable = [
        'id_laporan',
        'id_pelapor',
        'kategori_bidang',
        'deskripsi_laporan',
        'lokasi_gps',
        'alamat_map',
        'foto_bukti',
        'video_bukti',
        'status',
        'id_bidang_tujuan',
        'alasan_penolakan'
    ];

    // Relasi ke User (Masyarakat yang melapor)
    public function pelapor()
    {
        return $this->belongsTo(User::class, 'id_pelapor');
    }

    // Relasi ke Bidang (Tujuan disposisi)
    public function bidangTujuan()
    {
        return $this->belongsTo(Bidang::class, 'id_bidang_tujuan');
    }
}
