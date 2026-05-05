@extends('layouts.app')

<!-- CSS Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

@section('konten')
<div class="max-w-7xl mx-auto pb-10">

    <!-- BREADCRUMB & HEADER -->
    <div class="mb-6">
        <!-- Breadcrumb -->
        <div class="text-xs text-gray-500 mb-3 font-medium">
            <a href="{{ route('admin_universal.laporan') }}" class="hover:text-pupr-blue transition">Laporan</a>
            <span class="mx-1">&rsaquo;</span>
            <span class="text-gray-700 font-bold">Detail Laporan</span>
        </div>

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

            <!-- KIRI: Tombol Kembali, ID Laporan & Badge Status -->
            <div class="flex items-center space-x-3 md:space-x-4">
                <!-- Tombol Kembali (BARU) -->
                <a href="{{ route('admin_universal.laporan') }}" class="w-10 h-10 bg-white border border-gray-200 text-gray-500 hover:text-pupr-blue hover:border-pupr-blue hover:bg-blue-50 rounded-xl flex items-center justify-center transition shadow-sm focus:outline-none" title="Kembali ke Daftar Laporan">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>

                <!-- ID Laporan -->
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ $laporan->id_laporan ?? 'REP-20260428-009' }}</h2>

                <!-- Badge Status -->
                <span class="bg-yellow-400 text-white text-[10px] font-extrabold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm">
                    {{ $laporan->status ?? 'Proses' }}
                </span>
            </div>

            <!-- TOMBOL AKSI STATUS (Dengan Logika State Machine) -->
            <div class="flex flex-wrap gap-3">

                @php
                    $statusSaatIni = strtolower($laporan->status ?? 'pending');
                @endphp

                {{-- KONDISI 1: JIKA LAPORAN BARU MASUK (PENDING) --}}
                @if($statusSaatIni == 'pending')
                    <!-- Form Tolak -->
                    <form id="formTolak" action="{{ route('admin_universal.laporan.update_status', $laporan->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="ditolak">
                        <input type="hidden" name="alasan_penolakan" id="inputAlasanTolak">
                        <button type="button" onclick="konfirmasiTolak('formTolak')" class="border-2 border-red-500 text-red-500 hover:bg-red-50 px-4 py-2 rounded-lg text-sm font-bold transition flex items-center shadow-sm bg-white">
                            <i class="fas fa-times mr-2"></i> Tolak
                        </button>
                    </form>

                    <!-- Form Disposisi -->
                    <form id="formProses" action="{{ route('admin_universal.laporan.update_status', $laporan->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="diteruskan">
                        <input type="hidden" name="bidang_tujuan" id="inputBidangDisposisi">

                        <!-- TAMBAHAN: Input rahasia untuk menampung Catatan -->
                        <input type="hidden" name="catatan_disposisi" id="inputCatatanDisposisi">

                        <button type="button" onclick="konfirmasiDisposisi('formProses')" class="border-2 border-pupr-blue text-pupr-blue hover:bg-blue-50 px-4 py-2 rounded-lg text-sm font-bold transition flex items-center shadow-sm bg-white">
                            <i class="fas fa-share-square mr-2"></i> Disposisi
                        </button>
                    </form>

                    <!-- Form Selesai Langsung (Opsional jika bisa langsung diselesaikan) -->
                    <form id="formSelesai" action="{{ route('admin_universal.laporan.update_status', $laporan->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="selesai">
                        <button type="button" onclick="konfirmasiAksi('formSelesai', 'Selesaikan Laporan?', 'Tandai laporan ini sebagai SELESAI?', 'success', 'Ya, Selesai!', '#eab308')" class="bg-yellow-400 hover:bg-yellow-500 text-white px-5 py-2 rounded-lg text-sm font-bold transition flex items-center shadow-md">
                            <i class="fas fa-check-circle mr-2"></i> Selesai
                        </button>
                    </form>

                {{-- KONDISI 2: JIKA LAPORAN SEDANG DIPROSES / DITERUSKAN --}}
                @elseif($statusSaatIni == 'diteruskan' || $statusSaatIni == 'proses')
                    <!-- Form Batal Disposisi (Kembali ke Pending) -->
                    <form id="formBatalDisposisi" action="{{ route('admin_universal.laporan.update_status', $laporan->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="pending">
                        <button type="button" onclick="konfirmasiBatal('formBatalDisposisi', 'Batalkan Disposisi?')" class="border-2 border-gray-400 text-gray-500 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-bold transition flex items-center shadow-sm bg-white">
                            <i class="fas fa-undo mr-2"></i> Batalkan Disposisi
                        </button>
                    </form>

                    <!-- Form Selesai -->
                    <form id="formSelesai" action="{{ route('admin_universal.laporan.update_status', $laporan->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="selesai">
                        <button type="button" onclick="konfirmasiAksi('formSelesai', 'Selesaikan Laporan?', 'Pengerjaan lapangan sudah beres? Tandai sebagai SELESAI sekarang.', 'success', 'Ya, Selesai!', '#eab308')" class="bg-yellow-400 hover:bg-yellow-500 text-white px-5 py-2 rounded-lg text-sm font-bold transition flex items-center shadow-md">
                            <i class="fas fa-check-circle mr-2"></i> Selesai
                        </button>
                    </form>

                {{-- KONDISI 3: JIKA LAPORAN DITOLAK --}}
                @elseif($statusSaatIni == 'ditolak')
                    <!-- Form Batal Tolak (Kembali ke Pending) -->
                    <form id="formBatalTolak" action="{{ route('admin_universal.laporan.update_status', $laporan->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="pending">
                        <button type="button" onclick="konfirmasiBatal('formBatalTolak', 'Batalkan Penolakan?')" class="border-2 border-gray-400 text-gray-500 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-bold transition flex items-center shadow-sm bg-white">
                            <i class="fas fa-undo mr-2"></i> Batalkan Penolakan
                        </button>
                    </form>

                {{-- KONDISI 4: JIKA LAPORAN SUDAH SELESAI --}}
                @elseif($statusSaatIni == 'selesai')
                    <!-- Form Batal Selesai (Mundur ke Proses) -->
                    <form id="formBatalSelesai" action="{{ route('admin_universal.laporan.update_status', $laporan->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="diteruskan">
                        <button type="button" onclick="konfirmasiBatal('formBatalSelesai', 'Batalkan Status Selesai?')" class="border-2 border-gray-400 text-gray-500 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-bold transition flex items-center shadow-sm bg-white">
                            <i class="fas fa-undo mr-2"></i> Batalkan Selesai
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>

    <!-- MAIN GRID LAYOUT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI (Peta & Foto) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- KARTU PETA LOKASI -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-white relative">
                    <h3 class="font-bold text-gray-800 flex items-center text-sm">
                        <i class="fas fa-map-marker-alt text-pupr-blue mr-2 text-lg"></i> Lokasi Laporan
                    </h3>

                    <!-- Area Tombol Aksi Peta -->
                    <div class="flex space-x-2 text-gray-400">
                        <!-- Tombol Layar Penuh -->
                        <button id="btn-fs-detail" onclick="toggleFullscreenDetail()" class="hover:text-pupr-blue border border-gray-200 hover:bg-blue-50 rounded p-1.5 transition focus:outline-none" title="Layar Penuh">
                            <i class="fas fa-expand text-xs" id="icon-fs-detail"></i>
                        </button>

                        <!-- Tombol Ganti Layer & Dropdown -->
                        <div class="relative">
                            <button id="btn-layer-detail" onclick="toggleLayerMenuDetail()" class="hover:text-pupr-blue border border-gray-200 hover:bg-blue-50 rounded p-1.5 transition focus:outline-none" title="Ganti Tampilan Peta">
                                <i class="fas fa-layer-group text-xs"></i>
                            </button>
                            <!-- Menu Dropdown -->
                            <div id="menu-layer-detail" class="hidden absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-xl border border-gray-100 z-[2000] p-2 origin-top-right">
                                <button onclick="gantiLayerDetail('standar')" class="w-full text-left px-3 py-2 text-[11px] font-bold text-gray-600 hover:bg-blue-50 hover:text-pupr-blue rounded-lg transition">
                                    <i class="fas fa-map mr-2"></i> Peta Jalan
                                </button>
                                <button onclick="gantiLayerDetail('satelit')" class="w-full text-left px-3 py-2 text-[11px] font-bold text-gray-600 hover:bg-blue-50 hover:text-pupr-blue rounded-lg transition">
                                    <i class="fas fa-satellite mr-2"></i> Citra Satelit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative w-full h-[400px] bg-gray-100 z-0" id="mapDetail">
                    <!-- Overlay Koordinat (Dipindah ke Kanan Atas & Ditambah Tombol Salin) -->
                    <div class="absolute top-4 right-4 bg-white/95 backdrop-blur rounded-lg p-3 shadow-lg border border-gray-200 z-[1000] flex items-center space-x-4">
                        <div>
                            <p class="text-[9px] font-extrabold text-pupr-blue uppercase tracking-widest mb-0.5">Titik Koordinat</p>
                            <p class="text-sm font-mono font-bold text-gray-800" id="teks-koordinat">{{ $laporan->lokasi_gps ?? '-6.2146, 106.8451' }}</p>
                        </div>

                        <!-- Tombol Salin Interaktif -->
                        <button onclick="salinKoordinat()" class="w-9 h-9 rounded bg-gray-50 border border-gray-200 text-gray-500 hover:bg-blue-50 hover:text-pupr-blue hover:border-blue-200 flex items-center justify-center transition focus:outline-none" title="Salin Koordinat ke Clipboard">
                            <i class="far fa-copy" id="icon-salin"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- KARTU MEDIA (FOTO/VIDEO LAPORAN) -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-white">
                    <h3 class="font-bold text-gray-800 flex items-center text-sm">
                        <i class="fas fa-images text-pupr-blue mr-2 text-lg"></i> Bukti Foto & Video
                    </h3>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="galleryContainer">

                        <!-- Item 1 -->
                        <div onclick="openLightbox('https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&q=80&w=1200', 'image')" class="aspect-square rounded-xl overflow-hidden bg-gray-100 border border-gray-200 shadow-sm cursor-pointer relative group">
                            <img src="https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&q=80&w=400" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transform scale-50 group-hover:scale-100 transition-all drop-shadow-lg"></i>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div onclick="openLightbox('https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?auto=format&fit=crop&q=80&w=1200', 'image')" class="aspect-square rounded-xl overflow-hidden bg-gray-100 border border-gray-200 shadow-sm cursor-pointer relative group">
                            <img src="https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?auto=format&fit=crop&q=80&w=400" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transform scale-50 group-hover:scale-100 transition-all drop-shadow-lg"></i>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div onclick="openLightbox('https://images.unsplash.com/photo-1518991043280-1da61d9f3ac4?auto=format&fit=crop&q=80&w=1200', 'image')" class="aspect-square rounded-xl overflow-hidden bg-gray-100 border border-gray-200 shadow-sm cursor-pointer relative group">
                            <img src="https://images.unsplash.com/photo-1518991043280-1da61d9f3ac4?auto=format&fit=crop&q=80&w=400" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transform scale-50 group-hover:scale-100 transition-all drop-shadow-lg"></i>
                            </div>
                        </div>

                        <!-- Tombol Expand (Hanya muncul jika media > 3) -->
                        <button id="btnExpandGallery" onclick="expandGallery()" class="aspect-square rounded-xl border border-gray-200 bg-gray-50 overflow-hidden relative group cursor-pointer focus:outline-none shadow-sm">
                            <!-- Background Blur dari foto selanjutnya -->
                            <div class="absolute inset-0 bg-cover bg-center opacity-30 group-hover:opacity-20 transition-opacity" style="background-image: url('https://images.unsplash.com/photo-1565516972036-f001e4ecb7da?auto=format&fit=crop&q=80&w=400'); filter: blur(2px);"></div>
                            <div class="absolute inset-0 bg-pupr-blue/10 group-hover:bg-pupr-blue/20 transition-colors"></div>

                            <!-- Konten Tombol -->
                            <div class="relative z-10 w-full h-full flex flex-col items-center justify-center text-gray-700 group-hover:text-pupr-blue transition-colors">
                                <i class="fas fa-images text-2xl mb-1"></i>
                                <span class="text-xs font-extrabold tracking-wide">+2 Media Lain</span>
                            </div>
                        </button>

                        <!-- ITEM TERSEMBUNYI (Muncul ke bawah setelah di-expand) -->
                        <!-- Item 4 -->
                        <div onclick="openLightbox('https://images.unsplash.com/photo-1565516972036-f001e4ecb7da?auto=format&fit=crop&q=80&w=1200', 'image')" class="gallery-hidden-item hidden aspect-square rounded-xl overflow-hidden bg-gray-100 border border-gray-200 shadow-sm cursor-pointer relative group">
                            <img src="https://images.unsplash.com/photo-1565516972036-f001e4ecb7da?auto=format&fit=crop&q=80&w=400" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transform scale-50 group-hover:scale-100 transition-all drop-shadow-lg"></i>
                            </div>
                        </div>

                        <!-- Item 5 -->
                        <div onclick="openLightbox('https://images.unsplash.com/photo-1596484552834-6a58f850e0a1?auto=format&fit=crop&q=80&w=1200', 'image')" class="gallery-hidden-item hidden aspect-square rounded-xl overflow-hidden bg-gray-100 border border-gray-200 shadow-sm cursor-pointer relative group">
                            <img src="https://images.unsplash.com/photo-1596484552834-6a58f850e0a1?auto=format&fit=crop&q=80&w=400" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transform scale-50 group-hover:scale-100 transition-all drop-shadow-lg"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN (Informasi & Riwayat) -->
        <div class="lg:col-span-1 space-y-6">

            <!-- KARTU INFORMASI LAPORAN -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <!-- Header Card Biru PUPR -->
                <div class="bg-[#102A63] p-5 text-white relative overflow-hidden">
                    <h3 class="font-bold text-lg mb-1 relative z-10">Informasi Laporan</h3>
                    <p class="text-[9px] uppercase tracking-widest text-blue-200 font-bold relative z-10">Status Verifikasi: Terverifikasi</p>
                    <!-- Ornamen background -->
                    <i class="fas fa-file-alt absolute -right-4 -bottom-4 text-6xl text-white opacity-10"></i>
                </div>

                <div class="p-6 space-y-5">
                    <!-- Kategori (Ikon menyesuaikan teks secara dinamis) -->
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kategori</p>
                        <p class="font-bold text-gray-800 flex items-center text-sm">
                            @if(str_contains(strtolower($laporan->kategori_bidang), 'marga'))
                                <i class="fas fa-road text-pupr-blue mr-2 text-lg"></i>
                            @elseif(str_contains(strtolower($laporan->kategori_bidang), 'sda'))
                                <i class="fas fa-water text-pupr-blue mr-2 text-lg"></i>
                            @else
                                <i class="fas fa-building text-pupr-blue mr-2 text-lg"></i>
                            @endif
                            {{ $laporan->kategori_bidang }}
                        </p>
                    </div>

                    <!-- Pelapor & Tanggal (Grid Bersebelahan) -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pelapor</p>
                            <p class="font-bold text-gray-800 text-sm">{{ $laporan->pelapor->nama_lengkap ?? 'Anonim' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal</p>
                            <!-- Perbaikan Format Tanggal -->
                            <p class="font-bold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Lokasi</p>
                        <p class="text-sm text-gray-600 leading-relaxed font-medium">
                            {{ $laporan->alamat_map }}
                        </p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Deskripsi</p>
                        <p class="text-sm text-gray-600 italic bg-gray-50 p-4 rounded-xl border border-gray-100">
                            "{{ $laporan->deskripsi_laporan }}"
                        </p>
                    </div>

                    <!-- Tombol Cetak (Sekarang menjadi tag <a> untuk membuka PDF) -->
                    <div class="pt-3">
                        <a href="{{ route('admin_universal.laporan.cetak_detail', $laporan->id) }}" target="_blank" class="w-full border-2 border-pupr-blue text-pupr-blue hover:bg-blue-50 py-2.5 rounded-lg text-sm font-bold transition flex items-center justify-center shadow-sm">
                            <i class="fas fa-print mr-2"></i> Cetak Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- KARTU RIWAYAT STATUS DINAMIS -->
            <div class="bg-[#F8FAFC] rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 flex items-center mb-6 text-sm">
                    <i class="fas fa-history text-pupr-blue mr-2 text-lg"></i> Riwayat Status
                </h3>

                <div class="relative pl-6 border-l-2 border-gray-200 space-y-6">

                    {{-- 1. STATUS TERBARU (Hanya muncul jika statusnya sudah bukan 'pending') --}}
                    @if(isset($laporan) && strtolower($laporan->status) != 'pending')
                    <div class="relative">
                        @php
                            $statusLabel = strtoupper($laporan->status);

                            // Logika Warna & Teks Berdasarkan Status Database
                            if(in_array(strtolower($laporan->status), ['proses', 'diteruskan'])) {
                                $warnaTitik = 'bg-pupr-blue';
                                $warnaTeks = 'text-pupr-blue';
                                $teksBox = 'Laporan sedang ditindaklanjuti / diproses oleh tim teknis di lapangan.';
                            } elseif (strtolower($laporan->status) == 'selesai') {
                                $warnaTitik = 'bg-green-500';
                                $warnaTeks = 'text-green-600';
                                $teksBox = 'Pengerjaan telah selesai dan infrastruktur kembali normal.';
                            } elseif (strtolower($laporan->status) == 'ditolak') {
                                $warnaTitik = 'bg-red-500';
                                $warnaTeks = 'text-red-600';
                                $teksBox = 'Laporan ditolak karena data tidak valid atau merupakan duplikasi.';
                            } else {
                                $warnaTitik = 'bg-gray-500';
                                $warnaTeks = 'text-gray-700';
                                $teksBox = 'Status laporan telah diperbarui.';
                            }
                        @endphp

                        <!-- Titik Indikator Dinamis -->
                        <div class="absolute -left-[31px] top-1 w-4 h-4 rounded-full {{ $warnaTitik }} ring-4 ring-[#F8FAFC]"></div>
                        <p class="text-sm font-bold {{ $warnaTeks }} mb-0.5">{{ $statusLabel }}</p>

                        <!-- Waktu Diperbarui (Mengambil dari updated_at) -->
                        <p class="text-[10px] font-bold text-gray-400 mb-2">
                            {{ \Carbon\Carbon::parse($laporan->updated_at)->translatedFormat('d M Y, H:i') }} WIB
                        </p>

                        <!-- Kotak Keterangan -->
                        <div class="bg-white p-3 rounded-lg border border-gray-200 text-xs text-gray-600 shadow-sm font-medium">
                            {{ $teksBox }}
                        </div>
                    </div>
                    @endif

                    {{-- 2. STATUS AWAL (Selalu Muncul di Bawah) --}}
                    <div class="relative">
                        <!-- Titik Hijau -->
                        <div class="absolute -left-[31px] top-1 w-4 h-4 rounded-full bg-green-500 ring-4 ring-[#F8FAFC]"></div>
                        <p class="text-sm font-bold text-gray-800 mb-0.5">Laporan Masuk</p>

                        <!-- Waktu Dibuat (Mengambil dari created_at) -->
                        <p class="text-[10px] font-bold text-gray-400">
                            {{ isset($laporan) ? \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d M Y, H:i') : '24 Okt 2023, 08:30' }} WIB
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>



<!-- SCRIPT PETA LEAFLET KHUSUS DETAIL -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    // Variabel Global untuk Peta
    let mapDetail, layerStandarDetail, layerSatelitDetail;

    document.addEventListener("DOMContentLoaded", function() {
        let koorString = "{{ $laporan->lokasi_gps ?? '-6.2146, 106.8451' }}";
        let koorArr = koorString.split(',');
        let lat = parseFloat(koorArr[0]);
        let lng = parseFloat(koorArr[1]);

        // 1. Inisialisasi Peta
        mapDetail = L.map('mapDetail').setView([lat, lng], 16); // Zoom 16 lebih detail

        // 2. Siapkan 2 Jenis Peta (Layer)
        layerStandarDetail = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { maxZoom: 20 });
        layerSatelitDetail = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 });

        // Pasang layer standar sebagai default awal
        layerStandarDetail.addTo(mapDetail);

        // 3. Buat Pin Merah
        let redPinIcon = L.divIcon({
            className: 'bg-transparent',
            html: `<div style="text-shadow: 3px 5px 6px rgba(0,0,0,0.4);" class="text-red-500 text-[50px] hover:scale-110 transition-transform">
                     <i class="fas fa-map-marker-alt"></i>
                   </div>`,
            iconSize: [40, 50],
            iconAnchor: [20, 50]
        });

        L.marker([lat, lng], {icon: redPinIcon}).addTo(mapDetail);
    });

    // ============================================
    // LOGIKA FUNGSI TOMBOL AKSI PETA
    // ============================================

    // 1. Fitur Buka/Tutup Menu Dropdown Layer
    function toggleLayerMenuDetail() {
        document.getElementById('menu-layer-detail').classList.toggle('hidden');
    }

    // 2. Fitur Ganti Tampilan Peta (Standar vs Satelit)
    function gantiLayerDetail(jenis) {
        if(jenis === 'standar') {
            mapDetail.removeLayer(layerSatelitDetail);
            layerStandarDetail.addTo(mapDetail);
        } else {
            mapDetail.removeLayer(layerStandarDetail);
            layerSatelitDetail.addTo(mapDetail);
        }
        // Sembunyikan menu setelah diklik
        document.getElementById('menu-layer-detail').classList.add('hidden');
    }

    // 3. Fitur Layar Penuh (Fullscreen)
    function toggleFullscreenDetail() {
        let elemenPeta = document.getElementById('mapDetail');
        if (!document.fullscreenElement) {
            // Masuk layar penuh
            elemenPeta.requestFullscreen().catch(err => { alert(`Gagal: ${err.message}`); });
        } else {
            // Keluar layar penuh
            document.exitFullscreen();
        }
    }

    // 4. Deteksi otomatis saat Peta masuk/keluar layar penuh
    document.addEventListener('fullscreenchange', (event) => {
        let iconBtn = document.getElementById('icon-fs-detail');
        let divPeta = document.getElementById('mapDetail');

        if (document.fullscreenElement) {
            // Saat Layar Penuh: Ubah ikon panah ke dalam, hapus tinggi 400px
            iconBtn.classList.remove('fa-expand');
            iconBtn.classList.add('fa-compress');
            divPeta.classList.remove('h-[400px]');
            divPeta.style.height = '100vh';
        } else {
            // Saat Normal: Kembalikan ikon panah keluar dan set tinggi 400px lagi
            iconBtn.classList.remove('fa-compress');
            iconBtn.classList.add('fa-expand');
            divPeta.style.height = '';
            divPeta.classList.add('h-[400px]');
        }

        // PENTING: Refresh ukuran peta agar tidak ada area abu-abu
        setTimeout(() => { mapDetail.invalidateSize(); }, 300);
    });

    // 5. Tutup otomatis dropdown layer jika admin mengklik di sembarang tempat
    document.addEventListener('click', function(event) {
        if(!event.target.closest('#btn-layer-detail') && !event.target.closest('#menu-layer-detail')) {
            let menu = document.getElementById('menu-layer-detail');
            if(menu) menu.classList.add('hidden');
        }
    });

    // 6. Fitur Salin Koordinat GPS (Langkah Sebelumnya)
    function salinKoordinat() {
        let koordinat = document.getElementById('teks-koordinat').innerText;
        navigator.clipboard.writeText(koordinat).then(() => {
            let iconSalin = document.getElementById('icon-salin');
            let tombolSalin = iconSalin.parentElement;
            iconSalin.classList.remove('far', 'fa-copy', 'text-gray-500');
            iconSalin.classList.add('fas', 'fa-check');
            tombolSalin.classList.add('bg-green-50', 'text-green-600', 'border-green-200');
            setTimeout(() => {
                iconSalin.classList.remove('fas', 'fa-check');
                iconSalin.classList.add('far', 'fa-copy', 'text-gray-500');
                tombolSalin.classList.remove('bg-green-50', 'text-green-600', 'border-green-200');
            }, 2000);
        });
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil koordinat dari controller atau gunakan default
        let koorString = "{{ $laporan->lokasi_gps ?? '-6.2146, 106.8451' }}";
        let koorArr = koorString.split(',');
        let lat = parseFloat(koorArr[0]);
        let lng = parseFloat(koorArr[1]);

        // Inisialisasi Peta (Zoom lebih dekat yaitu 15)
        let mapDetail = L.map('mapDetail').setView([lat, lng], 15);

        // Style peta Google Maps style (Voyager CartoDB)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 20,
            attribution: '&copy; OpenStreetMap'
        }).addTo(mapDetail);

        // Buat Ikon Pin Merah Besar ala Google Maps
        let redPinIcon = L.divIcon({
            className: 'bg-transparent',
            html: `<div style="text-shadow: 3px 5px 6px rgba(0,0,0,0.4);" class="text-red-500 text-[50px]">
                     <i class="fas fa-map-marker-alt"></i>
                   </div>`,
            iconSize: [40, 50],
            iconAnchor: [20, 50] // Anchor pas di ujung bawah pin
        });

        // Letakkan pin di peta
        L.marker([lat, lng], {icon: redPinIcon}).addTo(mapDetail);
    });
