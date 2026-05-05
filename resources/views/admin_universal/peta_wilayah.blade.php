@extends('layouts.app')

@section('konten')
<!-- Muat Library Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="flex flex-col h-[85vh]">
    <!-- Header Peta -->
    <div class="flex justify-between items-end mb-4 shrink-0">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 mb-1">Peta Sebaran Infrastruktur</h2>
            <p class="text-sm text-gray-500">Pantau seluruh titik lokasi pelaporan masyarakat di wilayah Kabupaten Subang.</p>
        </div>
        <div class="flex gap-2">
            <span class="bg-blue-50 text-pupr-blue border border-blue-200 px-4 py-2 rounded-lg text-sm font-bold shadow-sm flex items-center">
                <i class="fas fa-map-pin mr-2"></i> Total: {{ $semua_laporan->count() }} Titik
            </span>
        </div>
    </div>

    <!-- Container Peta -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex-1 relative z-0">
        <div id="mapUtama" class="w-full h-full"></div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Inisialisasi Peta (Pusat di Subang)
        const map = L.map('mapUtama').setView([-6.5627, 107.7613], 12);

        // 2. Tampilan Satelit/Jalan dari OpenStreetMap
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap & SIGAP PUPR',
            maxZoom: 19
        }).addTo(map);

        // 3. Ambil data laporan dari Controller yang sudah diubah ke format JSON
        const laporanData = @json($semua_laporan);

        // 4. Sebarkan Pin Marker ke dalam Peta
        laporanData.forEach(function(laporan) {
            // Pecah koordinat (contoh: "-6.5627, 107.7613")
            let coords = laporan.lokasi_gps.split(',');
            if(coords.length === 2) {
                let lat = parseFloat(coords[0].trim());
                let lng = parseFloat(coords[1].trim());

                if(!isNaN(lat) && !isNaN(lng)) {
                    // Tentukan warna status
                    let statusColor = 'blue';
                    if(laporan.status === 'selesai') statusColor = 'green';
                    else if(laporan.status === 'pending') statusColor = 'orange';

                    // Buat popup informasi jika pin diklik
                    let popupContent = `
                        <div class="text-xs p-1">
                            <strong class="text-sm text-pupr-blue block mb-1">${laporan.id_laporan}</strong>
                            <p class="mb-1"><b>Bidang:</b> ${laporan.kategori_bidang}</p>
                            <p class="mb-2 line-clamp-2">${laporan.deskripsi_laporan}</p>
                            <a href="/admin-universal/laporan/detail/${laporan.id}" class="text-blue-600 underline font-bold">Lihat Detail Laporan</a>
                        </div>
                    `;

                    L.marker([lat, lng]).addTo(map)
                     .bindPopup(popupContent);
                }
            }
        });
    });
</script>
@endsection
