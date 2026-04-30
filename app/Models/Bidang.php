<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'bidang';

    // Kolom yang boleh diisi (sesuai migrasi Langkah 1)
    protected $fillable = [
        'nama_bidang',
        'kepala_bidang',
        'jumlah_uptd',
        'status'
    ];
}