</script>

<!-- Script untuk Fitur Salin Koordinat -->
<script>
    function salinKoordinat() {
        // Mengambil teks dari elemen koordinat
        let koordinat = document.getElementById('teks-koordinat').innerText;

        // Memanfaatkan API Clipboard bawaan browser untuk menyalin
        navigator.clipboard.writeText(koordinat).then(() => {
            // Memberikan efek visual (feedback) ke tombol saat berhasil disalin
            let iconSalin = document.getElementById('icon-salin');
            let tombolSalin = iconSalin.parentElement;

            // Ubah ikon menjadi centang hijau
            iconSalin.classList.remove('far', 'fa-copy', 'text-gray-500');
            iconSalin.classList.add('fas', 'fa-check');
            tombolSalin.classList.add('bg-green-50', 'text-green-600', 'border-green-200');

            // Kembalikan ke wujud semula setelah 2 detik
            setTimeout(() => {
                iconSalin.classList.remove('fas', 'fa-check');
                iconSalin.classList.add('far', 'fa-copy', 'text-gray-500');
                tombolSalin.classList.remove('bg-green-50', 'text-green-600', 'border-green-200');
            }, 2000);

        }).catch(err => {
            console.error('Gagal menyalin: ', err);
            alert('Maaf, browser Anda tidak mendukung fitur salin otomatis.');
        });
    }
