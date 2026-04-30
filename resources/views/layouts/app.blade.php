<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP PUPR - Admin Universal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pupr: {
                            blue: '#1E3A8A',   /* Warna Biru Logo PUPR Resmi */
                            yellow: '#FACC15', /* Warna Kuning Logo PUPR Resmi */
                            lightbg: '#F8FAFC' /* Background body abu-abu sangat muda */
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

        /* Custom scrollbar untuk sidebar jika menunya banyak */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</head>
<body class="flex">

    <aside class="w-64 bg-white min-h-screen border-r border-gray-100 flex flex-col fixed left-0 top-0 h-full z-20 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">

        <div class="p-8 flex items-center justify-center">
            <img src="{{ asset('gambar/puprlogo.png') }}" alt="Logo PUPR" class="w-32 object-contain">
        </div>

        <nav class="flex-1 mt-4 flex flex-col space-y-1">

            <a href="{{ route('admin_universal.beranda') }}"
               class="flex items-center px-8 py-3.5 transition-colors duration-200 {{ request()->routeIs('admin_universal.beranda') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 font-medium' }}">
                <i class="fas fa-th-large w-6 text-center mr-3 text-lg"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin_universal.laporan') }}"
               class="flex items-center px-8 py-3.5 transition-colors duration-200 {{ request()->routeIs('admin_universal.laporan') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 font-medium' }}">
                <i class="fas fa-file-alt w-6 text-center mr-3 text-lg"></i>
                <span>Kelola Laporan</span>
            </a>

            <a href="{{ route('admin_universal.bidang') }}"
               class="flex items-center px-8 py-3.5 transition-colors duration-200 {{ request()->routeIs('admin_universal.bidang') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 font-medium' }}">
                <i class="fas fa-network-wired w-6 text-center mr-3 text-lg"></i>
                <span>Kelola Bidang</span>
            </a>

            <a href="{{ route('admin_universal.pengguna') }}"
               class="flex items-center px-8 py-3.5 transition-colors duration-200 {{ request()->routeIs('admin_universal.pengguna') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 font-medium' }}">
                <i class="fas fa-users w-6 text-center mr-3 text-lg"></i>
                <span>Kelola Pengguna</span>
            </a>

        </nav>

        <div class="p-6 mt-auto">
            <button class="w-full bg-pupr-yellow hover:bg-yellow-500 text-white font-bold py-3.5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center mb-8 active:scale-95">
                <i class="fas fa-map-marked-alt mr-2"></i> Buka Peta Wilayah
            </button>

            <div class="space-y-4 px-2">
                <a href="#" class="flex items-center text-gray-500 hover:text-gray-800 font-medium transition">
                    <i class="far fa-question-circle w-6 text-center mr-3 text-lg"></i> Bantuan
                </a>

                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center text-red-500 hover:text-red-700 font-medium transition w-full text-left">
                        <i class="fas fa-sign-out-alt w-6 text-center mr-3 text-lg"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="flex-1 ml-64 min-h-screen flex flex-col">

        <header class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
            <div class="relative w-96">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" placeholder="Cari laporan atau wilayah..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-pupr-blue focus:ring-1 focus:ring-pupr-blue transition">
            </div>

            <div class="flex items-center space-x-5">
                <button class="text-gray-400 hover:text-pupr-blue transition relative">
                    <i class="far fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <button class="text-gray-400 hover:text-pupr-blue transition">
                    <i class="far fa-question-circle text-xl"></i>
                </button>
                <button class="text-gray-400 hover:text-pupr-blue transition">
                    <i class="fas fa-th text-xl"></i>
                </button>

                <div class="h-8 w-px bg-gray-200 mx-2"></div>

                <div class="flex items-center space-x-3 cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-pupr-blue text-white flex items-center justify-center font-bold shadow-md">
                        {{ substr(Auth::user()->nama_lengkap ?? 'A', 0, 1) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-bold text-gray-800 leading-tight">{{ Auth::user()->nama_lengkap ?? 'Admin Utama' }}</p>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">{{ str_replace('_', ' ', Auth::user()->peran ?? 'Administrator') }}</p>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8 flex-1">
            @yield('konten')
        </div>

    </main>

</body>
</html>
