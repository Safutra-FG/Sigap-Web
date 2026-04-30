<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Universal
        User::create([
            'nama_lengkap' => 'Administrator Utama',
            'username' => 'admin_pusat',
            'email' => 'admin@sigap.id',
            'password' => Hash::make('password123'),
            'peran' => 'admin_universal',
            'status_akun' => 'aktif',
        ]);

        // 2. Akun Admin Bidang
        User::create([
            'nama_lengkap' => 'Admin Bina Marga',
            'username' => 'admin_binamarga',
            'email' => 'binamarga@sigap.id',
            'password' => Hash::make('password123'),
            'peran' => 'admin_bidang',
            'id_bidang' => 2, // Relasi ke ID Bina Marga
            'status_akun' => 'aktif',
        ]);

        // 3. Akun Pekerja UPTD
        User::create([
            'nama_lengkap' => 'Budi Pekerja UPTD',
            'username' => 'pekerja_uptd',
            'email' => 'budi@sigap.id',
            'password' => Hash::make('password123'),
            'peran' => 'pekerja_bidang',
            'id_bidang' => 2,
            'status_akun' => 'aktif',
        ]);
    }
}
