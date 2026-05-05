<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP PUPR - Portal Pekerja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { pupr: { blue: '#1E3A8A', yellow: '#FACC15' } } } }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navbar Atas Simple -->
    <header class="bg-pupr-blue shadow-md sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('gambar/puprlogo.png') }}" alt="Logo PUPR" class="w-10 bg-white rounded p-1">
                <div class="text-white">
                    <h1 class="font-bold text-lg leading-tight">Portal Pekerja</h1>
                    <p class="text-[10px] text-blue-200 uppercase tracking-widest">SIGAP Kab. Subang</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-white text-sm font-semibold hidden sm:block">Halo, {{ Auth::user()->nama_lengkap }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full text-white flex items-center justify-center transition" title="Keluar">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto p-4 md:p-6 mt-4">
        @yield('konten')
    </main>

</body>
</html>