</script>

<!-- ============================================== -->
<!-- MODAL POP-UP LIGHTBOX UNTUK FOTO & VIDEO -->
<!-- ============================================== -->
<div id="lightboxModal" class="fixed inset-0 z-[4000] hidden flex items-center justify-center transition-opacity">
    <!-- Latar Belakang Gelap (Klik untuk menutup) -->
    <div class="absolute inset-0 bg-gray-900/90 backdrop-blur-sm cursor-zoom-out" onclick="closeLightbox()"></div>

    <!-- Tombol Tutup Silang -->
    <button onclick="closeLightbox()" class="absolute top-5 right-5 md:top-8 md:right-8 text-white hover:text-red-500 transition z-10 p-3 bg-black/20 rounded-full hover:bg-black/50 focus:outline-none">
        <i class="fas fa-times text-2xl drop-shadow-lg"></i>
    </button>

    <!-- Kontainer Media Pop-up -->
    <div class="relative z-10 max-w-5xl w-full max-h-[90vh] flex items-center justify-center p-4 pointer-events-none">
        <!-- Element Gambar -->
        <img id="lightboxImage" src="" class="hidden max-w-full max-h-[85vh] rounded-lg shadow-[0_0_40px_rgba(0,0,0,0.5)] pointer-events-auto border border-gray-700 transform scale-95 transition-transform duration-300">

        <!-- Element Video (Bisa Memutar Video jika tipe datanya Video) -->
        <video id="lightboxVideo" controls class="hidden max-w-full max-h-[85vh] rounded-lg shadow-[0_0_40px_rgba(0,0,0,0.5)] pointer-events-auto border border-gray-700"></video>
    </div>
