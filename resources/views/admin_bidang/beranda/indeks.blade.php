@extends('layouts.app_bidang')

@push('css')
<!-- CSS Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<!-- Custom CSS untuk Leaflet Popup -->
<style>
    .leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    .leaflet-popup-content { margin: 12px; }
    /* Memperbaiki z-index agar menu filter tidak tertutup peta */
    .z-menu { z-index: 2000 !important; }
</style>
@endpush

@section('konten')
<div class="max-w-7xl mx-auto pb-10">

    <!-- Header Dashboard -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Dashboard {{ $bidang->nama_bidang }}</h2>
            <div class="flex items-center gap-3">
                <span class="bg-yellow-400 text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">Admin Bidang</span>
                <span class="text-xs text-gray-500 font-medium flex items-center">
                    <!-- Tambahkan locale('id') agar menjadi bahasa Indonesia -->
                    <i class="far fa-calendar-alt mr-1.5"></i> {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- 4 KARTU STATISTIK (Menunggu Terhubung Database) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <!-- Kartu 1 -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32 relative overflow-hidden group hover:border-blue-200 transition">
            <div class="flex justify-between items-start">
                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Laporan Masuk</h4>
                <i class="fas fa-inbox text-gray-200 text-xl group-hover:text-blue-100 transition"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-gray-800">{{ $total_laporan ?? 0 }}</h2>
                <p class="text-[10px] text-gray-400 font-medium mt-1">Total Laporan Bidang Ini</p>
            </div>
        </div>

        <!-- Kartu 2 -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32 relative overflow-hidden group hover:border-yellow-200 transition">
            <div class="flex justify-between items-start">
                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Laporan Mendesak</h4>
                <i class="fas fa-exclamation-circle text-gray-200 text-xl group-hover:text-yellow-100 transition"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-gray-800">{{ $laporan_mendesak ?? 0 }}</h2>
                <p class="text-[10px] text-gray-400 font-medium mt-1"><span class="text-yellow-600 font-bold bg-yellow-50 px-1.5 py-0.5 rounded border border-yellow-100">Menunggu Validasi</span></p>
            </div>
        </div>

        <!-- Kartu 3 -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32 relative overflow-hidden group hover:border-blue-200 transition">
            <div class="flex justify-between items-start">
                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pekerjaan Berjalan</h4>
                <i class="fas fa-tools text-gray-200 text-xl group-hover:text-blue-100 transition"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-gray-800">{{ $laporan_proses ?? 0 }}</h2>
                <p class="text-[10px] text-gray-400 font-medium mt-1">Sedang ditangani tim UPTD</p>
            </div>
        </div>

        <!-- Kartu 4 -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32 relative overflow-hidden group hover:border-green-200 transition">
            <div class="flex justify-between items-start">
                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pekerjaan Selesai</h4>
                <i class="fas fa-check-circle text-gray-200 text-xl group-hover:text-green-100 transition"></i>
            </div>
            <div>
                <div class="flex justify-between items-end">
                    <h2 class="text-3xl font-extrabold text-gray-800">{{ $laporan_selesai ?? 0 }}</h2>
                    <span class="text-xs font-bold text-green-500 mb-1">Berhasil</span>
                </div>
            </div>
        </div>
    </div>

    <!-- AREA PETA (Monitoring Real-Time Terintegrasi Leaflet) -->
    <div id="peta-container" class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 overflow-hidden flex flex-col transition-all duration-300">
        <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center bg-white z-10">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm">Pemantauan Terkini</h3>
                    <p class="text-[10px] text-gray-400">Pemantauan unit operasional {{ $bidang->nama_bidang }} di wilayah Subang</p>
                </div>
            </div>

            <!-- Tombol Aksi Peta (Layer & Filter) -->
            <div class="flex items-center gap-2">
                <!-- Ganti Layer -->
                <div class="relative">
                    <button id="btn-layer" onclick="toggleMenuPeta('menu-layer')" class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 flex items-center justify-center transition focus:outline-none" title="Ganti Tampilan Peta">
                        <i class="fas fa-layer-group text-xs"></i>
                    </button>
                    <div id="menu-layer" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-xl border border-gray-100 z-menu p-2">
                        <button onclick="gantiLayerPeta('standar')" class="w-full text-left px-3 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">Peta Jalan</button>
                        <button onclick="gantiLayerPeta('satelit')" class="w-full text-left px-3 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">Citra Satelit</button>
                    </div>
                </div>

                <!-- Layar Penuh -->
                <button id="btn-fullscreen" onclick="toggleFullscreen()" class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 flex items-center justify-center transition focus:outline-none" title="Layar Penuh">
                    <i class="fas fa-expand text-xs" id="icon-fs"></i>
                </button>

                <span class="hidden md:flex ml-2 items-center text-[10px] font-bold text-green-600 bg-green-50 px-2.5 py-1 rounded-full border border-green-100">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span> Pembaruan Langsung
                </span>
            </div>
        </div>

        <!-- Wadah Peta Leaflet -->
        <div id="map-wrapper" class="relative w-full h-[400px] bg-gray-100 z-0 transition-all duration-300">
            <!-- Peta Leaflet Akan Di-Render di Sini -->
            <div id="mapDashboard" class="w-full h-full"></div>

            <!-- Legenda Peta -->
            <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur shadow-lg border border-gray-100 rounded-xl p-4 z-[1000] w-48">
                <h4 class="text-[9px] font-bold text-gray-500 uppercase tracking-wider mb-3">Status Operasional</h4>
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between text-xs font-medium text-gray-700">
                        <div class="flex items-center"><i class="fas fa-map-marker-alt text-yellow-500 w-3 text-center mr-2"></i> Proses</div>
                        <span class="font-bold text-gray-900">{{ $laporan_proses ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-medium text-gray-700">
                        <div class="flex items-center"><i class="fas fa-map-marker-alt text-red-500 w-3 text-center mr-2"></i> Mendesak</div>
                        <span class="font-bold text-gray-900">{{ $laporan_mendesak ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-medium text-gray-700">
                        <div class="flex items-center"><i class="fas fa-map-marker-alt text-green-500 w-3 text-center mr-2"></i> Selesai</div>
                        <span class="font-bold text-gray-900">{{ $laporan_selesai ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AREA BAWAH: Tabel & List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Laporan Masuk Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-center mb-5">
                <h3 class="font-bold text-gray-800 text-sm">Laporan Masuk Terbaru</h3>
                <a href="#" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 transition">Lihat Semua</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] text-gray-400 uppercase tracking-wider border-b border-gray-50">
                            <th class="pb-3 font-bold">ID Laporan</th>
                            <th class="pb-3 font-bold">Lokasi / Ruas</th>
                            <th class="pb-3 font-bold">Kategori</th>
                            <th class="pb-3 font-bold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600">
                        <!-- Data Dummy Sementara -->
                        <tr>
                            <td colspan="4" class="text-center py-8 text-xs text-gray-400">Menunggu integrasi data tabel...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Penugasan Aktif -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col">
            <h3 class="font-bold text-gray-800 text-sm mb-5">Tim Pekerja (UPTD) Aktif</h3>
            <div class="flex-1 overflow-y-auto">
               <div class="py-8 text-center text-gray-400 text-xs">Menunggu integrasi data UPTD...</div>
            </div>
            <button class="w-full mt-4 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-lg text-xs font-bold transition border border-gray-100">
                Kelola Seluruh Tim
            </button>
        </div>
    </div>

</div>
@endsection

@push('js')
<!-- Script Peta Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map, layerStandar, layerSatelit, markerGroup;
    // Nanti $sebaran_laporan ini dikirim dari BerandaController Admin Bidang
    let dataLaporan = @json($sebaran_laporan ?? []);

    document.addEventListener("DOMContentLoaded", function() {
        // 1. Inisialisasi Peta di div mapDashboard
        map = L.map('mapDashboard').setView([-6.5627, 107.7613], 12);

        // 2. Siapkan Layer
        layerStandar = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { maxZoom: 20 });
        layerSatelit = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 });

        layerStandar.addTo(map);
        markerGroup = L.layerGroup().addTo(map);

        // 3. Render Marker
        renderMarkers();
    });

    function renderMarkers() {
        markerGroup.clearLayers();

        if(dataLaporan.length > 0) {
            dataLaporan.forEach(function(laporan) {
                if(laporan.lokasi_gps) {
                    let koordinat = laporan.lokasi_gps.split(',');
                    if(koordinat.length == 2) {
                        let lat = parseFloat(koordinat[0].trim());
                        let lng = parseFloat(koordinat[1].trim());

                        // Logika Warna Ikon
                        let warnaIkon = 'text-red-500';
                        if(laporan.status === 'proses') warnaIkon = 'text-yellow-500';
                        if(laporan.status === 'selesai') warnaIkon = 'text-green-500';

                        // Custom Ikon HTML
                        let ikonCustom = L.divIcon({
                            className: 'bg-transparent',
                            html: `<div style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);" class="${warnaIkon} text-[35px] hover:scale-110 transition-transform cursor-pointer">
                                     <i class="fas fa-map-marker-alt"></i>
                                   </div>`,
                            iconSize: [30, 42],
                            iconAnchor: [15, 40],
                            popupAnchor: [0, -35]
                        });

                        // URL Aksi
                        let urlStreetView = `https://www.google.com/maps/@?api=1&map_action=pano&viewpoint=${lat},${lng}`;
                        let urlMaps = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;

                        // Bind Popup
                        let marker = L.marker([lat, lng], {icon: ikonCustom});
                        marker.bindPopup(`
                            <div class="p-2 w-56 text-center">
                                <p class="text-xs font-bold text-gray-500 mb-1">ID: ${laporan.id_laporan}</p>
                                <p class="font-extrabold text-gray-800 text-sm mb-2 leading-tight">${laporan.kategori_bidang}</p>
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 text-[10px] rounded-full font-bold mb-4 inline-block">STATUS: ${laporan.status.toUpperCase()}</span>

                                <div class="space-y-2">
                                    <a href="${urlStreetView}" target="_blank" class="w-full bg-blue-600 hover:bg-blue-700 !text-white text-xs font-bold py-2.5 px-3 rounded-lg flex items-center justify-center transition shadow-md" style="color: white !important;">
                                        <i class="fas fa-street-view mr-2 text-sm"></i> Street View
                                    </a>
                                    <a href="${urlMaps}" target="_blank" class="w-full border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-blue-600 text-xs font-bold py-2 px-3 rounded-lg flex items-center justify-center transition" style="text-decoration: none;">
                                        <i class="fas fa-map-marked-alt mr-2"></i> Buka Google Maps
                                    </a>
                                </div>
                            </div>
                        `);
                        markerGroup.addLayer(marker);
                    }
                }
            });
        }
    }

    // Fungsi Interaksi UI (Menu & Fullscreen)
    function gantiLayerPeta(jenis) {
        if(jenis === 'standar') {
            map.removeLayer(layerSatelit);
            layerStandar.addTo(map);
        } else {
            map.removeLayer(layerStandar);
            layerSatelit.addTo(map);
        }
        document.getElementById('menu-layer').classList.add('hidden');
    }

    function toggleMenuPeta(menuId) {
        let menu = document.getElementById(menuId);
        if(menu.classList.contains('hidden')) {
            document.getElementById('menu-layer').classList.add('hidden');
            menu.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
        }
    }

    function toggleFullscreen() {
        let container = document.getElementById('peta-container');
        if (!document.fullscreenElement) {
            container.requestFullscreen().catch(err => { console.log(err); });
        } else {
            document.exitFullscreen();
        }
    }

    document.addEventListener('fullscreenchange', (event) => {
        let wrapper = document.getElementById('map-wrapper');
        let iconBtn = document.getElementById('icon-fs');

        if (document.fullscreenElement) {
            wrapper.classList.remove('h-[400px]');
            wrapper.style.height = 'calc(100vh - 70px)';
            iconBtn.classList.remove('fa-expand');
            iconBtn.classList.add('fa-compress');
        } else {
            wrapper.style.height = '';
            wrapper.classList.add('h-[400px]');
            iconBtn.classList.remove('fa-compress');
            iconBtn.classList.add('fa-expand');
        }
        setTimeout(() => { map.invalidateSize(); }, 300);
    });

    document.addEventListener('click', function(event) {
        if(!event.target.closest('#btn-layer') && !event.target.closest('#menu-layer')) {
            document.getElementById('menu-layer').classList.add('hidden');
        }
    });
</script>
@endpush
