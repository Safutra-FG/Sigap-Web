@extends('layouts.app')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<style>
    .leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    .leaflet-popup-content { margin: 12px; }

    /* --- TAMBAHAN ANIMASI HALUS --- */
    @keyframes fadeInUpElement {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animasi-masuk {
        animation: fadeInUpElement 0.6s ease-out forwards;
        opacity: 0; /* Elemen sembunyi dulu sebelum animasi mulai */
    }
    /* Jeda berurutan untuk efek kaskade */
    .jeda-1 { animation-delay: 0.1s; }
    .jeda-2 { animation-delay: 0.2s; }
    .jeda-3 { animation-delay: 0.3s; }
    .jeda-4 { animation-delay: 0.4s; }
    .jeda-5 { animation-delay: 0.5s; }
</style>

@section('konten')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div></div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow animasi-masuk jeda-1">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="fas fa-chart-bar"></i>
            </div>
            @if(($statistik['persen_total'] ?? 0) >= 0)
                <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full flex items-center">
                    <i class="fas fa-arrow-trend-up mr-1"></i> {{ $statistik['persen_total'] ?? 0 }}%
                </span>
            @else
                <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded-full flex items-center">
                    <i class="fas fa-arrow-trend-down mr-1"></i> {{ abs($statistik['persen_total'] ?? 0) }}%
                </span>
            @endif
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Total Laporan</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($statistik['total_laporan'] ?? 0, 0, ',', '.') }}</h3>
        </div>
        <div class="mt-4 text-xs text-gray-400 font-medium flex justify-between">
            <span>Dibandingkan bulan lalu</span>
            <span class="font-bold {{ ($statistik['selisih_total'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-500' }}">
                {{ ($statistik['selisih_total'] ?? 0) > 0 ? '+' : '' }}{{ $statistik['selisih_total'] ?? 0 }}
            </span>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow animasi-masuk jeda-2">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                <i class="fas fa-check-circle"></i>
            </div>
            <span class="{{ ($statistik['rasio_selesai'] ?? 0) >= 75 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} text-xs font-bold px-2 py-1 rounded-full flex items-center">
                <i class="fas fa-star mr-1"></i> Performa
            </span>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Selesai</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($statistik['selesai'] ?? 0, 0, ',', '.') }}</h3>
        </div>
        <div class="mt-4 text-xs text-gray-400 font-medium flex justify-between">
            <span>Rasio Penyelesaian</span>
            <span class="text-gray-800 font-bold">{{ $statistik['rasio_selesai'] ?? 0 }}%</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow animasi-masuk jeda-3">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600">
                <i class="fas fa-sync-alt"></i>
            </div>
            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-full flex items-center">
                <i class="fas fa-tools mr-1"></i> Lapangan
            </span>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Proses</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($statistik['dalam_proses'] ?? 0, 0, ',', '.') }}</h3>
        </div>
        <div class="mt-4 text-xs text-gray-400 font-medium flex justify-between">
            <span>Laporan sedang dikerjakan</span>
            <span class="text-gray-800 font-bold">{{ $statistik['dalam_proses'] ?? 0 }} Aktif</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow animasi-masuk jeda-4">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                <i class="fas fa-clipboard-list"></i>
            </div>
            @if(($statistik['laporan_terbaru'] ?? 0) > 0)
                <span class="bg-red-50 text-red-600 border border-red-200 text-xs font-bold px-2 py-1 rounded-full flex items-center animate-pulse">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Butuh Validasi
                </span>
            @else
                <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2 py-1 rounded-full flex items-center">
                    <i class="fas fa-check mr-1"></i> Clear
                </span>
            @endif
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Menunggu Validasi</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($statistik['laporan_terbaru'] ?? 0, 0, ',', '.') }}</h3>
        </div>
        <div class="mt-4 text-xs text-gray-400 font-medium flex justify-between">
            <span>Laporan baru / pending</span>
            <span class="{{ ($statistik['laporan_terbaru'] ?? 0) > 0 ? 'text-red-500' : 'text-gray-800' }} font-bold">
                {{ $statistik['laporan_terbaru'] ?? 0 }} Urgent
            </span>
        </div>
    </div>
</div>