</div>

<!-- Script untuk Lightbox & Expand Grid -->
<script>
    // Fungsi Menampilkan Media ke dalam Pop-up
    function openLightbox(mediaUrl, type) {
        const modal = document.getElementById('lightboxModal');
        const img = document.getElementById('lightboxImage');
        const vid = document.getElementById('lightboxVideo');

        // Tampilkan Modal
        modal.classList.remove('hidden');

        if(type === 'image') {
            img.src = mediaUrl;
            img.classList.remove('hidden');
            vid.classList.add('hidden');
            vid.pause();

            // Sedikit animasi zoom-in saat foto dibuka
            setTimeout(() => { img.classList.remove('scale-95'); img.classList.add('scale-100'); }, 10);
        } else if (type === 'video') {
            vid.src = mediaUrl;
            vid.classList.remove('hidden');
            img.classList.add('hidden');
            vid.play(); // Putar video otomatis
        }
    }

    // Fungsi Menutup Pop-up
    function closeLightbox() {
        const modal = document.getElementById('lightboxModal');
        const img = document.getElementById('lightboxImage');
        const vid = document.getElementById('lightboxVideo');

        modal.classList.add('hidden');
        img.classList.add('scale-95'); // Reset animasi
        img.classList.remove('scale-100');

        // Hapus source dan pause video agar memori browser tidak penuh
        setTimeout(() => {
            vid.pause();
            vid.src = "";
            img.src = "";
        }, 300);
    }

    // Fungsi Meluaskan Grid Foto ke Bawah (Expand)
    function expandGallery() {
        // 1. Sembunyikan tombol "Lihat Media Lain"
        document.getElementById('btnExpandGallery').classList.add('hidden');

        // 2. Cari semua media yang tersembunyi, lalu tampilkan
        let hiddenItems = document.querySelectorAll('.gallery-hidden-item');
        hiddenItems.forEach(item => {
            item.classList.remove('hidden');
            // Menambahkan sedikit efek pudar agar munculnya mulus
            item.style.opacity = '0';
            setTimeout(() => {
                item.style.transition = 'opacity 0.5s ease-in-out';
                item.style.opacity = '1';
            }, 50);
        });
    }
