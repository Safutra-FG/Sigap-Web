<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP PUPR - Dasbor Bidang</title>
    <!-- Tailwind CSS & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pupr: { blue: '#1E3A8A', yellow: '#FACC15', lightbg: '#F8FAFC' }
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="flex">

    <!-- SIDEBAR KHUSUS ADMIN BIDANG -->
    <aside class="w-64 bg-white min-h-screen border-r border-gray-100 flex flex-col fixed left-0 top-0 h-full z-20 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">

        <div class="p-8 flex items-center justify-center border-b border-gray-50">
            <img src="{{ asset('gambar/puprlogo.png') }}" alt="Logo PUPR" class="w-32 object-contain">
        </div>

        <!-- Menu Navigasi Admin Bidang[cite: 1] -->
        <nav class="flex-1 mt-4 flex flex-col space-y-1">

            <a href="{{ route('admin_bidang.beranda') }}"
               class="flex items-center px-8 py-3.5 transition-colors duration-200 {{ request()->routeIs('admin_bidang.beranda') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 font-medium' }}">
                <i class="fas fa-th-large w-6 text-center mr-3 text-lg"></i>
                <span>Dasbor Bidang</span>
            </a>

            <a href="#" class="flex items-center px-8 py-3.5 transition-colors duration-200 text-gray-500 hover:bg-gray-50 hover:text-gray-800 font-medium">
                <i class="fas fa-inbox w-6 text-center mr-3 text-lg"></i>
                <span>Laporan Masuk</span>
            </a>

            <a href="#" class="flex items-center px-8 py-3.5 transition-colors duration-200 text-gray-500 hover:bg-gray-50 hover:text-gray-800 font-medium">
                <i class="fas fa-hard-hat w-6 text-center mr-3 text-lg"></i>
                <span>Penugasan Pekerja</span>
            </a>

            <a href="#" class="flex items-center px-8 py-3.5 transition-colors duration-200 text-gray-500 hover:bg-gray-50 hover:text-gray-800 font-medium">
                <i class="fas fa-chart-line w-6 text-center mr-3 text-lg"></i>
                <span>Monitoring Progres</span>
            </a>

        </nav>

        <div class="p-6 mt-auto">
            <button class="w-full bg-pupr-yellow hover:bg-yellow-500 text-white font-bold py-3.5 rounded-xl shadow-md transition-all flex items-center justify-center mb-8">
                <i class="fas fa-map-marked-alt mr-2"></i> Peta Bidang
            </button>

            <div class="space-y-4 px-2">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center text-red-500 hover:text-red-700 font-medium transition w-full text-left">
                        <i class="fas fa-sign-out-alt w-6 text-center mr-3 text-lg"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 ml-64 min-h-screen flex flex-col">

        <header class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
            <div class="relative w-96">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-search"></i></span>
                <input type="text" placeholder="Cari tugas atau pekerja..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-pupr-blue transition">
            </div>

            <div class="flex items-center space-x-5">
                <div class="flex items-center space-x-3 cursor-pointer border-l pl-5">
                    <div class="w-10 h-10 rounded-full bg-pupr-blue text-white flex items-center justify-center font-bold shadow-md">
                        {{ substr(Auth::user()->nama_lengkap ?? 'A', 0, 1) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-bold text-gray-800 leading-tight">{{ Auth::user()->nama_lengkap ?? 'Admin Bidang' }}</p>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">{{ Auth::user()->bidang->nama_bidang ?? 'Kepala Unit' }}</p>
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