<div id="peta-container" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8 relative transition-all animasi-masuk jeda-5">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Peta Sebaran Laporan</h3>
            <p class="text-sm text-gray-400 font-medium">Visualisasi geografis infrastruktur per wilayah</p>
        </div>
        <div class="flex space-x-3 relative">
            <div class="relative">
                <button id="btn-layer" onclick="toggleMenuPeta('menu-layer')" class="w-10 h-10 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 flex items-center justify-center transition focus:outline-none" title="Ganti Tampilan Peta">
                    <i class="fas fa-layer-group"></i>
                </button>
                <div id="menu-layer" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-xl border border-gray-100 z-[2000] p-2">
                    <button onclick="gantiLayerPeta('standar')" class="w-full text-left px-3 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">Peta Jalan (Bawaan)</button>
                    <button onclick="gantiLayerPeta('satelit')" class="w-full text-left px-3 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">Citra Satelit</button>
                </div>
            </div>

            <div class="relative">
                <button id="btn-filter" onclick="toggleMenuPeta('menu-filter')" class="w-10 h-10 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 flex items-center justify-center transition focus:outline-none" title="Filter Laporan">
                    <i class="fas fa-filter"></i>
                </button>
                <div id="menu-filter" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-[2000] p-4">
                    <p class="text-xs font-bold text-gray-400 mb-3 uppercase tracking-wider border-b pb-2">Filter Data Laporan</p>
                    <label class="flex items-center space-x-3 mb-2 cursor-pointer hover:bg-gray-50 p-1 rounded transition">
                        <input type="checkbox" value="pending" class="filter-cb form-checkbox h-4 w-4 text-red-500" checked onchange="renderMarkers()">
                        <span class="text-sm font-medium text-gray-700">Menunggu Validasi</span>
                    </label>
                    <label class="flex items-center space-x-3 mb-2 cursor-pointer hover:bg-gray-50 p-1 rounded transition">
                        <input type="checkbox" value="proses" class="filter-cb form-checkbox h-4 w-4 text-yellow-500" checked onchange="renderMarkers()">
                        <span class="text-sm font-medium text-gray-700">Dalam Pengerjaan</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-1 rounded transition">
                        <input type="checkbox" value="selesai" class="filter-cb form-checkbox h-4 w-4 text-green-500" checked onchange="renderMarkers()">
                        <span class="text-sm font-medium text-gray-700">Selesai</span>
                    </label>
                </div>
            </div>

            <button id="btn-fullscreen" onclick="toggleFullscreen()" class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white text-sm font-bold rounded-lg transition flex items-center focus:outline-none shadow-md hover:shadow-lg">
                <i class="fas fa-expand mr-2" id="icon-fs"></i> <span id="text-fs">Layar Penuh</span>
            </button>
        </div>
    </div>

    <div id="map-wrapper" class="relative w-full h-[400px] rounded-xl overflow-hidden border border-gray-200 z-0 transition-all duration-300">
        <div id="map" class="w-full h-full bg-gray-100"></div>

        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur rounded-xl p-4 shadow-lg border border-white/50 z-[1000]">
            <h4 class="text-[10px] font-extrabold text-gray-500 mb-3 tracking-widest uppercase">Legenda Peta</h4>
            <div class="space-y-3">
                <div class="flex items-center text-xs font-bold text-gray-600">
                    <i class="fas fa-map-marker-alt text-red-500 text-lg w-5 text-center drop-shadow-sm mr-2"></i> Mendesak / Pending
                </div>
                <div class="flex items-center text-xs font-bold text-gray-600">
                    <i class="fas fa-map-marker-alt text-yellow-500 text-lg w-5 text-center drop-shadow-sm mr-2"></i> Dalam Pengerjaan
                </div>
                <div class="flex items-center text-xs font-bold text-gray-600">
                    <i class="fas fa-map-marker-alt text-green-500 text-lg w-5 text-center drop-shadow-sm mr-2"></i> Stabil / Selesai
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map, layerStandar, layerSatelit, markerGroup;
    let dataLaporan = @json($sebaran_laporan ?? []);

    document.addEventListener("DOMContentLoaded", function() {
        map = L.map('map').setView([-6.5627, 107.7613], 12);

        layerStandar = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { maxZoom: 20 });
        layerSatelit = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 });

        layerStandar.addTo(map);
        markerGroup = L.layerGroup().addTo(map);
        renderMarkers();
    });

    function renderMarkers() {
        markerGroup.clearLayers();
        let filterAktif = Array.from(document.querySelectorAll('.filter-cb:checked')).map(cb => cb.value);

        if(dataLaporan.length > 0) {
            dataLaporan.forEach(function(laporan) {
                if(filterAktif.includes(laporan.status) && laporan.lokasi_gps) {
                    let koordinat = laporan.lokasi_gps.split(',');
                    if(koordinat.length == 2) {
                        let lat = parseFloat(koordinat[0].trim());
                        let lng = parseFloat(koordinat[1].trim());

                        // 1. Logika Warna Teks Ikon FontAwesome
                        let warnaIkon = 'text-red-500';
                        if(laporan.status === 'proses') warnaIkon = 'text-yellow-500';
                        if(laporan.status === 'selesai') warnaIkon = 'text-green-500';

                        // 2. Pembuatan Ikon Pin Map
                        let ikonCustom = L.divIcon({
                            className: 'bg-transparent',
                            html: `<div style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);" class="${warnaIkon} text-[35px] hover:scale-110 transition-transform cursor-pointer">
                                     <i class="fas fa-map-marker-alt"></i>
                                   </div>`,
                            iconSize: [30, 42],
                            iconAnchor: [15, 40], // Anchor di ujung bawah ikon
                            popupAnchor: [0, -35] // Popup muncul di atas ikon
                        });

                        // 3. Tautan URL Google Maps & Street View
                        // URL untuk membuka langsung mode Street View (Panorama)
                        let urlStreetView = `https://www.google.com/maps/@?api=1&map_action=pano&viewpoint=$${lat},${lng}`;
                        // URL standar untuk membuka pin Google Maps biasa
                        let urlMaps = `https://www.google.com/maps/search/?api=1&query=$${lat},${lng}`;

                        // 4. Bind Popup dengan Tombol Aksi
                        let marker = L.marker([lat, lng], {icon: ikonCustom});
                        marker.bindPopup(`
                            <div class="p-2 w-56 text-center">
                                <p class="text-xs font-bold text-gray-500 mb-1">ID: ${laporan.id_laporan}</p>
                                <p class="font-extrabold text-gray-800 text-sm mb-2 leading-tight">${laporan.kategori_bidang}</p>
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 text-[10px] rounded-full font-bold mb-4 inline-block">STATUS: ${laporan.status.toUpperCase()}</span>

                                <div class="space-y-2">
                                    <a href="${urlStreetView}" target="_blank" class="w-full bg-blue-600 hover:bg-blue-700 !text-white text-xs font-bold py-2.5 px-3 rounded-lg flex items-center justify-center transition shadow-md" style="color: white !important;">
                                        <i class="fas fa-street-view mr-2 text-sm"></i> Lihat Sekitar (Street View)
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

    // Fungsi Ganti Layer, Toggle Menu, dan Layar Penuh (Tetap Sama)
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
            document.getElementById('menu-filter').classList.add('hidden');
            menu.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
        }
    }

    function toggleFullscreen() {
        let container = document.getElementById('peta-container');
        if (!document.fullscreenElement) {
            container.requestFullscreen().catch(err => { alert(`Gagal: ${err.message}`); });
        } else {
            document.exitFullscreen();
        }
    }

    document.addEventListener('fullscreenchange', (event) => {
        let wrapper = document.getElementById('map-wrapper');
        let teksBtn = document.getElementById('text-fs');
        let iconBtn = document.getElementById('icon-fs');

        if (document.fullscreenElement) {
            wrapper.classList.remove('h-[400px]');
            wrapper.style.height = 'calc(100vh - 120px)';
            teksBtn.innerText = "Keluar Penuh";
            iconBtn.classList.remove('fa-expand');
            iconBtn.classList.add('fa-compress');
        } else {
            wrapper.style.height = '';
            wrapper.classList.add('h-[400px]');
            teksBtn.innerText = "Layar Penuh";
            iconBtn.classList.remove('fa-compress');
            iconBtn.classList.add('fa-expand');
        }
        setTimeout(() => { map.invalidateSize(); }, 300);
    });

    document.addEventListener('click', function(event) {
        if(!event.target.closest('#btn-layer') && !event.target.closest('#menu-layer')) {
            document.getElementById('menu-layer').classList.add('hidden');
        }
        if(!event.target.closest('#btn-filter') && !event.target.closest('#menu-filter')) {
            document.getElementById('menu-filter').classList.add('hidden');
        }
    });
</script>
@endsection
