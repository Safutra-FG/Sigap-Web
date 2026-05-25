# SIGAP Web

A Laravel 12-based complaint and task management system for public works and field operations. Project ini digunakan untuk menangani pelaporan keluhan, manajemen bidang, penugasan pekerja lapangan, notifikasi internal, dan ekspor data.

## Fitur Utama

- Autentikasi pengguna dengan peran: `admin_universal`, `admin_bidang`, dan `pekerja_bidang`
- Dashboard pusat untuk manajemen bidang, pengguna, laporan, statistik, dan notifikasi
- Pengelolaan laporan keluhan dengan status, tampilan detail, disposisi/tolak, dan cetak detail PDF
- Ekspor data ke CSV/PDF/Excel untuk laporan dan bidang
- Sistem notifikasi internal untuk admin dan admin bidang
- Profil pengguna dengan pembaruan data dan foto profil
- Pengelolaan bidang dengan status aktif/non-aktif
- Admin bidang dapat menugaskan pekerja dan memantau progres tugas
- Pekerja lapangan melihat tugas dan memperbarui status penyelesaian

## Struktur Peran

- `admin_universal`: akses penuh ke seluruh modul, manajemen bidang, pengguna, laporan, statistik, dan notifikasi
- `admin_bidang`: akses ke laporan bidang tertentu, detail laporan, penugasan pekerja, dan ekspor laporan bidang
- `pekerja_bidang`: akses ke tugas lapangan, update progres tugas, dan penyelesaian penugasan

## Persyaratan

- PHP 8.2+
- Composer
- Node.js 18+ dan npm
- XAMPP, Laragon, atau server lokal serupa
- Database MySQL atau MariaDB

## Instalasi

1. Clone atau salin project ke folder server lokal, misalnya `c:\xampp\htdocs\sigap_web`
2. Jalankan:
   - `composer install`
   - `npm install`
3. Salin file environment:
   - `copy .env.example .env`
4. Sesuaikan konfigurasi database dan storage di `.env`
5. Generate app key:
   - `php artisan key:generate`
6. Jalankan migrasi dan seeder:
   - `php artisan migrate`
   - `php artisan db:seed`
7. Build asset produksi:
   - `npm run build`

> Alternatif setup otomatis:
> - `composer setup` (jika ingin menjalankan rangkaian install, env, key:generate, migrate, dan build dalam sekali jalan)

## Menjalankan Aplikasi

- Jalankan server Laravel:
  - `php artisan serve`
- Jalankan mode development Vite:
  - `npm run dev`

## Akun Default untuk Pengujian

Akun berikut disediakan di `database/seeders/UserSeeder.php`:

- Admin Universal
  - email: `admin@sigap.id`
  - username: `admin_pusat`
  - password: `password123`
- Admin Bidang
  - email: `binamarga@sigap.id`
  - username: `admin_binamarga`
  - password: `password123`
- Pekerja Bidang
  - email: `budi@sigap.id`
  - username: `pekerja_uptd`
  - password: `password123`

## Skrip Composer & NPM

- `composer setup` : install composer, setup env, generate app key, migrate, dan build asset
- `npm run dev` : jalankan Vite development server
- `npm run build` : build assets produksi
- `composer test` : jalankan test suite Laravel

## Rute Utama

- `/` : halaman login
- `admin-universal/beranda` : dashboard admin pusat
- `admin-universal/bidang` : manajemen bidang
- `admin-universal/pengguna` : manajemen pengguna
- `admin-universal/laporan` : daftar laporan pusat
- `admin-universal/statistik` : statistik sistem
- `admin-bidang/beranda` : dashboard admin bidang
- `admin-bidang/laporan` : daftar laporan bidang

## Dependensi Utama

- `laravel/framework` ^12.0
- `barryvdh/laravel-dompdf` ^3.1
- `laravel/sanctum` ^4.3
- `laravel/tinker` ^2.10.1
- `vite` ^7.0.7
- `tailwindcss` ^4.0.0
- `laravel-vite-plugin` ^2.0.0
- `axios` ^1.11.0

## Catatan

Pastikan file `.env` sudah dikonfigurasi dengan benar sebelum menjalankan migrasi. Jika menggunakan storage atau upload gambar, jalankan `php artisan storage:link` bila diperlukan.
