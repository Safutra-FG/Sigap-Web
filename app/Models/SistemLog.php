<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SistemLog extends Model
{
    use HasFactory;

    // Menentukan nama tabel di database
    protected $table = 'sistem_logs';

    // Menentukan kolom apa saja yang boleh diisi (mass assignable)
    protected $fillable = [
        'aktivitas',
        'kategori',
        'user_id'
    ];
}
