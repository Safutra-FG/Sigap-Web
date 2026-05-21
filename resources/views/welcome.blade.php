<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIGAP PUPR Subang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pupr: {
                            blue: '#1E3A8A',
                            yellow: '#FACC15',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
        }

        .input-focus:focus-within {
            border-color: #1E3A8A;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        /* Gambar latar kantor PUPR lokal */
        .bg-infrastruktur {
            background-image: url('{{ asset("gambar/kantor.jpg") }}');
            background-size: cover;
            background-position: center;
        }

        /* Animasi CSS Murni agar pasti berjalan */
        .animasi-masuk {
            animation: fadeInMasuk 0.8s ease-out forwards;
            opacity: 0; /* Mulai dari transparan */
        }

        @keyframes fadeInMasuk {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="flex flex-col md:flex-row w-full max-w-5xl bg-white shadow-2xl rounded-2xl overflow-hidden min-h-[580px] animasi-masuk">

        <div class="hidden md:flex md:w-7/12 bg-infrastruktur relative flex-col justify-between p-12 overflow-hidden">

            <div class="absolute inset-0 bg-[#1E3A8A]/85 transition-colors duration-700"></div>

            <div class="relative z-10 w-full mt-8">

                <div class="inline-flex items-center space-x-2 border border-white/30 rounded-full px-4 py-1.5 mb-8 w-max">
                    <i class="fas fa-shield-check text-blue-200 text-[11px]"></i>
                    <span class="text-blue-100 text-[9px] font-bold tracking-widest uppercase">Sistem Informasi & Gerbang Administrasi</span>
                </div>

                <h2 class="text-white text-4xl lg:text-5xl font-extrabold mb-6 leading-tight tracking-tight">
                    Transformasi Digital<br>Infrastruktur Negeri
                </h2>

                <p class="text-blue-100 text-sm font-medium leading-relaxed opacity-90 max-w-md">
                    Akses portal manajemen terpadu bagi Admin Universal dan Bidang untuk monitoring, pelaporan, dan koordinasi proyek infrastruktur Kabupaten Subang.
                </p>
            </div>

            <div class="relative z-10 flex space-x-10 mt-auto pt-8 border-t border-white/20">
                <div>
                    <p class="text-white font-extrabold text-2xl">24/7</p>
                    <p class="text-[9px] text-blue-200 font-bold uppercase tracking-widest mt-1">Monitoring</p>
                </div>
                <div>
                    <p class="text-white font-extrabold text-2xl">Aman</p>
                    <p class="text-[9px] text-blue-200 font-bold uppercase tracking-widest mt-1">Terenkripsi</p>
                </div>
            </div>
        </div>

        <div class="w-full md:w-5/12 flex flex-col justify-center p-8 lg:p-12 bg-white">

            <div class="mb-8">
                <h1 class="text-2xl font-extrabold text-gray-800 mb-1.5">Portal Admin Masuk</h1>
                <p class="text-gray-500 text-[11px] font-medium">Silakan masuk menggunakan kredensial departemen Anda.</p>
            </div>

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 mb-6 rounded-r-lg text-xs font-bold flex items-center shadow-sm animate-pulse">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('login.proses') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-2">Nama Pengguna</label>
                    <div class="relative flex items-center bg-blue-50/50 border border-gray-200 rounded-lg overflow-hidden input-focus transition-all">
                        <span class="pl-4 text-gray-400">
                            <i class="far fa-id-badge text-sm"></i>
                        </span>
                        <input type="text" name="username" placeholder="Masukkan Nama Pengguna Anda"
                            class="w-full pl-3 pr-4 py-3 bg-transparent text-xs font-semibold text-gray-800 outline-none"
                            required autofocus>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider">Kata Sandi</label>
                        </div>
                    <div class="relative flex items-center bg-blue-50/50 border border-gray-200 rounded-lg overflow-hidden input-focus transition-all">
                        <span class="pl-4 text-gray-400">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input type="password" id="inputKataSandi" name="password" placeholder="••••••••"
                            class="w-full pl-3 pr-12 py-3 bg-transparent text-xs font-semibold text-gray-800 outline-none"
                            required>
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-pupr-blue transition-colors focus:outline-none">
                            <i class="far fa-eye" id="ikonMata"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center pt-1">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" class="rounded border-gray-300 text-pupr-yellow focus:ring-pupr-yellow h-3.5 w-3.5 cursor-pointer">
                        <span class="ml-2 text-[10px] font-semibold text-gray-500 group-hover:text-gray-700 transition select-none">Ingat perangkat ini untuk 30 hari</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3.5 bg-pupr-yellow text-gray-900 rounded-lg font-extrabold text-xs shadow-md hover:bg-yellow-500 hover:shadow-lg active:scale-[0.99] transition-all flex items-center justify-center group mt-2 border border-yellow-500">
                    <span>Masuk Ke Dashboard</span>
                    <i class="fas fa-sign-in-alt ml-2 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>

            <footer class="mt-12 pt-6 border-t border-gray-100">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter text-center">
                    &copy; 2026 SISTEM INFORMASI SIGAP • PUPR SUBANG
                </p>
            </footer>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('inputKataSandi');
            const eyeIcon = document.getElementById('ikonMata');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</html>
