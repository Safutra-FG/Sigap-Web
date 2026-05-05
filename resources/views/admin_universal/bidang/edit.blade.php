@extends('layouts.app')

@section('konten')
<div class="max-w-4xl mx-auto pb-10">

    <!-- Header Halaman -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin_universal.bidang') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-gray-500 hover:text-pupr-blue hover:bg-blue-50 transition shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 mb-1">Edit Detail Bidang</h2>
            <p class="text-sm text-gray-500">Perbarui informasi, status, atau ikon dari bidang infrastruktur ini.</p>
        </div>
    </div>

    <!-- Kotak Formulir -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800"><i class="fas fa-pen-square text-pupr-blue mr-2"></i> Formulir Perubahan Data</h3>
        </div>

        <div class="p-6">
            <form action="{{ route('admin_universal.bidang.perbarui', $bidang->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- Wajib untuk proses Update di Laravel -->

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kode Bidang -->
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Kode Bidang <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_bidang" value="{{ $bidang->kode_bidang }}" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:border-pupr-blue outline-none shadow-sm uppercase font-mono" required>
                    </div>

                    <!-- Nama Bidang -->
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Nama Bidang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_bidang" value="{{ $bidang->nama_bidang }}" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:border-pupr-blue outline-none shadow-sm" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Pemilihan Ikon -->
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Ikon Representasi <span class="text-red-500">*</span></label>
                        <select name="ikon" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:border-pupr-blue outline-none shadow-sm font-semibold text-gray-700" required>
                            <option value="fas fa-road" {{ $bidang->ikon == 'fas fa-road' ? 'selected' : '' }}>Ikon Jalan Raya</option>
                            <option value="fas fa-bridge" {{ $bidang->ikon == 'fas fa-bridge' ? 'selected' : '' }}>Ikon Jembatan</option>
                            <option value="fas fa-water" {{ $bidang->ikon == 'fas fa-water' ? 'selected' : '' }}>Ikon Air (Sumber Daya Air)</option>
                            <option value="fas fa-building" {{ $bidang->ikon == 'fas fa-building' ? 'selected' : '' }}>Ikon Gedung (Cipta Karya)</option>
                            <option value="fas fa-map" {{ $bidang->ikon == 'fas fa-map' ? 'selected' : '' }}>Ikon Peta (Tata Ruang)</option>
                            <option value="fas fa-tools" {{ $bidang->ikon == 'fas fa-tools' ? 'selected' : '' }}>Ikon Perkakas / Konstruksi</option>
                        </select>
                    </div>

                    <!-- Status Aktif & Peringatan Cascade -->
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Status Bidang <span class="text-red-500">*</span></label>
                        <select name="status_aktif" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:border-pupr-blue outline-none shadow-sm font-semibold text-gray-700" required>
                            <option value="1" {{ $bidang->status_aktif == 1 ? 'selected' : '' }}>Aktif Beroperasi</option>
                            <option value="0" {{ $bidang->status_aktif == 0 ? 'selected' : '' }}>Dinonaktifkan</option>
                        </select>
                        <p class="text-[10px] text-red-500 font-medium mt-1.5 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i> Perhatian: Jika dinonaktifkan, seluruh akun pegawai di bidang ini akan ikut dinonaktifkan!
                        </p>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="mb-8">
                    <label class="text-[10px] font-bold text-gray-500 uppercase mb-1.5 block">Deskripsi Tugas Bidang <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" rows="4" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:border-pupr-blue outline-none resize-none shadow-sm" required>{{ $bidang->deskripsi }}</textarea>
                </div>

                <!-- Tombol Aksi -->
                <div class="border-t border-gray-100 pt-6 flex justify-end gap-3">
                    <a href="{{ route('admin_universal.bidang') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-bold transition shadow-sm">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-2.5 bg-[#1E3A8A] hover:bg-blue-800 text-white rounded-lg text-sm font-bold transition shadow-md flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
