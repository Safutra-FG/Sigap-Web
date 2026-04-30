@extends('layouts.app')

@section('konten')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center">
        <a href="{{ route('admin_universal.laporan') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-pupr-blue transition shadow-sm mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Pelaporan Masyarakat</h2>
            <p class="text-sm text-gray-400 font-medium">Validasi dan tindak lanjut laporan {{ $laporan->id_laporan }}</p>
        </div>
    </div>

    <div class="px-4 py-2 rounded-lg font-bold text-sm border
        {{ $laporan->status == 'pending' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' :
          ($laporan->status == 'ditolak' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-blue-50 text-blue-700 border-blue-200') }}">
        STATUS: {{ strtoupper($laporan->status) }}
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-8">

    <div class="grid grid-cols-3 gap-6 mb-8 border-b border-gray-100 pb-6">
        <div>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">ID Report</p>
            <p class="text-gray-800 font-bold">{{ $laporan->id_laporan }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Tanggal Laporan</p>
            <p class="text-gray-800 font-bold">{{ \Carbon\Carbon::parse($laporan->created_at)->format('d M, Y') }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Nama Pelapor</p>
            <p class="text-gray-800 font-bold">{{ $laporan->pelapor->nama_lengkap ?? 'Anonim' }}</p>
        </div>
    </div>

    <div class="mb-8">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2"><i class="fas fa-align-left mr-1"></i> Deskripsi Pelaporan</p>
        <p class="text-gray-700 text-sm leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $laporan->deskripsi_laporan }}</p>
    </div>

    <div class="mb-8">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2"><i class="fas fa-map-marker-alt mr-1"></i> Peta Lokasi</p>
        <div class="w-full h-64 bg-gray-200 rounded-xl overflow-hidden border border-gray-200 relative flex items-center justify-center">
            <div class="absolute inset-0 opacity-50" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
            <div class="z-10 text-center">
                <i class="fas fa-map-pin text-4xl text-red-500 drop-shadow-md mb-2"></i>
                <p class="text-sm font-bold text-gray-700 bg-white/90 px-3 py-1 rounded-full shadow">{{ $laporan->alamat_map }}</p>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2 font-mono">Koordinat: {{ $laporan->lokasi_gps }}</p>
    </div>

    <div class="mb-10">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2"><i class="fas fa-camera mr-1"></i> Foto Bukti Laporan</p>
        <div class="flex space-x-4 overflow-x-auto pb-2">
            <div class="w-40 h-28 bg-gray-100 rounded-lg border border-gray-200 flex flex-shrink-0 items-center justify-center text-gray-400">
                <i class="fas fa-image text-2xl"></i>
            </div>
        </div>
    </div>

    @if($laporan->status == 'pending')
    <div class="flex justify-end space-x-4 border-t border-gray-100 pt-6">
        <button onclick="toggleModal('modalTolak')" class="bg-red-50 text-red-600 hover:bg-red-500 hover:text-white px-6 py-3 rounded-xl font-bold shadow-sm transition flex items-center">
            <i class="fas fa-times-circle mr-2"></i> Tolak Laporan
        </button>
        <button onclick="toggleModal('modalDisposisi')" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-xl font-bold shadow-md hover:shadow-lg transition flex items-center">
            <i class="fas fa-share-square mr-2"></i> Disposisi ke Bidang
        </button>
    </div>
    @endif
</div>

<div id="modalDisposisi" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 transition-all">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-xl font-bold text-gray-800">Buat Disposisi Laporan</h3>
            <button onclick="toggleModal('modalDisposisi')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <p class="text-sm text-gray-500 mb-6">Pilih bidang yang akan menangani laporan ini beserta catatan instruksinya.</p>

        <form action="{{ route('admin_universal.laporan.disposisi', $laporan->id) }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-700 mb-2">Pilih Bidang Tujuan:</label>
                <select name="id_bidang_tujuan" class="w-full border border-gray-200 rounded-lg p-3 text-sm outline-none focus:border-pupr-blue" required>
                    <option value="">-- Pilih Bidang --</option>
                    @foreach($bidang_aktif as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-700 mb-2">Catatan Tambahan (Opsional):</label>
                <textarea name="catatan_disposisi" rows="3" class="w-full border border-gray-200 rounded-lg p-3 text-sm outline-none focus:border-pupr-blue" placeholder="Tambahkan instruksi khusus..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="toggleModal('modalDisposisi')" class="px-5 py-2.5 text-gray-500 font-bold text-sm hover:bg-gray-100 rounded-lg transition">Batal</button>
                <button type="submit" class="bg-green-500 text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-md hover:bg-green-600 transition">Kirim Disposisi</button>
            </div>
        </form>
    </div>
</div>

<div id="modalTolak" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 transition-all">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="flex justify-between items-center mb-4 border-b pb-4">
            <h3 class="text-xl font-bold text-red-600"><i class="fas fa-exclamation-circle mr-2"></i> Tolak Laporan</h3>
            <button onclick="toggleModal('modalTolak')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form action="{{ route('admin_universal.laporan.tolak', $laporan->id) }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-700 mb-2">Alasan Penolakan (Wajib Diisi):</label>
                <textarea name="alasan_penolakan" rows="4" class="w-full border border-gray-200 rounded-lg p-3 text-sm outline-none focus:border-red-500" placeholder="Berikan alasan mengapa laporan ini ditolak (misal: data tidak valid, bukan wewenang PUPR...)" required></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="toggleModal('modalTolak')" class="px-5 py-2.5 text-gray-500 font-bold text-sm hover:bg-gray-100 rounded-lg transition">Batal</button>
                <button type="submit" class="bg-red-500 text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-md hover:bg-red-600 transition">Ya, Tolak Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }
</script>
@endsection
