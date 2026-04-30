@extends('layouts.app')

@section('konten')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h2>
        <p class="text-sm text-gray-400 font-medium">Kelola akses akun untuk admin dan pekerja lapangan</p>
    </div>
    <button onclick="toggleModal('modalTambah')" class="bg-pupr-blue text-white px-5 py-2.5 rounded-lg font-bold shadow-md hover:bg-opacity-90 hover:shadow-lg transition flex items-center">
        <i class="fas fa-user-plus mr-2"></i> Tambah Pengguna Baru
    </button>
</div>

@if(session('sukses'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center">
        <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('sukses') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex items-center">
        <i class="fas fa-exclamation-triangle mr-3 text-lg"></i> {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-bold tracking-wider">
            <tr>
                <th class="px-6 py-4">Nama & Username</th>
                <th class="px-6 py-4">Peran</th>
                <th class="px-6 py-4">Bidang/Departemen</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @foreach($pengguna as $item)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <p class="font-bold text-gray-800">{{ $item->nama_lengkap }}</p>
                    <p class="text-xs text-gray-400">{{ $item->username }} | {{ $item->email }}</p>
                </td>
                <td class="px-6 py-4">
                    @if($item->peran == 'admin_universal')
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-[10px] font-bold">ADMIN UNIVERSAL</span>
                    @elseif($item->peran == 'admin_bidang')
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-bold">ADMIN BIDANG</span>
                    @else
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold">KARYAWAN UPTD</span>
                    @endif
                </td>
                <td class="px-6 py-4 font-medium text-gray-600">
                    {{ $item->bidang ? $item->bidang->nama_bidang : '-' }}
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $item->status_akun == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ strtoupper($item->status_akun) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center flex justify-center space-x-3">
                    <button type="button" onclick="bukaModalHapus('{{ route('admin_universal.pengguna.hapus', $item->id) }}', '{{ $item->nama_lengkap }}')" class="w-8 h-8 rounded bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition shadow-sm flex items-center justify-center" title="Hapus Akun">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="modalTambah" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 transition-all">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-xl font-bold text-gray-800">Tambah Pengguna Baru</h3>
            <button onclick="toggleModal('modalTambah')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>

        <form action="{{ route('admin_universal.pengguna.simpan') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none focus:border-pupr-blue" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Pengguna</label>
                    <input type="text" name="username" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none focus:border-pupr-blue" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none focus:border-pupr-blue" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Kata Sandi</label>
                <input type="password" name="password" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none focus:border-pupr-blue" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Peran</label>
                    <select name="peran" id="pilihPeran" onchange="cekPeran()" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none focus:border-pupr-blue">
                        <option value="admin_universal">Admin Universal</option>
                        <option value="admin_bidang">Admin Bidang</option>
                        <option value="pekerja_bidang">Karyawan / Pekerja UPTD</option>
                    </select>
                </div>
                <div id="wadahBidang" class="hidden">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Pilih Bidang</label>
                    <select name="id_bidang" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm outline-none focus:border-pupr-blue">
                        <option value="">-- Pilih Bidang --</option>
                        @foreach($bidang as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t">
                <button type="button" onclick="toggleModal('modalTambah')" class="px-5 py-2.5 text-gray-500 font-bold text-sm hover:bg-gray-100 rounded-lg transition">Batal</button>
                <button type="submit" class="bg-pupr-blue text-white px-5 py-2.5 rounded-lg font-bold text-sm shadow-md hover:bg-opacity-90 transition">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>

<div id="modalHapus" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center transform transition-transform scale-100">
        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4 text-red-500 text-3xl">
            <i class="fas fa-exclamation-triangle"></i>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus Akun?</h3>
        <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus akun <span id="namaPenggunaHapus" class="font-bold text-gray-800"></span>? Tindakan ini tidak dapat dibatalkan.</p>

        <form id="formHapusPengguna" method="POST" action="">
            @csrf @method('DELETE')
            <div class="flex justify-center space-x-3">
                <button type="button" onclick="tutupModalHapus()" class="w-full px-5 py-3 bg-gray-100 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="w-full px-5 py-3 bg-red-500 text-white font-bold text-sm rounded-xl hover:bg-red-600 transition shadow-md">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsionalitas Modal Tambah
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function cekPeran() {
        var peran = document.getElementById('pilihPeran').value;
        var wadahBidang = document.getElementById('wadahBidang');

        if(peran == 'admin_bidang' || peran == 'pekerja_bidang') {
            wadahBidang.classList.remove('hidden');
        } else {
            wadahBidang.classList.add('hidden');
        }
    }
    cekPeran();

    // Fungsionalitas Modal Hapus Custom
    function bukaModalHapus(urlDelete, namaUser) {
        document.getElementById('namaPenggunaHapus').innerText = namaUser;
        document.getElementById('formHapusPengguna').action = urlDelete;
        document.getElementById('modalHapus').classList.remove('hidden');
    }

    function tutupModalHapus() {
        document.getElementById('modalHapus').classList.add('hidden');
    }
</script>
@endsection
