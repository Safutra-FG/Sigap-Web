@extends('layouts.app')

@section('konten')
<div class="max-w-7xl mx-auto pb-10">

    <!-- HEADER & BREADCRUMB -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-1">Kelola Bidang Infrastruktur</h2>
            <p class="text-sm text-gray-500">Pantau dan kelola struktur organisasi serta distribusi laporan per bidang.</p>
        </div>
        <button onclick="toggleModalBidang()" class="bg-yellow-400 hover:bg-yellow-500 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition shadow-md flex items-center justify-center shrink-0 focus:outline-none">
            <i class="fas fa-plus mr-2"></i> Tambah Bidang Baru
        </button>
    </div>

    <!-- KARTU STATISTIK BIDANG (Menampilkan max 5 bidang teratas) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        {{-- Kita looping 5 data bidang untuk kartu atas --}}
        @foreach($semua_bidang->take(5) as $stat)
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm flex flex-col relative overflow-hidden transition hover:shadow-md hover:border-blue-100">
            <!-- Icon & ID -->
            <div class="flex justify-between items-center mb-4">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-pupr-blue flex items-center justify-center text-lg shadow-sm border border-blue-100/50">
                    <i class="{{ $stat->ikon ?? 'fas fa-building' }}"></i>
                </div>
                <span class="text-[9px] font-extrabold text-gray-400 tracking-wider uppercase">ID: {{ $stat->kode_bidang ?? 'B-'.$stat->id }}</span>
            </div>

            <h4 class="font-bold text-gray-800 text-sm mb-1 line-clamp-1">{{ $stat->nama_bidang }}</h4>

            <div class="flex justify-between items-end mt-auto pt-2">
                <div>
                    <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stat->laporans_count ?? 0 }}</p>
                    <p class="text-[10px] text-gray-500 font-medium mt-1">Total Laporan</p>
                </div>
                <span class="bg-green-50 text-green-600 text-[10px] font-bold px-2 py-1 rounded-md border border-green-100 flex items-center">
                    <i class="fas fa-arrow-trend-up mr-1 text-[8px]"></i> Aktif
                </span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- TABEL DAFTAR BIDANG -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <!-- Header Tabel -->
        <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-white">
            <h3 class="font-bold text-gray-800 text-lg">Daftar Bidang Aktif</h3>

            <div class="flex gap-2 w-full md:w-auto">
                <!-- Form Filter Status (Otomatis Submit saat dipilih) -->
                <form action="{{ route('admin_universal.bidang') }}" method="GET" class="flex-1 md:flex-none">
                    <select name="status" onchange="this.form.submit()" class="w-full md:w-auto border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-bold bg-white hover:bg-gray-50 focus:border-pupr-blue outline-none transition cursor-pointer appearance-none">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Hanya Tidak Aktif</option>
                    </select>
                </form>

                <!-- Tombol Unduh / Export -->
                <a href="{{ route('admin_universal.bidang.export') }}" class="border border-gray-200 text-gray-600 px-3 py-2 rounded-lg text-sm font-bold hover:bg-blue-50 hover:text-pupr-blue hover:border-blue-200 transition flex items-center justify-center shadow-sm" title="Unduh Data Bidang (CSV)">
                    <i class="fas fa-download"></i>
                </a>
            </div>
        </div>

        <!-- Isi Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-gray-50/50 text-[10px] uppercase tracking-widest text-gray-400 border-b border-gray-100">
                        <th class="py-4 px-6 font-bold w-[5%]">NO</th>
                        <th class="py-4 px-6 font-bold w-[25%]">NAMA BIDANG</th>
                        <th class="py-4 px-6 font-bold w-[40%]">DESKRIPSI</th>
                        <th class="py-4 px-6 font-bold text-center w-[10%]">LAPORAN</th>
                        <th class="py-4 px-6 font-bold text-center w-[10%]">STATUS</th>
                        <th class="py-4 px-6 font-bold text-center w-[10%]">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-100">
                    @forelse($semua_bidang as $index => $item)
                    <tr class="hover:bg-blue-50/30 transition group border-b border-gray-50 last:border-0">

                        <!-- 1. KOLOM NOMOR -->
                        <td class="py-4 px-6 text-gray-500 font-medium text-xs">
                            {{ str_pad($semua_bidang->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                        </td>

                        <!-- 2. KOLOM NAMA BIDANG -->
                        <td class="py-4 px-6">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100/50 text-[#1E3A8A] flex items-center justify-center mr-3 shrink-0">
                                    {{-- Menggunakan kolom ikon yang sudah kita tambahkan ke database --}}
                                    <i class="{{ $item->ikon ?? 'fas fa-building' }} text-xs"></i>
                                </div>
                                <span class="font-bold text-gray-800 text-sm">{{ $item->nama_bidang }}</span>
                            </div>
                        </td>

                        <!-- 3. KOLOM DESKRIPSI -->
                        <td class="py-4 px-6 text-xs text-gray-500 pr-8">
                            <p class="line-clamp-2">{{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                        </td>

                        <!-- 4. KOLOM LAPORAN (Menampilkan angka nyata, bukan ikon aksi) -->
                        <td class="py-4 px-6 text-center">
                            <span class="font-bold text-[#1E3A8A] bg-blue-50 px-3 py-1 rounded-md text-xs border border-blue-100 shadow-sm">
                                {{-- Menampilkan data nyata hasil dari withCount di Controller --}}
                                {{ $item->laporans_count ?? 0 }}
                            </span>
                        </td>

                        <!-- 5. KOLOM STATUS (Konsisten menggunakan status_aktif) -->
                        <td class="py-4 px-6 text-center">
                            @if($item->status_aktif)
                                <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-bold shadow-sm">
                                    Aktif
                                </span>
                            @else
                                <span class="bg-gray-50 text-gray-500 border border-gray-200 px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-bold shadow-sm">
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        <!-- 6. KOLOM AKSI -->
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center space-x-2">

                                {{-- Tombol Edit (Akan mengarahkan ke halaman baru) --}}
                                <a href="{{ route('admin_universal.bidang.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-100 transition" title="Edit Bidang">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>

                                {{-- Form Rahasia untuk Hapus Data --}}
                                <form id="form-hapus-{{ $item->id }}" action="{{ route('admin_universal.bidang.hapus', $item->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                {{-- Tombol Hapus (Akan memicu pop-up SweetAlert) --}}
                                <button type="button" onclick="konfirmasiHapus({{ $item->id }}, '{{ $item->nama_bidang }}')" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-400 hover:text-red-500 hover:bg-red-50 border border-transparent hover:border-red-100 transition" title="Hapus Bidang">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p class="font-medium text-sm text-gray-400">Belum ada data bidang infrastruktur.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination Dinamis -->
        <div class="p-5 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between text-sm text-gray-500 gap-4 bg-white">
            @if($semua_bidang->total() > 0)
                <div>
                    Menampilkan <span class="font-bold text-gray-800">{{ $semua_bidang->firstItem() }}-{{ $semua_bidang->lastItem() }}</span> dari <span class="font-bold text-gray-800">{{ $semua_bidang->total() }}</span> Bidang
                </div>
                {{ $semua_bidang->appends(request()->query())->links('pagination::tailwind') }} <!-- Gunakan default tailwind laravel agar rapi -->
            @endif
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL TAMBAH BIDANG BARU -->
<!-- ============================================== -->
<div id="modalBidang" class="fixed inset-0 z-[4000] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="toggleModalBidang()"></div>

    <div class="bg-white shadow-2xl w-full max-w-2xl z-10 overflow-hidden transform transition-all rounded-2xl flex flex-col">
        <!-- Header Modal -->
        <div class="bg-[#102A63] text-white px-6 py-4 flex items-center justify-between shadow-md">
            <div class="flex items-center">
                <i class="fas fa-layer-group text-xl mr-3"></i>
                <h3 class="text-lg font-bold">Tambah Bidang Baru</h3>
            </div>
            <button type="button" onclick="toggleModalBidang()" class="text-white hover:text-red-400 transition focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Form Tambah Bidang -->
        <div class="p-6 overflow-y-auto">
            <form action="{{ route('admin_universal.bidang.simpan') }}" method="POST">
                @csrf

                <!-- Tampilkan Pesan Error Global Jika Ada -->
                @if($errors->any())
                    <div class="mb-5 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-xs font-bold">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <!-- Kode Bidang -->
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Kode Bidang <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_bidang" placeholder="Contoh: B-06" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-pupr-blue outline-none shadow-sm uppercase font-mono" required>
                    </div>

                    <!-- Nama Bidang -->
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Nama Bidang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_bidang" placeholder="Contoh: Bina Konstruksi" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-pupr-blue outline-none shadow-sm" required>
                    </div>
                </div>

                <!-- Pemilihan Ikon -->
                <div class="mb-5">
                    <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Ikon Representasi <span class="text-red-500">*</span></label>
                    <select name="ikon" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-pupr-blue outline-none shadow-sm font-semibold text-gray-700" required>
                        <option value="fas fa-road">Ikon Jalan Raya</option>
                        <option value="fas fa-bridge">Ikon Jembatan</option>
                        <option value="fas fa-water">Ikon Air (Sumber Daya Air)</option>
                        <option value="fas fa-building">Ikon Gedung (Cipta Karya)</option>
                        <option value="fas fa-map">Ikon Peta (Tata Ruang)</option>
                        <option value="fas fa-tools">Ikon Perkakas / Konstruksi</option>
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1">Pilih ikon yang paling mewakili bidang ini.</p>
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Deskripsi Tugas Bidang <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" rows="3" placeholder="Tuliskan fokus pekerjaan atau wewenang dari bidang ini..." class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:border-pupr-blue outline-none resize-none shadow-sm" required></textarea>
                </div>

                <!-- Tombol Submit -->
                <div class="border-t border-gray-100 pt-5 flex justify-end gap-3">
                    <button type="button" onclick="toggleModalBidang()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#1a73e8] hover:bg-blue-700 text-white rounded-lg text-sm font-bold transition shadow-md flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan Bidang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk memunculkan dan menyembunyikan Pop-up Tambah Bidang
    function toggleModalBidang() {
        const modal = document.getElementById('modalBidang');
        modal.classList.toggle('hidden');
    }
</script>

<!-- Memuat Library SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function konfirmasiHapus(id, namaBidang) {
        Swal.fire({
            title: 'Hapus Bidang?',
            html: `Apakah Anda yakin ingin menghapus bidang <b>${namaBidang}</b>? Data yang dihapus tidak dapat dikembalikan.`,
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
                document.getElementById('form-hapus-' + id).submit();
            }
        });
    }
</script>

<!-- Notifikasi Flash Message dengan SweetAlert2 & Auto-Open Modal -->
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

@if($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        toggleModalBidang(); // Otomatis buka modal jika ada error validasi
    });
</script>
@endif

@endsection
