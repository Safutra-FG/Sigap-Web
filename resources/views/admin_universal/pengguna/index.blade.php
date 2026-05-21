@extends('layouts.app')

@section('konten')

<style>
    @keyframes fadeUpMasuk {
        0% { opacity: 0; transform: translateY(15px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animasi-baris {
        animation: fadeUpMasuk 0.5s ease-out forwards;
        opacity: 0;
    }
</style>

<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h2>
        <p class="text-sm text-gray-400 font-medium">Kelola akses akun untuk admin dan pekerja lapangan</p>
    </div>
    <button type="button" onclick="bukaModalTambah()" class="bg-[#1E3A8A] hover:bg-blue-800 text-white px-5 py-2.5 rounded-lg text-sm font-bold flex items-center shadow-md transition">
        <i class="fas fa-user-plus mr-2"></i> Tambah Pengguna Baru
    </button>
</div>

@if(session('sukses'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('sukses') }}",
            timer: 3000,
            showConfirmButton: false,
            customClass: { popup: 'rounded-2xl shadow-xl' }
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#dc2626',
            customClass: { popup: 'rounded-2xl shadow-xl' }
        });
    });
</script>
@endif

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-bold tracking-wider">
            <tr>
                <th class="px-6 py-4">Nama & Username</th>
                <th class="px-6 py-4">Peran</th>
                <th class="px-6 py-4">Fokus Kerja (Bidang/Wilayah)</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @foreach($pengguna as $item)
            <tr class="hover:bg-gray-50 transition animasi-baris" style="animation-delay: {{ $loop->index * 0.08 }}s;">
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
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold">PEKERJA UPTD</span>
                    @endif
                </td>

                <td class="px-6 py-4 font-medium text-gray-600">
                    @if($item->peran == 'admin_bidang')
                        <span class="flex items-center text-blue-600">
                            <i class="fas fa-network-wired w-4 mr-1"></i>
                            {{ !empty($item->id_bidang) && $item->bidang ? $item->bidang->nama_bidang : 'Belum Terhubung' }}
                        </span>
                    @elseif(in_array($item->peran, ['pekerja_bidang', 'pekerja_uptd', 'pekerja_lapangan', 'pekerja']))
                        <span class="flex items-center text-red-600 font-semibold">
                            <i class="fas fa-map-marker-alt w-4 mr-1"></i>
                            {{ !empty($item->kantor_wilayah) ? $item->kantor_wilayah : 'Belum Diatur' }}
                        </span>
                    @else
                        <span class="flex items-center text-gray-400">
                            <i class="fas fa-building w-4 mr-1"></i> Pusat Utama
                        </span>
                    @endif
                </td>

                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $item->status_akun == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ strtoupper($item->status_akun) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center flex justify-center space-x-3">
                    <button type="button" onclick="konfirmasiHapusPengguna('{{ route('admin_universal.pengguna.hapus', $item->id) }}', '{{ $item->nama_lengkap }}')" class="w-8 h-8 rounded bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition shadow-sm flex items-center justify-center" title="Hapus Akun">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="modal-tambah" class="fixed inset-0 z-[100] hidden items-center justify-center">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="tutupModalTambah()"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Tambah Pengguna Baru</h3>
            <button onclick="tutupModalTambah()" class="text-gray-400 hover:text-red-500 transition text-xl">&times;</button>
        </div>

        <form id="form-tambah-pengguna" action="{{ route('admin_universal.pengguna.simpan') }}" method="POST">
            @csrf
            <div class="p-6">
                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-xs font-bold">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:border-pupr-blue outline-none shadow-sm" required>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Nama Pengguna (Username) <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:border-pupr-blue outline-none shadow-sm" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:border-pupr-blue outline-none shadow-sm" required>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Kata Sandi (Min. 6 Karakter) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="input-password" class="w-full border border-gray-300 rounded-lg p-3 pr-10 text-sm focus:border-pupr-blue outline-none shadow-sm" minlength="6" required>
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-pupr-blue transition focus:outline-none">
                                <i class="fas fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-2">
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Peran Sistem <span class="text-red-500">*</span></label>
                        <select name="peran" id="pilihPeran" onchange="cekPeran()" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:border-pupr-blue outline-none shadow-sm font-semibold text-gray-700" required>
                            <option value="">-- Pilih Peran --</option>
                            <option value="admin_universal">Admin Universal</option>

                            @if(count($bidang) > 0)
                                <option value="admin_bidang">Admin Bidang</option>
                                <option value="pekerja_bidang">Pekerja UPTD</option>
                            @else
                                <option value="admin_bidang" disabled class="text-red-400">Admin Bidang (Buat Bidang Dulu!)</option>
                                <option value="pekerja_bidang" disabled class="text-red-400">Pekerja UPTD (Buat Bidang Dulu!)</option>
                            @endif
                        </select>
                    </div>

                    <div id="wadahBidang" class="hidden">
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Pilih Bidang (Khusus Admin Bidang) <span class="text-red-500">*</span></label>
                        <select name="id_bidang" id="inputBidang" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:border-pupr-blue outline-none shadow-sm">
                            <option value="">-- Pilih Bidang --</option>
                            @foreach($bidang as $item_bidang)
                                <option value="{{ $item_bidang->id }}">{{ $item_bidang->nama_bidang }}</option>
                            @endforeach
                        </select>
                        @if(count($bidang) == 0)
                            <p class="text-[10px] text-red-500 mt-1 font-bold animate-pulse"><i class="fas fa-exclamation-circle"></i> Buat bidang dulu di menu Kelola Bidang.</p>
                        @endif
                    </div>

                    <div id="wadahWilayah" class="hidden">
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Desa / Wilayah Operasi UPTD <span class="text-red-500">*</span></label>
                        <select name="kantor_wilayah" id="inputWilayah" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:border-pupr-blue outline-none shadow-sm">
                            <option value="">-- Pilih Desa / Wilayah --</option>
                            <optgroup label="Kecamatan Subang">
                                <option value="Desa Soklat">Desa Soklat</option>
                                <option value="Desa Karanganyar">Desa Karanganyar</option>
                                <option value="Desa Cigadung">Desa Cigadung</option>
                            </optgroup>
                            <optgroup label="Kecamatan Cibogo">
                                <option value="Desa Cibogo">Desa Cibogo</option>
                                <option value="Desa Padaasih">Desa Padaasih</option>
                                <option value="Desa Sumurbarang">Desa Sumurbarang</option>
                            </optgroup>
                            <optgroup label="Kecamatan Jalancagak">
                                <option value="Desa Jalancagak">Desa Jalancagak</option>
                                <option value="Desa Tambakmekar">Desa Tambakmekar</option>
                                <option value="Desa Curugrendeng">Desa Curugrendeng</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="tutupModalTambah()" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-sm font-bold transition shadow-sm">
                    Batal
                </button>
                <button type="submit" id="btn-simpan-pengguna" class="px-8 py-2.5 bg-[#1E3A8A] hover:bg-blue-800 text-white rounded-lg text-sm font-bold transition shadow-md flex items-center justify-center">
                    <i class="fas fa-circle-notch fa-spin hidden mr-2" id="spinner-simpan"></i>
                    <span id="teks-simpan">Simpan Pengguna</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi untuk mengubah status tombol saat disubmit
    document.getElementById('form-tambah-pengguna').addEventListener('submit', function() {
        let btn = document.getElementById('btn-simpan-pengguna');
        let spinner = document.getElementById('spinner-simpan');
        let teks = document.getElementById('teks-simpan');

        // Blokir tombol agar tidak bisa diklik 2x
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');

        // Tampilkan animasi loading dan ubah teks
        spinner.classList.remove('hidden');
        teks.innerText = 'Menyimpan...';
    });

    function bukaModalTambah() {
        document.getElementById('modal-tambah').classList.remove('hidden');
        document.getElementById('modal-tambah').classList.add('flex');
    }
    function tutupModalTambah() {
        document.getElementById('modal-tambah').classList.add('hidden');
        document.getElementById('modal-tambah').classList.remove('flex');
    }

    function togglePassword() {
        const inputPwd = document.getElementById('input-password');
        const eyeIcon = document.getElementById('eye-icon');
        if (inputPwd.type === 'password') {
            inputPwd.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            inputPwd.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

@if($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() { bukaModalTambah(); });
</script>
@endif

<form id="form-hapus-pengguna-rahasia" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function cekPeran() {
        var peran = document.getElementById('pilihPeran').value;
        var wadahBidang = document.getElementById('wadahBidang');
        var inputBidang = document.getElementById('inputBidang');
        var wadahWilayah = document.getElementById('wadahWilayah');
        var inputWilayah = document.getElementById('inputWilayah');

        if(peran === 'admin_bidang') {
            wadahBidang.classList.remove('hidden');
            inputBidang.setAttribute('required', 'required');
            wadahWilayah.classList.add('hidden');
            inputWilayah.removeAttribute('required');
            inputWilayah.value = "";
        } else if(peran === 'pekerja_bidang' || peran === 'pekerja') {
            wadahWilayah.classList.remove('hidden');
            inputWilayah.setAttribute('required', 'required');
            wadahBidang.classList.add('hidden');
            inputBidang.removeAttribute('required');
            inputBidang.value = "";
        } else {
            wadahBidang.classList.add('hidden');
            inputBidang.removeAttribute('required');
            inputBidang.value = "";
            wadahWilayah.classList.add('hidden');
            inputWilayah.removeAttribute('required');
            inputWilayah.value = "";
        }
    }

    cekPeran();

    function konfirmasiHapusPengguna(urlDelete, namaUser) {
        Swal.fire({
            title: 'Hapus Akun?',
            html: `Apakah Anda yakin ingin menghapus akun <b>${namaUser}</b>? Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#f3f4f6',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: '<span class="text-gray-700">Batal</span>',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl shadow-2xl p-6 border border-gray-100',
                title: 'text-xl font-bold text-gray-800 mb-2',
                htmlContainer: 'text-sm text-gray-500 mb-6',
                icon: 'text-red-500 border-red-100 bg-red-50 w-20 h-20 text-4xl mb-4',
                confirmButton: 'w-full sm:w-auto px-8 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition shadow-md',
                cancelButton: 'w-full sm:w-auto px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition',
                actions: 'flex flex-col-reverse sm:flex-row gap-3 w-full justify-center'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const formHapus = document.getElementById('form-hapus-pengguna-rahasia');
                formHapus.action = urlDelete;
                formHapus.submit();
            }
        });
    }
</script>
@endsection
