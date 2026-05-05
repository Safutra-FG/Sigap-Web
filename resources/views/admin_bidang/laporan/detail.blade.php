@extends('layouts.app_bidang')

@section('konten')
<div class="mb-6 flex items-center">
    <a href="{{ route('admin_bidang.laporan') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-pupr-blue transition shadow-sm mr-4">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Detail & Penugasan Pekerja</h2>
        <p class="text-sm text-gray-400 font-medium">Laporan {{ $laporan->id_laporan }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h3 class="text-lg font-bold text-pupr-blue mb-6 border-b pb-4"><i class="fas fa-file-alt mr-2"></i> Informasi Keluhan</h3>

        <div class="mb-6">
            <p class="text-xs text-gray-400 font-bold uppercase mb-1">Deskripsi Masalah</p>
            <p class="text-gray-700 bg-gray-50 p-4 rounded-xl text-sm">{{ $laporan->deskripsi_laporan }}</p>
        </div>

        <div class="mb-6">
            <p class="text-xs text-gray-400 font-bold uppercase mb-1">Lokasi Kejadian (Koordinat: {{ $laporan->lokasi_gps }})</p>
            <p class="text-gray-800 font-medium">{{ $laporan->alamat_map }}</p>
        </div>

        <div class="w-full h-48 bg-gray-200 rounded-xl flex items-center justify-center relative overflow-hidden border border-gray-200">
            <div class="absolute inset-0 opacity-50" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
            <i class="fas fa-map-pin text-4xl text-red-500 z-10 drop-shadow-md"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h3 class="text-lg font-bold text-pupr-blue mb-6 border-b pb-4"><i class="fas fa-hard-hat mr-2"></i> Form Penugasan</h3>

        @if($laporan->status == 'diteruskan')
        <form action="{{ route('admin_bidang.laporan.tugaskan', $laporan->id) }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-700 mb-2">Pilih Pekerja / Mandor UPTD:</label>
                <select name="id_pekerja" class="w-full border border-gray-200 rounded-lg p-3 text-sm outline-none focus:border-pupr-blue" required>
                    <option value="">-- Pilih Tim Lapangan --</option>
                    @foreach($pekerja as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_lengkap }} ({{ $p->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-700 mb-2">Instruksi Kerja (Opsional):</label>
                <textarea name="instruksi_tambahan" rows="4" class="w-full border border-gray-200 rounded-lg p-3 text-sm outline-none focus:border-pupr-blue" placeholder="Masukkan detail instruksi peralatan atau material yang harus disiapkan..."></textarea>
            </div>

            <button type="submit" class="w-full bg-pupr-yellow hover:bg-yellow-500 text-white font-bold py-3.5 rounded-xl shadow-md transition-all">
                Kirim Perintah Kerja
            </button>
        </form>
        @else
        <div class="text-center py-10">
            <div class="w-16 h-16 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-check"></i>
            </div>
            <p class="font-bold text-gray-800">Pekerja Telah Ditugaskan</p>
            <p class="text-xs text-gray-500 mt-2">Laporan ini sedang dalam tahap pengerjaan lapangan.</p>
        </div>
        @endif
    </div>
</div>
@endsection
