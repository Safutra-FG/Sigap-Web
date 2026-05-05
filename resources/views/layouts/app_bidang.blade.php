<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Bidang - SIGAP PUPR</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Nanti script CSS Peta (Leaflet/Mapbox) akan kita taruh di sini -->
    @stack('css')
</head>
<body class="bg-[#F8FAFC] font-sans text-gray-800 antialiased flex h-screen overflow-hidden">

    <!-- SIDEBAR KIRI -->
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between h-full flex-shrink-0 z-20">
        <div>
            <!-- Logo Area -->
            <div class="h-16 flex items-center px-6 border-b border-gray-50 mb-6">
                <h1 class="text-xl font-extrabold text-[#1E3A8A] tracking-tight">SIGAP <span class="text-yellow-500">PUPR</span></h1>
            </div>

            <!-- Identitas Bidang (Badge Biru) -->
            <div class="px-5 mb-6">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white text-blue-600 flex items-center justify-center shadow-sm">
                        <i class="{{ auth()->user()->bidang->ikon ?? 'fas fa-building' }}"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-blue-900">Bidang Admin</h3>
                        <p class="text-[10px] text-blue-600 line-clamp-1">Sistem Informasi SIGAP</p>
                    </div>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="px-3 space-y-1">
                <a href="{{ route('admin_bidang.beranda') }}" class="flex items-center px-3 py-2.5 rounded-lg bg-blue-50 text-blue-600 font-bold text-sm mb-1">
                    <i class="fas fa-th-large w-6 text-center mr-2"></i> Dashboard
                </a>
                <a href="#" class="flex items-center px-3 py-2.5 rounded-lg text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium text-sm mb-1 transition">
                    <i class="fas fa-file-alt w-6 text-center mr-2"></i> Kelola Laporan
                </a>
                <a href="#" class="flex items-center px-3 py-2.5 rounded-lg text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium text-sm mb-1 transition">
                    <i class="fas fa-clipboard-check w-6 text-center mr-2"></i> Penugasan
                </a>
                <div class="flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium text-sm mb-1 transition cursor-pointer">
                    <div class="flex items-center">
                        <i class="fas fa-desktop w-6 text-center mr-2"></i> Monitoring
                    </div>
                    <i class="fas fa-circle text-[6px] text-blue-600"></i>
                </div>
            </nav>
        </div>

        <!-- Menu Bawah Sidebar -->
        <div class="p-4 border-t border-gray-50">
            <a href="#" class="flex items-center px-3 py-2 rounded-lg text-gray-500 hover:text-gray-700 font-medium text-sm transition">
                <i class="far fa-question-circle w-6 text-center mr-2"></i> Bantuan
            </a>
            <!-- Form Logout Sementara -->
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="w-full flex items-center px-3 py-2 rounded-lg text-red-500 hover:bg-red-50 font-medium text-sm transition">
                    <i class="fas fa-sign-out-alt w-6 text-center mr-2"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- AREA KANAN (HEADER & KONTEN UTAMA) -->
    <div class="flex-1 flex flex-col h-full relative">

        <!-- TOP NAVBAR -->
        <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-8 z-10">
            <!-- Search Bar -->
            <div class="relative w-96">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" placeholder="Cari laporan atau petugas..." class="w-full bg-gray-50 border border-transparent focus:border-gray-200 focus:bg-white rounded-full py-2 pl-10 pr-4 text-sm outline-none transition">
            </div>

            <!-- Profil & Notifikasi -->
            <div class="flex items-center gap-5">
                <button class="text-gray-400 hover:text-gray-600 transition relative">
                    <i class="far fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <div class="h-8 w-px bg-gray-200"></div>
                <div class="flex items-center gap-3 cursor-pointer">
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-800 leading-tight">{{ auth()->user()->nama_lengkap }}</p>
                        <p class="text-[10px] font-bold text-blue-600 uppercase">{{ auth()->user()->bidang->nama_bidang ?? 'Admin' }}</p>
                    </div>
                    <img src="{{ auth()->user()->foto_profil ? asset('storage/'.auth()->user()->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->nama_lengkap).'&background=1E3A8A&color=fff' }}" alt="Profil" class="w-9 h-9 rounded-full border-2 border-gray-100 object-cover">
                </div>
            </div>
        </header>

        <!-- KONTEN UTAMA (Bisa di-scroll) -->
        <main class="flex-1 overflow-y-auto p-8">
            @yield('konten')
        </main>

    </div>

    <!-- Nanti script JS Peta (Leaflet/Mapbox) akan kita taruh di sini -->
    @stack('js')
</body>
</html>
