@extends('layouts.app')

@section('konten')
<div class="max-w-5xl mx-auto pb-16 space-y-16">

    <!-- Header Halaman -->
    <div class="text-center pt-8 pb-4 border-b border-gray-200">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-3">Pusat Bantuan & Informasi</h1>
        <p class="text-gray-500 max-w-2xl mx-auto">Dokumentasi resmi penggunaan sistem, informasi pengembang, dan latar belakang Sistem Informasi Geospasial Pengaduan (SIGAP).</p>
    </div>

    <!-- ========================================= -->
    <!-- BAGIAN 1: PANDUAN ADMIN UNIVERSAL -->
    <!-- ========================================= -->
    <section id="panduan-admin" class="scroll-mt-24">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-blue-100 text-pupr-blue rounded-xl flex items-center justify-center text-xl mr-4"><i class="fas fa-book"></i></div>
            <h2 class="text-2xl font-bold text-gray-800">Panduan Admin Universal</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-gray-900 mb-2"><i class="fas fa-th-large text-pupr-blue mr-2"></i> Dashboard & Peta Wilayah</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Menu ini menampilkan ringkasan statistik seluruh laporan. Gunakan fitur "Buka Peta Wilayah" di sidebar kiri untuk memantau titik koordinat kerusakan infrastruktur di Kabupaten Subang secara langsung (Command Center).</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-gray-900 mb-2"><i class="fas fa-file-alt text-pupr-blue mr-2"></i> Kelola Laporan</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Sebagai Admin Universal, Anda bertugas memvalidasi laporan masuk dari masyarakat, mengubah status (Pending, Proses, Selesai), dan meneruskan (disposisi) laporan tersebut ke bidang terkait.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-gray-900 mb-2"><i class="fas fa-network-wired text-pupr-blue mr-2"></i> Kelola Bidang</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Gunakan menu ini untuk menambah, mengedit, atau menonaktifkan unit kerja/bidang (seperti Bina Marga, SDA, Cipta Karya). Bidang yang berstatus "Aktif" akan muncul sebagai opsi tujuan disposisi laporan.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-gray-900 mb-2"><i class="fas fa-users text-pupr-blue mr-2"></i> Kelola Pengguna & Profil</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Anda memiliki wewenang untuk menambah akun admin bidang atau pekerja baru. Jangan lupa untuk memperbarui foto profil dan memantau riwayat log aktivitas Anda di menu Profil Saya.</p>
            </div>
        </div>
    </section>

    <!-- ========================================= -->
    <!-- BAGIAN 2: HUBUNGI TIM IT (PENGEMBANG) -->
    <!-- ========================================= -->
    <section id="tim-it" class="scroll-mt-24">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl mr-4"><i class="fas fa-headset"></i></div>
            <h2 class="text-2xl font-bold text-gray-800">Hubungi Tim IT</h2>
        </div>

        <p class="text-sm text-gray-600 mb-6">Sistem ini dirancang dan dikembangkan oleh mahasiswa TRPL Politeknik Negeri Subang (POLSUB). Jika Anda mengalami kendala teknis atau menemukan <i>bug</i>, silakan hubungi tim pengembang di bawah ini:</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <!-- Pengembang 1 -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:border-pupr-blue transition group">
                <div class="w-20 h-20 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl text-gray-400 group-hover:bg-blue-50 group-hover:text-pupr-blue transition">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="font-extrabold text-gray-900 text-lg">Muhammad Adrian Taofik</h3>
                <p class="text-xs font-bold text-pupr-blue mt-1 mb-3 uppercase tracking-widest">NIM: [10602033]</p>
                <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-3 py-1 rounded-full">Full Stack & UI/UX Desain</span>
            </div>

            <!-- Pengembang 2 -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:border-pupr-blue transition group">
                <div class="w-20 h-20 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl text-gray-400 group-hover:bg-blue-50 group-hover:text-pupr-blue transition">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="font-extrabold text-gray-900 text-lg">Safutra Fadli Guminar</h3>
                <p class="text-xs font-bold text-pupr-blue mt-1 mb-3 uppercase tracking-widest">NIM: [10602049]</p>
                <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-3 py-1 rounded-full">Project Manager & Full Stack</span>
            </div>

            <!-- Pengembang 3 -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:border-pupr-blue transition group">
                <div class="w-20 h-20 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl text-gray-400 group-hover:bg-blue-50 group-hover:text-pupr-blue transition">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="font-extrabold text-gray-900 text-lg">Zahra Arifiani</h3>
                <p class="text-xs font-bold text-pupr-blue mt-1 mb-3 uppercase tracking-widest">NIM: [10602063]</p>
                <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-3 py-1 rounded-full">System Analist & UI/UX Desain</span>
            </div>
        </div>
    </section>

    <!-- ========================================= -->
    <!-- BAGIAN 3: TENTANG SIGAP -->
    <!-- ========================================= -->
    <section id="tentang-sigap" class="scroll-mt-24">
        <div class="bg-[#1E3A8A] rounded-3xl p-8 md:p-12 text-white relative overflow-hidden shadow-xl">
            <!-- Ornamen Dekorasi -->
            <i class="fas fa-map-marked-alt absolute -right-10 -bottom-10 text-[200px] text-white opacity-10"></i>

            <div class="relative z-10">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-white text-pupr-blue rounded-xl flex items-center justify-center text-xl mr-4"><i class="fas fa-info"></i></div>
                    <h2 class="text-2xl font-bold">Tentang SIGAP</h2>
                </div>

                <h3 class="text-xl font-bold mb-4 text-pupr-yellow">Sistem Informasi Geospasial Pengaduan (SIGAP)</h3>

                <div class="space-y-4 text-blue-100 text-sm leading-relaxed max-w-3xl">
                    <p>
                        SIGAP adalah sebuah platform digital inovatif yang dirancang khusus untuk Dinas Pekerjaan Umum dan Penataan Ruang (PUPR) Kabupaten Subang. Sistem ini mengintegrasikan teknologi pelaporan berbasis partisipasi masyarakat dengan pemetaan spasial (Geographic Information System / GIS).
                    </p>
                    <p>
                        Tujuan utama dari SIGAP adalah mempercepat respons pemerintah terhadap kerusakan infrastruktur seperti jalan berlubang, jembatan rusak, drainase mampet, dan fasilitas tata ruang lainnya. Dengan mengandalkan titik koordinat GPS dari laporan warga, tim PUPR dapat melacak posisi pasti kerusakan, mendisposisikan tugas ke unit kerja (UPTD) terkait secara akurat, dan memantau progres perbaikan dari satu dasbor pusat komando terpadu.
                    </p>
                    <p class="font-bold text-white pt-4">
                        Dikembangkan Tahun 2026 &copy; Politeknik Negeri Subang (POLSUB) - TRPL Kelas 2A.
                    </p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
