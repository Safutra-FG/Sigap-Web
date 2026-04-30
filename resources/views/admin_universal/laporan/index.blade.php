@extends('layouts.app')

@section('konten')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Kelola Laporan Masyarakat</h2>
    <p class="text-sm text-gray-400 font-medium">Daftar keluhan infrastruktur yang masuk ke sistem SIGAP.</p>
</div>

@if(session('sukses'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center">
        <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('sukses') }}
    </div>
@endif

<div class="grid grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-blue-500">
        <div><p class="text-xs text-gray-400 font-bold uppercase">Total Laporan</p><h4 class="text-xl font-bold text-gray-800">{{ $statistik['total'] }}</h4></div>
        <i class="fas fa-folder text-blue-200 text-2xl"></i>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-yellow-500">
        <div><p class="text-xs text-gray-400 font-bold uppercase">Menunggu Validasi</p><h4 class="text-xl font-bold text-gray-800">{{ $statistik['terbaru'] }}</h4></div>
        <i class="fas fa-clock text-yellow-200 text-2xl"></i>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-purple-500">
        <div><p class="text-xs text-gray-400 font-bold uppercase">Sedang Diproses</p><h4 class="text-xl font-bold text-gray-800">{{ $statistik['proses'] }}</h4></div>
        <i class="fas fa-sync text-purple-200 text-2xl"></i>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-green-500">
        <div><p class="text-xs text-gray-400 font-bold uppercase">Selesai</p><h4 class="text-xl font-bold text-gray-800">{{ $statistik['selesai'] }}</h4></div>
        <i class="fas fa-check-square text-green-200 text-2xl"></i>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-gray-700">Daftar Pelaporan Masuk</h3>
    </div>
    <table class="w-full text-left border-collapse">
        <thead class="bg-white border-b border-gray-100 text-gray-500 text-xs uppercase font-bold tracking-wider">
            <tr>
                <th class="px-6 py-4">ID Laporan</th>
                <th class="px-6 py-4">Tanggal</th>
                <th class="px-6 py-4">Pelapor</th>
                <th class="px-6 py-4">Kategori Bidang</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @forelse($semua_laporan as $item)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-bold text-pupr-blue">{{ $item->id_laporan }}</td>
                <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($item->created_at)->format('d M, Y') }}</td>
                <td class="px-6 py-4 font-medium text-gray-800">{{ $item->pelapor->nama_lengkap ?? 'Anonim' }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $item->kategori_bidang }}</td>
                <td class="px-6 py-4">
                    @if($item->status == 'pending')
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-[10px] font-bold">MENUNGGU VALIDASI</span>
                    @elseif($item->status == 'diteruskan' || $item->status == 'proses')
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-bold">DIPROSES</span>
                    @elseif($item->status == 'selesai')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold">SELESAI</span>
                    @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-bold">DITOLAK</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <a href="{{ route('admin_universal.laporan.detail', $item->id) }}" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-4 py-1.5 rounded-lg text-xs font-bold transition shadow-sm">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-400 font-medium">Belum ada laporan keluhan dari masyarakat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
