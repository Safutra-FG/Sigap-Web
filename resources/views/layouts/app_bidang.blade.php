<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP PUPR - Karyawan Bidang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pupr: {
                            blue: '#1E3A8A',
                            yellow: '#FACC15',
                            lightbg: '#F8FAFC'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>

    @stack('css')
</head>
<body class="flex">

    @php
        // Mengambil data notifikasi untuk Karyawan Bidang
        $semuaNotif = \App\Models\Notifikasi::where('user_id', Auth::id())->latest()->get();
        $notifBelumDibaca = $semuaNotif->where('is_read', false)->count();
        $notifDropdown = $semuaNotif->take(5);

        // Logika Titik Biru Monitoring (Ada update pengerjaan dalam 12 jam terakhir)
        $adaUpdateMonitoring = \App\Models\LaporanKeluhan::where('kategori_bidang', Auth::user()->bidang->nama_bidang ?? '')
                                ->whereIn('status', ['proses', 'selesai'])
                                ->where('updated_at', '>=', now()->subHours(12))
                                ->exists();

        // Sembunyikan titik biru jika sedang membuka halaman Monitoring
        if(request()->routeIs('admin_bidang.monitoring')) {
            $adaUpdateMonitoring = false;
        }
    @endphp

    <aside class="w-64 bg-white min-h-screen border-r border-gray-100 flex flex-col fixed left-0 top-0 h-full z-20 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
        <div class="p-8 flex items-center justify-center border-b border-gray-50">
            <img src="{{ asset('gambar/puprsigap1.png') }}" alt="Logo PUPR SIGAP" class="w-48 object-contain">
        </div>

        <div class="px-6 py-4">
            <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-white text-blue-600 flex items-center justify-center shadow-sm font-bold text-lg">
                    <i class="fas fa-road"></i>
                </div>
                <div>
                    <h3 class="text-xs font-extrabold text-blue-900 leading-tight">Bidang Karyawan</h3>
                    <p class="text-[9px] text-blue-500 font-medium">Sistem Informasi SIGAP</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 mt-2 flex flex-col space-y-1">
            <a href="{{ route('admin_bidang.beranda') }}" class="flex items-center px-8 py-3.5 transition-colors duration-200 {{ request()->routeIs('admin_bidang.beranda') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 font-medium' }}">
                <i class="fas fa-th-large w-6 text-center mr-3 text-lg"></i> Dashboard
            </a>

            <a href="{{ route('admin_bidang.laporan') }}" class="flex items-center px-8 py-3.5 transition-colors duration-200 {{ request()->routeIs('admin_bidang.laporan*') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 font-medium' }}">
                <i class="fas fa-file-alt w-6 text-center mr-3 text-lg"></i> Kelola Laporan
            </a>

            <a href="{{ route('admin_bidang.monitoring') }}" class="flex items-center px-8 py-3.5 transition-colors duration-200 {{ request()->routeIs('admin_bidang.monitoring') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 font-medium' }} justify-between">
                <div class="flex items-center">
                    <i class="fas fa-desktop w-6 text-center mr-3 text-lg"></i> Monitoring
                </div>
                @if($adaUpdateMonitoring)
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(59,130,246,0.8)]"></div>
                @endif
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-64 min-h-screen flex flex-col">

        <header class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center sticky top-0 z-[1500]">
            <div class="relative w-96">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" placeholder="Cari laporan atau petugas..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-pupr-blue focus:ring-1 focus:ring-pupr-blue transition">
            </div>

            <div class="flex items-center space-x-5 relative">

                <div class="relative">
                    <button id="btn-notif" onclick="toggleHeaderMenu('menu-notif')" class="text-gray-400 hover:text-pupr-blue transition relative focus:outline-none p-1">
                        <i class="far fa-bell text-xl"></i>
                        @if($notifBelumDibaca > 0)
                            <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center border-2 border-white animate-pulse">{{ $notifBelumDibaca }}</span>
                        @endif
                    </button>

                    <div id="menu-notif" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden transform transition-all origin-top-right">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-700">Notifikasi Baru</span>
                            <a href="#" class="text-[10px] font-bold text-pupr-blue hover:underline">Tandai dibaca</a>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse($notifDropdown as $notif)
                                <a href="#" class="block px-4 py-3 border-b border-gray-50 transition border-l-4 {{ $notif->is_read ? 'bg-white border-transparent hover:bg-gray-50' : 'bg-blue-50/50 border-blue-500 hover:bg-blue-50' }}">
                                    <p class="text-xs {{ $notif->is_read ? 'text-gray-600 font-medium' : 'text-gray-900 font-bold' }} mb-0.5">{{ $notif->judul }}</p>
                                    <p class="text-[10px] {{ $notif->is_read ? 'text-gray-400' : 'text-gray-600' }}">{{ $notif->pesan }}</p>
                                    <p class="text-[9px] text-gray-400 mt-1 font-medium"><i class="far fa-clock"></i> {{ $notif->created_at->diffForHumans() }}</p>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center text-gray-400 text-xs">Belum ada notifikasi baru.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="h-8 w-px bg-gray-200 mx-2"></div>

                <div class="relative">
                    <div id="btn-profil" onclick="toggleHeaderMenu('menu-profil')" class="flex items-center space-x-3 cursor-pointer p-1.5 rounded-lg hover:bg-gray-50 transition">
                        @if(Auth::user()->foto_profil)
                            <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-md" alt="Profil">
                        @else
                            <div class="w-10 h-10 rounded-full bg-pupr-blue text-white flex items-center justify-center font-bold shadow-md">
                                {{ substr(Auth::user()->nama_lengkap ?? 'K', 0, 1) }}
                            </div>
                        @endif

                        <div class="hidden md:block text-left">
                            <p class="text-sm font-bold text-gray-800 leading-tight">{{ Auth::user()->nama_lengkap ?? 'Karyawan Bidang' }}</p>
                            <p class="text-[10px] text-blue-600 font-bold uppercase tracking-wider">{{ Auth::user()->bidang->nama_bidang ?? 'Bidang SIGAP' }}</p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs ml-1"></i>
                    </div>

                    <div id="menu-profil" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 origin-top-right">
                        <a href="{{ route('admin_bidang.profil') }}" class="block px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            <i class="fas fa-user-circle w-5 text-center mr-1"></i> Profil Saya
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <button type="button" onclick="konfirmasiKeluar()" class="w-full text-left block px-4 py-2.5 text-xs font-semibold text-red-500 hover:bg-red-50 hover:text-red-600 transition">
                            <i class="fas fa-sign-out-alt w-5 text-center mr-1"></i> Keluar
                        </button>
                    </div>
                </div>

            </div>
        </header>

        <div class="p-8 flex-1">
            @yield('konten')
        </div>

    </main>

    <form id="form-keluar" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <script>
        function toggleHeaderMenu(menuId) {
            const menus = ['menu-notif', 'menu-profil'];
            menus.forEach(id => {
                let elemenMenu = document.getElementById(id);
                if (id === menuId) {
                    elemenMenu.classList.toggle('hidden');
                } else {
                    elemenMenu.classList.add('hidden');
                }
            });
        }

        document.addEventListener('click', function(event) {
            if(!event.target.closest('#btn-notif') && !event.target.closest('#menu-notif')) {
                let menu = document.getElementById('menu-notif');
                if(menu) menu.classList.add('hidden');
            }
            if(!event.target.closest('#btn-profil') && !event.target.closest('#menu-profil')) {
                let menu = document.getElementById('menu-profil');
                if(menu) menu.classList.add('hidden');
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function konfirmasiKeluar() {
            Swal.fire({
                title: 'Akhiri Sesi?',
                text: "Apakah Anda yakin ingin keluar dari Dasbor SIGAP? Anda harus login kembali untuk masuk.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-gray-100',
                    title: 'text-gray-800 font-bold',
                    confirmButton: 'px-6 py-2.5 rounded-lg text-sm font-bold shadow-md',
                    cancelButton: 'px-6 py-2.5 rounded-lg text-sm font-bold shadow-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-keluar').submit();
                }
            });
        }
    </script>

    @stack('js')

</body>
</html>
