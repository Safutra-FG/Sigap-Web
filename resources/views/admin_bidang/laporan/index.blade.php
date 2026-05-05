@extends('layouts.app_bidang')

@section('konten')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Laporan Masuk (Bidang {{ Auth::user()->bidang->nama_bidang }})</h2>
    <p class="text-sm text-gray-400 font-medium">Daftar keluhan yang telah divalidasi dan diteruskan ke bidang Anda.</p>
</div>

@if(session('sukses'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center">
        <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('sukses') }}
    </div>
@endif

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-bold tracking-wider">
            <tr>
                <th class="px-6 py-4">ID Laporan</th>
                <th class="px-6 py-4">Lokasi Kejadian</th>
                <th class="px-6 py-4">Kategori</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-center">Tindakan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @forelse($laporan_masuk as $item)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-bold text-pupr-blue">{{ $item->id_laporan }}</td>
                <td class="px-6 py-4 font-medium text-gray-800">{{ Str::limit($item->alamat_map, 40) }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $item->kategori_bidang }}</td>
                <td class="px-6 py-4">
                    @if($item->status == 'diteruskan')
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-[10px] font-bold">MENUNGGU PENUGASAN</span>
                    @elseif($item->status == 'proses')
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-bold">DALAM PENGERJAAN</span>
                    @else
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold">SELESAI</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <a href="{{ route('admin_bidang.laporan.detail', $item->id) }}" class="bg-pupr-blue text-white hover:bg-opacity-90 px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm">
                        {{ $item->status == 'diteruskan' ? 'Tugaskan Pekerja' : 'Lihat Detail' }}
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-400 font-medium">Belum ada laporan yang masuk ke bidang Anda.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