</script>

<!-- ============================================== -->
<!-- LIBRARY & SCRIPT UNTUK SWEETALERT2 POP-UP -->
<!-- ============================================== -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. Fungsi Pop-up Khusus TOLAK (Dengan Kolom Alasan)
    function konfirmasiTolak(formId) {
        Swal.fire({
            title: 'Tolak Laporan',
            text: 'Silakan berikan alasan yang jelas mengapa laporan ini ditolak:',
            input: 'textarea',
            inputPlaceholder: 'Contoh: Laporan tidak valid, foto kurang jelas, atau bukan wewenang PUPR...',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Tolak Laporan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-2xl' },
            inputValidator: (value) => {
                if (!value) {
                    return 'Alasan penolakan tidak boleh kosong!'; // Validasi agar admin wajib mengisi
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Masukkan teks alasan ke dalam form rahasia sebelum disubmit
                document.getElementById('inputAlasanTolak').value = result.value;
                tampilkanLoading();
                document.getElementById(formId).submit();
            }
        });
    }

    // 2. Fungsi Pop-up Khusus DISPOSISI (Desain Kustom Sesuai Mockup)
    function konfirmasiDisposisi(formId) {
        Swal.fire({
            title: '<div class="text-left text-[18px] font-extrabold text-[#102A63] pt-1">Buat Disposisi Laporan</div>',
            html: `
                <div class="border-t border-gray-200 mt-4 pt-5 text-left space-y-4">
                    <select id="swal-bidang" class="w-full border border-gray-300 text-gray-700 rounded-lg p-3 text-sm focus:border-[#1a73e8] focus:ring-1 focus:ring-[#1a73e8] outline-none transition bg-white cursor-pointer">
                        <option value="" disabled selected>Pilih Bidang Tujuan</option>
                        <option value="Bina Marga">Bina Marga (Jalan & Jembatan)</option>
                        <option value="SDA">Sumber Daya Air (SDA)</option>
                        <option value="Cipta Karya">Cipta Karya (Fasum & Gedung)</option>
                        <option value="Penataan Ruang">Penataan Ruang</option>
                        <option value="Bina Konstruksi">Bina Konstruksi</option>
                    </select>

                    <textarea id="swal-catatan" rows="4" class="w-full border border-gray-300 text-gray-700 rounded-lg p-3 text-sm focus:border-[#1a73e8] focus:ring-1 focus:ring-[#1a73e8] outline-none transition resize-none" placeholder="Catatan Tambahan (Opsional)"></textarea>
                </div>
            `,
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonColor: '#1a73e8', // Warna biru persis di foto
            cancelButtonColor: '#9ca3af', // Warna abu-abu persis di foto
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Batal',
            reverseButtons: true, // Tombol batal di kiri, kirim di kanan
            padding: '1.5em',
            customClass: {
                popup: 'rounded-2xl shadow-xl border border-gray-100',
                title: 'w-full mb-0 p-0',
                closeButton: 'focus:outline-none hover:text-red-500 mt-2 mr-2',
                confirmButton: 'px-6 py-2.5 rounded-lg text-sm font-bold tracking-wide',
                cancelButton: 'px-6 py-2.5 rounded-lg text-sm font-bold tracking-wide bg-[#9ca3af]'
            },
            preConfirm: () => {
                // Ambil nilai dari inputan pop-up
                const bidang = document.getElementById('swal-bidang').value;
                const catatan = document.getElementById('swal-catatan').value;

                if (!bidang) {
                    Swal.showValidationMessage('Silakan pilih bidang tujuan terlebih dahulu!');
                    return false;
                }
                return { bidang: bidang, catatan: catatan };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Masukkan nilai dari pop-up ke form tersembunyi
                document.getElementById('inputBidangDisposisi').value = result.value.bidang;
                document.getElementById('inputCatatanDisposisi').value = result.value.catatan;

                tampilkanLoading();
                document.getElementById(formId).submit();
            }
        });
    }


    // 3. Fungsi Global untuk Selesai (Tanpa Input)
    function konfirmasiAksi(formId, judul, teks, ikon, teksTombol, warnaTombol) {
        Swal.fire({
            title: judul,
            text: teks,
            icon: ikon,
            showCancelButton: true,
            confirmButtonColor: warnaTombol,
            cancelButtonColor: '#9ca3af',
            confirmButtonText: teksTombol,
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                tampilkanLoading();
                document.getElementById(formId).submit();
            }
        });
    }

    // 4. Fungsi Pop-up Khusus untuk Membatalkan Aksi (Revert Status)
    function konfirmasiBatal(formId, judul) {
        Swal.fire({
            title: judul,
            text: 'Tindakan ini akan mengembalikan status laporan ke tahap sebelumnya.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6b7280', // Warna abu-abu
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tutup',
            reverseButtons: true,
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                tampilkanLoading();
                document.getElementById(formId).submit();
            }
        });
    }

    // Fungsi Loading
    function tampilkanLoading() {
        Swal.fire({
            title: 'Memproses...', text: 'Menyimpan perubahan ke database.',
            allowOutsideClick: false, showConfirmButton: false,
            didOpen: () => { Swal.showLoading(); }
        });
    }

    // Notifikasi Sukses
    @if(session('sukses'))
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('sukses') }}', showConfirmButton: false, timer: 2500, customClass: { popup: 'rounded-2xl' }});
        });
    @endif
</script>

@endsection
