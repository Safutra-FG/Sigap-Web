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
                            blue: '#1E3A8A',   /* Warna Biru Logo PUPR Resmi */
                            yellow: '#FACC15', /* Warna Kuning Logo PUPR Resmi */
                        }
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        .input-focus:focus {
            border-color: #1E3A8A;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="flex flex-col md:flex-row w-full max-w-5xl bg-white shadow-2xl rounded-2xl overflow-hidden min-h-[600px]">

        <div class="hidden md:flex md:w-1/2 bg-pupr-blue relative flex-col items-center justify-center p-12">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>

            <div class="relative z-10 w-full flex flex-col items-center">
                <img src="{{ asset('gambar/puprlogin.png') }}" alt="Ilustrasi Login" class="w-full max-w-md drop-shadow-2xl mb-10 hover:scale-105 transition-transform duration-500">

                <div class="text-center">
                    <h2 class="text-white text-3xl font-extrabold mb-3 tracking-tight">SIGAP PUPR</h2>
                    <p class="text-blue-100 text-sm font-medium leading-relaxed opacity-90">
                        Sistem Informasi Geografis dan Pelayanan <br> Dinas Pekerjaan Umum dan Penataan Ruang <br> Kabupaten Subang
                    </p>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 w-full h-3 bg-pupr-yellow"></div>
        </div>

        <div class="w-full md:w-1/2 flex flex-col justify-center p-8 md:p-14 bg-white">
            <div class="mb-10">
                <img src="{{ asset('gambar/puprlogo.png') }}" alt="Logo PUPR" class="w-24 mb-6">

                <h1 class="text-3xl font-extrabold text-pupr-blue mb-2">Selamat Datang</h1>
                <p class="text-gray-500 text-sm">Silakan masuk untuk mengakses dasbor anda.</p>
            </div>

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg text-sm flex items-center shadow-sm">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('login.proses') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Nama Pengguna</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="username" placeholder="Masukkan nama pengguna"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium transition-all input-focus outline-none"
                            required autofocus>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="inputKataSandi" name="password" placeholder="••••••••"
                            class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium transition-all input-focus outline-none"
                            required>
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-pupr-blue transition-colors focus:outline-none">
                            <i class="fas fa-eye" id="ikonMata"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between py-2">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300 text-pupr-blue focus:ring-pupr-blue h-4 w-4">
                        <span class="ml-2 text-xs font-semibold text-gray-600 select-none">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3.5 bg-pupr-blue text-white rounded-lg font-bold text-sm shadow-lg hover:bg-opacity-95 hover:shadow-xl active:scale-[0.99] transition-all flex items-center justify-center group mt-4">
                    <span>MASUK KE SISTEM</span>
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>

            <footer class="mt-12 pt-6 border-t border-gray-100">
                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-tighter text-center">
                    &copy; 2026 DINAS PUPR KABUPATEN SUBANG
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
