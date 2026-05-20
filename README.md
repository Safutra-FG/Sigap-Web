# SIGAP Web

A Laravel 12-based complaint and task management system for public works and field operations. Project ini menangani alur pelaporan, pengelolaan bidang, tugas admin, notifikasi, dan ekspor data.

## Fitur Utama

- Autentikasi pengguna dengan peran: `admin_universal`, `admin_bidang`, dan `pekerja_bidang`
- Dashboard pusat untuk pengelolaan bidang, pengguna, dan laporan
- Kelola laporan keluhan dengan status, detail, disposisi/tolak, dan cetak detail PDF
- Fitur ekspor data laporan dan bidang ke CSV/PDF/Excel
- Sistem notifikasi internal untuk admin
- Profil pengguna dengan update data dan foto profil
- Pengelolaan bidang dan status aktif/non-aktif
- Dashboard admin bidang untuk memantau laporan bidang dan menugaskan tugas kepada pekerja
- Pengguna pekerja lapangan dapat melihat tugas dan memperbarui progres pekerjaan

## Struktur Peran

- `admin_universal`: akses penuh ke semua data, manajemen bidang, pengguna, laporan, peta wilayah, statistik, dan notifikasi
- `admin_bidang`: akses ke laporan bidang tertentu, detail laporan, penugasan, dan ekspor laporan bidang
- `pekerja_bidang`: akses ke tugas lapangan untuk memperbarui progres dan menyelesaikan penugasan

## Persyaratan

- PHP 8.2+
- Composer
- Node.js & npm
- XAMPP atau server local serupa

## Instalasi

1. Clone atau salin project ke folder server lokal, misalnya `c:\xampp\htdocs\sigap_web`
2. Jalankan:
   - `composer install`
   - `npm install`
3. Salin file environment:
   - `copy .env.example .env`
4. Generate app key:
   - `php artisan key:generate`
5. Jalankan migrasi dan seeder:
   - `php artisan migrate --force`
   - `php artisan db:seed`
6. Build asset produksi:
   - `npm run build`

## Menjalankan Aplikasi

- Mode development:
  - `npm run dev`
- Jalankan server Laravel:
  - `php artisan serve`

## Akun Default untuk Pengujian

Akun berikut tersedia di seeder:

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

## Rute Utama

- `/` : halaman login
- `admin-universal/beranda` : dashboard admin pusat
- `admin-universal/bidang` : manajemen bidang
- `admin-universal/pengguna` : manajemen pengguna
- `admin-universal/laporan` : daftar laporan pusat
- `admin-universal/statistik` : statistik sistem
- `admin-bidang/beranda` : dashboard admin bidang
- `admin-bidang/laporan` : daftar laporan bidang

## Dependensi Penting

- `laravel/framework` ^12.0
- `barryvdh/laravel-dompdf` ^3.1
- `laravel/tinker`
- `vite`, `tailwindcss`, `laravel-vite-plugin`

## Catatan

Project ini sudah menggunakan Laravel 12 dengan Vite untuk asset management. Pastikan database dan file storage sudah dikonfigurasi di `.env` sebelum menjalankan migrasi.

---

File ini sekarang merepresentasikan aplikasi SIGAP, bukan README bawaan skeleton Laravel.
