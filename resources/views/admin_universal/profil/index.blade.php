@extends('layouts.app')

@section('konten')
<div class="max-w-7xl mx-auto pb-10 relative">

    <!-- Header Halaman (Sama seperti sebelumnya) -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <a href="{{ route('admin_universal.beranda') }}" class="text-sm font-bold text-pupr-blue hover:underline mb-2 inline-block"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard</a>
            <h2 class="text-2xl font-extrabold text-gray-900">Detail Profil Pengguna</h2>
            <p class="text-sm text-gray-500">Informasi identitas dan riwayat aktivitas sistem.</p>
        </div>
    </div>

    <!-- Layout Grid Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- KOLOM KIRI (Profil & Kontak) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Kartu Identitas Utama -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden text-center">
                <div class="h-28 bg-[#1E3A8A]"></div>

                <!-- Foto Profil (Bisa Diklik untuk Upload) -->
                <div class="relative -mt-14 mb-3 flex justify-center">
                    <form action="{{ route('admin_universal.profil.foto') }}" method="POST" enctype="multipart/form-data" id="form-foto">
                        @csrf
                        <label for="foto_profil" class="cursor-pointer group relative block">
                            <div class="w-28 h-28 bg-white rounded-full p-1 shadow-md border border-gray-100 relative overflow-hidden">
                                @if($user->foto_profil)
                                    <img src="{{ asset('storage/' . $user->foto_profil) }}" class="w-full h-full object-cover rounded-full" alt="Foto Profil">
                                @else
                                    <div class="w-full h-full rounded-full bg-gray-200 flex items-center justify-center text-4xl text-gray-500 font-bold">
                                        {{ substr($user->nama_lengkap ?? 'A', 0, 1) }}
                                    </div>
                                @endif
                                <!-- Overlay Hover Kamera -->
                                <div class="absolute inset-1 bg-black/50 rounded-full flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                    <i class="fas fa-camera text-white text-xl mb-1"></i>
                                    <span class="text-[9px] text-white font-bold tracking-wider">UBAH</span>
                                </div>
                            </div>
                            <input type="file" name="foto_profil" id="foto_profil" class="hidden" accept="image/*" onchange="document.getElementById('form-foto').submit();">
                        </label>
                    </form>
                </div>

                <div class="px-6 pb-6">
                    <h3 class="text-xl font-extrabold text-gray-900">{{ $user->nama_lengkap ?? 'Admin Utama' }}</h3>
                    <p class="text-sm font-bold text-pupr-blue mt-1">{{ str_replace('_', ' ', strtoupper($user->peran ?? 'ADMINISTRATOR')) }}</p>

                    <div class="flex justify-center gap-2 mt-4 mb-6">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold tracking-wider">AKTIF</span>
                        <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-full text-[10px] font-bold tracking-wider">SISTEM ADMIN</span>
                    </div>
                </div>
            </div>

            <!-- Kartu Kontak & Lokasi (Data Nyata Database) -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h4 class="font-bold text-gray-800 mb-5">Kontak & Lokasi</h4>
                <div class="space-y-5">
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-pupr-blue flex items-center justify-center mr-4 shrink-0"><i class="fas fa-envelope"></i></div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Email Sistem</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $user->email ?? 'Belum diatur' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-pupr-blue flex items-center justify-center mr-4 shrink-0"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nomor Telepon</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $user->no_telp ?? 'Belum diatur' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-pupr-blue flex items-center justify-center mr-4 shrink-0"><i class="fas fa-building"></i></div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Kantor Wilayah</p>
                            <p class="text-sm font-semibold text-gray-800">Pusat Komando SIGAP Subang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN (Informasi & Aktivitas) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Informasi Kepegawaian (NIP Diganti ID Sistem) -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 lg:p-8">
                <div class="flex justify-between items-start mb-6">
                    <h4 class="text-lg font-bold text-gray-800">Informasi Otoritas</h4>
                    <i class="fas fa-shield-check text-green-500 text-xl" title="Terverifikasi"></i>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">ID Pengguna Sistem</p>
                        <p class="text-base font-semibold text-gray-800 font-mono bg-gray-50 px-2 py-1 rounded inline-block border border-gray-100">SGP-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Peran Aplikasi</p>
                        <p class="text-base font-semibold text-gray-800 capitalize">{{ str_replace('_', ' ', $user->peran ?? 'Admin Universal') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal Pendaftaran Akun</p>
                        <p class="text-base font-semibold text-gray-800">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Tabel Aktivitas Terakhir Nyata -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 lg:p-8">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-lg font-bold text-gray-800">Aktivitas Terakhir Anda</h4>
                    <button onclick="bukaModalLog()" class="text-xs font-bold text-pupr-blue hover:underline">Lihat Semua Riwayat</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @forelse($aktivitas_terbaru as $aktivitas)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 pr-4">
                                    <p class="font-bold text-gray-800">{{ $aktivitas->aktivitas }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Sistem Log #{{ $aktivitas->id }}</p>
                                </td>
                                <td class="py-4 text-gray-500 text-xs">
                                    <p>{{ $aktivitas->created_at->diffForHumans() }}</p>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-6 text-gray-400 text-xs">Belum ada aktivitas tercatat sejak login.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL POP-UP LIHAT SEMUA LOG -->
<!-- ========================================== -->
<div id="modal-log" class="fixed inset-0 z-[2000] hidden">
    <!-- Latar Belakang Gelap -->
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="tutupModalLog()"></div>

    <!-- Kotak Modal -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
        <!-- Header Modal -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Seluruh Riwayat Aktivitas</h3>
                <p class="text-xs text-gray-500">Mencatat seluruh jejak rekam admin di dalam sistem.</p>
            </div>
            <button onclick="tutupModalLog()" class="text-gray-400 hover:text-red-500 transition text-xl">&times;</button>
        </div>

        <!-- Isi Log -->
        <div class="p-6 overflow-y-auto flex-1">
            <ul class="space-y-4 border-l-2 border-blue-100 ml-3">
                @forelse($semua_aktivitas as $log)
                <li class="relative pl-6">
                    <span class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-pupr-blue border-4 border-white shadow-sm"></span>
                    <p class="text-sm font-bold text-gray-800">{{ $log->aktivitas }}</p>
                    <p class="text-[10px] font-semibold text-gray-500 mt-1 uppercase tracking-wider">{{ $log->created_at->format('d M Y - H:i') }} WIB <span class="ml-2 text-blue-500">{{ $log->kategori }}</span></p>
                </li>
                @empty
                <p class="text-sm text-gray-500 ml-6">Riwayat aktivitas bersih.</p>
                @endforelse
            </ul>
        </div>

        <!-- Footer Modal & Tombol Hapus Database -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center">
            <p class="text-xs text-gray-500"><i class="fas fa-info-circle mr-1"></i> Data yang dihapus akan hilang dari database.</p>

            <!-- Form yang sudah diubah untuk memicu SweetAlert2 -->
            <form id="form-hapus-semua-log" action="{{ route('admin_universal.profil.log.hapus') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" onclick="konfirmasiHapusSemuaLog()" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white border border-red-100 hover:border-red-500 rounded-lg text-xs font-bold transition shadow-sm">
                    <i class="fas fa-trash-alt mr-1"></i> Hapus Seluruh Riwayat
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function bukaModalLog() { document.getElementById('modal-log').classList.remove('hidden'); }
    function tutupModalLog() { document.getElementById('modal-log').classList.add('hidden'); }
</script>

<script>
    // Fungsi untuk modal log (sudah ada sebelumnya, biarkan saja)
    function bukaModalLog() { document.getElementById('modal-log').classList.remove('hidden'); }
    function tutupModalLog() { document.getElementById('modal-log').classList.add('hidden'); }

    // TAMBAHKAN FUNGSI BARU INI DI BAWAHNYA:
    function konfirmasiHapusSemuaLog() {
        // Sembunyikan modal log utama sejenak agar pop-up tidak bertumpuk
        tutupModalLog();

        Swal.fire({
            title: 'Hapus Seluruh Riwayat?',
            text: "Peringatan: Apakah Anda yakin ingin MENGHAPUS SELURUH riwayat aktivitas ini dari database secara permanen?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Warna merah Tailwind
            cancelButtonColor: '#9ca3af',  // Warna abu-abu Tailwind
            confirmButtonText: 'Ya, Hapus Permanen!',
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
                // Jika diklik "Ya", otomatis kirim form untuk menghapus data
                document.getElementById('form-hapus-semua-log').submit();
            } else {
                // Jika diklik "Batal", buka kembali modal log riwayatnya
                bukaModalLog();
            }
        });
    }
</script>

@endsection
