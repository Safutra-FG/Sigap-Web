@extends('layouts.app')

@section('konten')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-pupr-blue">Kelola Bidang</h2>
    <button onclick="toggleModal('modalTambah')" class="bg-pupr-blue text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-opacity-90 transition">
        <i class="fas fa-plus mr-2"></i> Tambah Bidang
    </button>
</div>

@if(session('sukses'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm">
        {{ session('sukses') }}
    </div>
@endif

<div class="bg-pupr-yellow rounded-xl p-1 shadow-lg">
    <div class="bg-white rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-pupr-yellow text-pupr-blue font-bold uppercase text-sm">
                <tr>
                    <th class="px-6 py-4">No. ID</th>
                    <th class="px-6 py-4">Nama Bidang</th>
                    <th class="px-6 py-4">Kepala Bidang</th>
                    <th class="px-6 py-4">Jumlah UPTD</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($semua_bidang as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-pupr-blue">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-semibold">{{ $item->nama_bidang }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $item->kepala_bidang ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">{{ $item->jumlah_uptd }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $item->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ strtoupper($item->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center flex justify-center space-x-2">
                        <button onclick="editBidang({{ $item }})" class="text-blue-500 hover:text-blue-700"><i class="fas fa-edit"></i></button>
                        <form action="{{ route('admin_universal.bidang.hapus', $item->id) }}" method="POST" onsubmit="return confirm('Hapus bidang ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modalTambah" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-8">
        <h3 class="text-xl font-bold text-pupr-blue mb-6">Tambah Bidang Baru</h3>
        <form action="{{ route('admin_universal.bidang.simpan') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Nama Bidang</label>
                <input type="text" name="nama_bidang" class="w-full border rounded-lg p-2" placeholder="Contoh: Bina Marga" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Kepala Bidang</label>
                <input type="text" name="kepala_bidang" class="w-full border rounded-lg p-2" placeholder="Nama Pejabat">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2">Status</label>
                <select name="status" class="w-full border rounded-lg p-2">
                    <option value="aktif">Aktif</option>
                    <option value="non-aktif">Non-Aktif</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="toggleModal('modalTambah')" class="px-4 py-2 text-gray-500 font-bold">Batal</button>
                <button type="submit" class="bg-pupr-blue text-white px-6 py-2 rounded-lg font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }
    // Fungsi edit bisa dikembangkan dengan modal serupa atau halaman terpisah
</script>
@endsection
