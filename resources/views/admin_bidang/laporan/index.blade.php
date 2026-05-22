@extends('layouts.app_bidang')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    .leaflet-popup-content { margin: 12px; }
</style>
@endpush

@section('konten')
<div class="max-w-7xl mx-auto pb-10">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Kelola Laporan & Penugasan</h2>
            <p class="text-sm text-gray-500 font-medium">Manajemen penugasan tim lapangan (UPTD) dan pemantauan infrastruktur.</p>
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('admin_bidang.laporan.ekspor_excel') }}" class="bg-green-50 border border-green-200 text-green-700 hover:bg-green-100 px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition flex items-center">
                <i class="fas fa-file-excel mr-2"></i> Rekap Excel
            </a>
            <a href="{{ route('admin_bidang.laporan.ekspor_pdf') }}" target="_blank" class="bg-red-50 border border-red-200 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition flex items-center">
                <i class="fas fa-file-pdf mr-2"></i> Rekap PDF
            </a>
        </div>
    </div>

    @if(session('sukses'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('sukses') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0"><i class="fas fa-file-invoice"></i></div>
            <div>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-0.5">Total Laporan</p>
                <h4 class="text-2xl font-extrabold text-gray-800 leading-none">{{ number_format($statistik['total'] ?? 0, 0, ',', '.') }}</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-500 flex items-center justify-center text-xl shrink-0"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-0.5">Menunggu UPTD</p>
                <h4 class="text-2xl font-extrabold text-gray-800 leading-none">{{ number_format($statistik['menunggu'] ?? 0, 0, ',', '.') }}</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl shrink-0"><i class="fas fa-tools"></i></div>
            <div>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-0.5">Sedang Dikerjakan</p>
                <h4 class="text-2xl font-extrabold text-gray-800 leading-none">{{ number_format($statistik['proses'] ?? 0, 0, ',', '.') }}</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl shrink-0"><i class="fas fa-check-circle"></i></div>
            <div>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-0.5">Selesai</p>
                <h4 class="text-2xl font-extrabold text-gray-800 leading-none">{{ number_format($statistik['selesai'] ?? 0, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-8 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4 bg-white">
            <div class="flex gap-3">
                <div class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 font-medium flex items-center cursor-pointer hover:bg-gray-50">
                    Status: Semua <i class="fas fa-filter ml-3 text-gray-400 text-xs"></i>
                </div>
            </div>
            <div class="text-sm text-gray-600 font-medium flex items-center">
                Urutkan:
                <span class="ml-2 border border-gray-200 rounded-lg px-3 py-2 cursor-pointer hover:bg-gray-50">
                    Terbaru <i class="fas fa-chevron-down ml-2 text-gray-400 text-xs"></i>
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="text-gray-400 text-[11px] uppercase font-bold tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">NO</th>
                        <th class="px-6 py-4">ID LAPORAN</th>
                        <th class="px-6 py-4">LOKASI</th>
                        <th class="px-6 py-4">TANGGAL MASUK</th>
                        <th class="px-6 py-4">STATUS</th>
                        <th class="px-6 py-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm font-medium text-gray-700">
                    @forelse($laporan_masuk as $index => $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-5 text-gray-500">{{ $laporan_masuk->firstItem() + $index }}</td>
                        <td class="px-6 py-5 font-bold text-pupr-blue">{{ $item->id_laporan }}</td>
                        <td class="px-6 py-5">
                            <p class="text-gray-800 font-bold">{{ explode(',', $item->alamat_map)[0] ?? 'Lokasi' }}</p>
                            <p class="text-[10px] text-gray-400">{{ Str::limit($item->alamat_map, 40) }}</p>
                        </td>
                        <td class="px-6 py-5 text-gray-500">
                            {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-5">
                            @if($item->status == 'diteruskan')
                                <span class="flex items-center text-yellow-600 bg-yellow-50 px-2.5 py-1 rounded-full w-max text-[10px] font-bold border border-yellow-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span> Menunggu Penugasan
                                </span>
                            @elseif($item->status == 'proses')
                                <span class="flex items-center text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full w-max text-[10px] font-bold border border-indigo-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span> Dalam Pengerjaan
                                </span>
                            @else
                                <span class="flex items-center text-green-600 bg-green-50 px-2.5 py-1 rounded-full w-max text-[10px] font-bold border border-green-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span> Selesai
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 flex items-center justify-center">
                            <a href="{{ route('admin_bidang.laporan.detail', $item->id) }}" class="bg-blue-50 text-blue-600 hover:bg-pupr-blue hover:text-white px-4 py-2 rounded-lg text-xs font-bold transition flex items-center border border-blue-100 hover:border-pupr-blue">
                                @if($item->status == 'diteruskan')
                                    <i class="fas fa-paper-plane mr-2"></i> Tugaskan
                                @else
                                    <i class="fas fa-file-alt mr-2"></i> Lihat Detail
                                @endif
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 font-medium">Belum ada laporan yang masuk ke bidang Anda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t border-gray-100">
            {{ $laporan_masuk->links('pagination::tailwind') }}
        </div>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden h-[400px]">
        <div class="absolute top-4 left-4 z-[1000] bg-white/95 backdrop-blur px-3 py-1.5 rounded-md border border-gray-200 shadow-sm flex items-center">
            <i class="far fa-map text-pupr-blue mr-2"></i>
            <span class="text-[10px] font-extrabold text-gray-700 tracking-wider">SEBARAN LAPORAN TERBARU</span>
        </div>
        <div id="mapDashboard" class="w-full h-full bg-gray-200 z-0"></div>
    </div>

</div>

@if(session('sukses'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Dtugaskan!',
            text: "{{ session('sukses') }}", // Akan otomatis memunculkan teks "Laporan berhasil diproses! Pekerja telah ditugaskan ke lokasi."
            timer: 3000,
            showConfirmButton: false,
            customClass: { popup: 'rounded-2xl shadow-xl border border-gray-100' }
        });
    });
</script>
@endif

@endsection

@push('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map, markerGroup;
    let dataLaporan = @json($sebaran_laporan ?? []);

    document.addEventListener("DOMContentLoaded", function() {
        map = L.map('mapDashboard', { zoomControl: false }).setView([-6.5627, 107.7613], 12);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19, attribution: '© OpenStreetMap'
        }).addTo(map);

        markerGroup = L.layerGroup().addTo(map);

        if(dataLaporan.length > 0) {
            dataLaporan.forEach(function(laporan) {
                if(laporan.lokasi_gps) {
                    let koordinat = laporan.lokasi_gps.split(',');
                    if(koordinat.length == 2) {
                        let lat = parseFloat(koordinat[0].trim());
                        let lng = parseFloat(koordinat[1].trim());

                        let warnaBg = 'bg-yellow-500';
                        if(laporan.status === 'proses') warnaBg = 'bg-indigo-500';
                        if(laporan.status === 'selesai') warnaBg = 'bg-green-500';

                        let ikonCustom = L.divIcon({
                            className: 'bg-transparent',
                            html: `
                                <div class="relative w-6 h-6 flex items-center justify-center">
                                    <div class="absolute inset-0 ${warnaBg} rounded-full opacity-40 animate-ping"></div>
                                    <div class="w-3 h-3 ${warnaBg} border-2 border-white rounded-full relative z-10 shadow-md"></div>
                                </div>
                            `,
                            iconSize: [24, 24], iconAnchor: [12, 12], popupAnchor: [0, -10]
                        });

                        let marker = L.marker([lat, lng], {icon: ikonCustom});
                        // PENTING: Gunakan laporan.id (bukan id_laporan) untuk mengarah ke route Detail
                        marker.bindPopup(`
                            <div class="text-center p-1">
                                <p class="text-[10px] font-bold text-gray-500 mb-1">${laporan.id_laporan}</p>
                                <p class="font-bold text-gray-800 text-xs mb-2 uppercase">${laporan.status}</p>
                                <a href="/admin-bidang/laporan/detail/${laporan.id}" class="text-[10px] bg-blue-50 text-blue-600 px-3 py-1 rounded-md font-bold block hover:bg-blue-100 border border-blue-100">Buka Detail</a>
                            </div>
                        `);
                        markerGroup.addLayer(marker);
                    }
                }
            });
        }
    });
</script>
@endpush
