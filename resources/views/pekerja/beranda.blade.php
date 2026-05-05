@extends('layouts.app_pekerja')

@section('konten')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Perintah Kerja</h2>
    <p class="text-sm text-gray-500">Perbarui progres pekerjaan Anda secara berkala.</p>
</div>

@if(session('sukses'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
        <i class="fas fa-check-circle mr-2"></i> {{ session('sukses') }}
    </div>
@endif

<div class="space-y-6">
    @forelse($daftar_tugas as $tugas)
    <!-- Kartu Tugas -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden relative">

        <!-- Status Bar Atas -->
        <div class="px-5 py-3 border-b border-gray-100 flex justify-between items-center
            {{ $tugas->status_tugas == 'selesai' ? 'bg-green-50' : ($tugas->status_tugas == 'terkendala' ? 'bg-red-50' : 'bg-blue-50') }}">
            <span class="font-bold text-sm {{ $tugas->status_tugas == 'selesai' ? 'text-green-700' : 'text-blue-700' }}">
                ID Laporan: {{ $tugas->laporan->id_laporan }}
            </span>
            <span class="text-xs font-bold uppercase tracking-wider px-2 py-1 rounded border
                {{ $tugas->status_tugas == 'selesai' ? 'border-green-300 text-green-600' : 'border-blue-300 text-blue-600' }}">
                {{ $tugas->status_tugas }}
            </span>
        </div>

        <div class="p-5">
            <!-- Lokasi dan Instruksi -->
            <div class="mb-4">
                <p class="text-xs text-gray-400 font-bold uppercase mb-1"><i class="fas fa-map-marker-alt text-red-500 mr-1"></i> Lokasi Pengerjaan</p>
                <p class="font-semibold text-gray-800 text-sm">{{ $tugas->laporan->alamat_map }}</p>
            </div>

            <div class="mb-6">
                <p class="text-xs text-gray-400 font-bold uppercase mb-1"><i class="fas fa-clipboard-list text-yellow-500 mr-1"></i> Instruksi Admin</p>
                <p class="text-gray-600 text-sm bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $tugas->instruksi_tambahan ?? 'Tidak ada instruksi khusus.' }}</p>
            </div>

            <!-- Form Update Progres (Muncul jika belum selesai) -->
            @if($tugas->status_tugas != 'selesai')
            <form action="{{ route('pekerja.tugas.update', $tugas->id) }}" method="POST" class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                @csrf
                <p class="text-sm font-bold text-gray-800 mb-3 border-b border-gray-200 pb-2">Perbarui Progres Lapangan</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Status Lapangan</label>
                        <select name="status_tugas" class="w-full border-gray-300 rounded-lg text-sm p-2 outline-none focus:border-pupr-blue">
                            <option value="dikerjakan" {{ $tugas->status_tugas == 'dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                            <option value="terkendala" {{ $tugas->status_tugas == 'terkendala' ? 'selected' : '' }}>Terkendala Lapangan</option>
                            <option value="selesai">Pekerjaan Selesai (100%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Persentase Progres (%)</label>
                        <input type="number" name="progres_persen" min="0" max="100" value="{{ $tugas->progres_persen }}" class="w-full border border-gray-300 rounded-lg text-sm p-2 outline-none focus:border-pupr-blue">
                    </div>
                </div>

                <!-- Progress Bar Visual -->
                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                    <div class="bg-pupr-blue h-2 rounded-full transition-all duration-500" style="width: {{ $tugas->progres_persen }}%"></div>
                </div>

                <button type="submit" class="w-full bg-pupr-blue hover:bg-blue-800 text-white font-bold py-2.5 rounded-lg shadow transition text-sm">
                    Kirim Laporan Progres
                </button>
            </form>
            @else
            <!-- Tampilan Jika Selesai -->
            <div class="bg-green-50 p-4 rounded-xl border border-green-200 text-center">
                <i class="fas fa-check-circle text-3xl text-green-500 mb-2"></i>
                <p class="font-bold text-green-800">Pekerjaan Telah Selesai</p>
                <p class="text-xs text-green-600">Progres 100%. Laporan telah ditutup.</p>
            </div>
            @endif

        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center">
        <i class="fas fa-clipboard-check text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 font-medium">Belum ada perintah kerja yang ditugaskan kepada Anda saat ini.</p>
    </div>
    @endforelse
</div>
@endsection
