@extends('layouts.app')

@section('konten')
<!-- HEADER HALAMAN & TOMBOL EKSPOR -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <!-- Judul sudah diperbarui sesuai permintaan -->
        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Statistik</h2>
        <p class="text-sm text-gray-500">Pantau tren bulanan, sebaran bidang, dan status penyelesaian laporan infrastruktur.</p>
    </div>

    <!-- Tombol Ekspor PDF (Ditambahkan ID untuk efek loading) -->
    <button id="btn-cetak-pdf" onclick="eksporKePDF()" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition flex items-center shadow-md hover:shadow-lg active:scale-95">
        <i class="fas fa-file-pdf mr-2 text-lg"></i> Unduh Laporan PDF
    </button>
</div>

<!-- BUNGKUSAN AREA YANG AKAN DICETAK KE PDF -->
<div id="area-statistik" class="bg-[#F8FAFC] p-2 rounded-xl">

    <!-- KOP SURAT RESMI (Hanya muncul di dalam file PDF) -->
    <div id="kop-surat-pdf" class="hidden mb-6 border-b-4 border-gray-900 pb-4 items-center justify-between">
        <div class="flex items-center gap-5">
            <!-- Mengambil logo PUPR dari folder gambarmu -->
            <img src="{{ asset('gambar/puprsigap1.png') }}" alt="Logo PUPR" class="h-16 object-contain">
            <div class="text-left">
                <h1 class="text-xl font-extrabold text-gray-900 uppercase leading-tight">Dinas Pekerjaan Umum dan Penataan Ruang</h1>
                <h2 class="text-lg font-bold text-gray-700 uppercase leading-tight">Kabupaten Subang</h2>
                <p class="text-xs text-gray-500 mt-1">Laporan Rekapitulasi Statistik Infrastruktur SIGAP - Tahun {{ $tahun ?? date('Y') }}</p>
            </div>
        </div>
        <div class="text-right text-xs text-gray-500 font-medium">
            <p>Dicetak oleh: <strong>{{ auth()->user()->nama_lengkap ?? 'Muhammad Adrian Taofik' }}</strong></p>
            <p>Waktu: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB</p>
        </div>
    </div>

    <!-- GRAFIK BAR & DONUT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- GRAFIK BAR (KIRI) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Grafik Laporan per Bulan</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tren Aktivitas Tahun {{ $tahun ?? date('Y') }}</p>
                </div>

                <!-- Filter Tahun Dinamis (Akan disembunyikan saat cetak PDF) -->
                <form action="{{ route('admin_universal.statistik') }}" method="GET" id="formFilterTahun" class="elemen-non-cetak">
                    <select name="tahun" onchange="document.getElementById('formFilterTahun').submit()" class="text-sm border border-gray-200 rounded-lg text-gray-600 font-medium py-1.5 px-3 bg-gray-50 hover:bg-gray-100 cursor-pointer outline-none focus:border-pupr-blue">
                        <option value="{{ date('Y') }}" {{ ($tahun ?? date('Y')) == date('Y') ? 'selected' : '' }}>Tahun {{ date('Y') }}</option>
                        <option value="{{ date('Y') - 1 }}" {{ ($tahun ?? date('Y')) == (date('Y') - 1) ? 'selected' : '' }}>Tahun {{ date('Y') - 1 }}</option>
                        <option value="{{ date('Y') - 2 }}" {{ ($tahun ?? date('Y')) == (date('Y') - 2) ? 'selected' : '' }}>Tahun {{ date('Y') - 2 }}</option>
                    </select>
                </form>
            </div>

            <div class="h-64 flex items-end justify-between px-2 pb-6 border-b border-gray-100 relative">
                <div class="absolute bottom-0 w-full flex justify-between text-[10px] md:text-xs font-bold text-gray-400 px-2">
                    @foreach($grafik_bulan ?? [] as $gb)
                        <span class="w-8 text-center">{{ $gb['nama'] }}</span>
                    @endforeach
                </div>

                @foreach($grafik_bulan ?? [] as $gb)
                    @php
                        $max = $max_bulan ?? 0;
                        $tinggiVisual = $max > 0 ? (($gb['total'] / $max) * 85) : 0;
                    @endphp
                    <div class="w-6 md:w-8 relative group flex justify-center items-end" style="height: 100%;">
                        <div class="w-full rounded-t-sm transition-all duration-700 ease-out cursor-pointer hover:bg-blue-600"
                             style="height: {{ $tinggiVisual }}%; background-color: {{ $gb['total'] > 0 ? '#3B82F6' : '#F3F4F6' }};">

                            <!-- Tooltip Angka saat di web -->
                            <div class="opacity-0 group-hover:opacity-100 absolute bottom-[calc({{ $tinggiVisual }}%+10px)] left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-[10px] py-1 px-2 rounded z-10 whitespace-nowrap pointer-events-none transition-opacity">
                                {{ $gb['total'] }} Laporan
                            </div>
                            <!-- Angka Permanen (Hanya muncul saat di-Export ke PDF) -->
                            <div class="hidden angka-cetak absolute bottom-[calc({{ $tinggiVisual }}%+5px)] left-1/2 transform -translate-x-1/2 text-[10px] font-bold text-gray-600">
                                {{ $gb['total'] > 0 ? $gb['total'] : '' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- GRAFIK DONUT (KANAN) -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative">
            <h3 class="text-lg font-bold text-gray-800 mb-8">Sebaran per Bidang</h3>

            <div class="flex justify-center mb-8">
                @php
                    $conicGradient = '';
                    foreach($grafik_bidang ?? [] as $gb) {
                        $conicGradient .= $gb['warna'] . ' ' . $gb['start_deg'] . 'deg ' . $gb['end_deg'] . 'deg, ';
                    }
                    $conicGradient = rtrim($conicGradient, ', ');
                    if (empty($conicGradient)) {
                        $conicGradient = '#F3F4F6 0deg 360deg';
                    }
                @endphp

                <div class="w-40 h-40 rounded-full flex items-center justify-center relative shadow-sm" style="background: conic-gradient({{ $conicGradient }});">
                    <div class="w-28 h-28 bg-white rounded-full flex flex-col items-center justify-center z-10 shadow-inner">
                        <span class="text-2xl font-extrabold text-gray-800">{{ number_format($statistik['total_laporan'] ?? 0, 0, ',', '.') }}</span>
                        <span class="text-[10px] font-bold text-gray-400 tracking-widest">TOTAL</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4 px-4 max-h-40 overflow-y-auto custom-scrollbar">
                @forelse($grafik_bidang ?? [] as $gb)
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center text-gray-600 font-medium">
                        <span class="w-3 h-3 rounded-sm mr-3 shadow-sm" style="background-color: {{ $gb['warna'] }};"></span>
                        {{ $gb['nama'] }}
                    </div>
                    <span class="font-bold text-gray-800">{{ $gb['persen'] }}%</span>
                </div>
                @empty
                <p class="text-center text-xs text-gray-400">Belum ada data bidang terhubung.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- DISTRIBUSI STATUS PENYELESAIAN -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">Distribusi Status Laporan</h3>
            <span class="bg-blue-50 text-blue-600 text-xs font-bold px-3 py-1 rounded-full border border-blue-100">Total: {{ number_format($statistik['total_laporan'] ?? 0, 0, ',', '.') }}</span>
        </div>

        @php
            $total_laporan = $statistik['total_laporan'] > 0 ? $statistik['total_laporan'] : 1;
            $p_selesai = ($statistik['selesai'] / $total_laporan) * 100;
            $p_proses  = ($statistik['dalam_proses'] / $total_laporan) * 100;
            $p_pending = ($statistik['laporan_terbaru'] / $total_laporan) * 100;
        @endphp

        <!-- Progress Bar Segmented -->
        <div class="w-full h-10 flex rounded-xl overflow-hidden shadow-inner mb-6 bg-gray-100 border border-gray-200">
            @if($statistik['total_laporan'] > 0)
                <div style="width: {{ $p_selesai }}%" class="bg-green-500 flex items-center justify-center text-white text-[10px] font-bold" title="Selesai">
                    {{ $p_selesai >= 5 ? round($p_selesai).'%' : '' }}
                </div>
                <div style="width: {{ $p_proses }}%" class="bg-yellow-400 flex items-center justify-center text-yellow-900 text-[10px] font-bold" title="Proses">
                    {{ $p_proses >= 5 ? round($p_proses).'%' : '' }}
                </div>
                <div style="width: {{ $p_pending }}%" class="bg-red-500 flex items-center justify-center text-white text-[10px] font-bold" title="Menunggu Validasi">
                    {{ $p_pending >= 5 ? round($p_pending).'%' : '' }}
                </div>
            @else
                <div class="w-full flex items-center justify-center text-xs text-gray-400 font-medium">Belum ada data laporan di tahun ini.</div>
            @endif
        </div>

        <!-- Legenda Status -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center justify-between p-4 rounded-xl border border-green-100 bg-green-50/50">
                <div class="flex items-center text-sm font-bold text-gray-700">
                    <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center mr-3"><i class="fas fa-check-circle"></i></div>
                    Selesai
                </div>
                <span class="text-xl font-extrabold text-green-600">{{ $statistik['selesai'] }}</span>
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl border border-yellow-100 bg-yellow-50/50">
                <div class="flex items-center text-sm font-bold text-gray-700">
                    <div class="w-8 h-8 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center mr-3"><i class="fas fa-sync-alt"></i></div>
                    Dalam Pengerjaan
                </div>
                <span class="text-xl font-extrabold text-yellow-600">{{ $statistik['dalam_proses'] }}</span>
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl border border-red-100 bg-red-50/50">
                <div class="flex items-center text-sm font-bold text-gray-700">
                    <div class="w-8 h-8 rounded-lg bg-red-100 text-red-500 flex items-center justify-center mr-3"><i class="fas fa-exclamation-triangle"></i></div>
                    Menunggu Validasi
                </div>
                <span class="text-xl font-extrabold text-red-500">{{ $statistik['laporan_terbaru'] }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- Library HTML2PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function eksporKePDF() {
        const areaCetak = document.getElementById('area-statistik');
        const btnBulan = document.getElementById('formFilterTahun');
        const btnCetak = document.getElementById('btn-cetak-pdf');

        // Simpan teks asli tombol
        const teksAsli = btnCetak.innerHTML;

        // Ubah tampilan tombol jadi loading
        btnCetak.innerHTML = '<i class="fas fa-spinner fa-spin mr-2 text-lg"></i> Menyusun PDF...';
        btnCetak.disabled = true;
        btnCetak.classList.add('opacity-75', 'cursor-not-allowed');

        // Modifikasi DOM sesaat sebelum dicetak
        btnBulan.style.display = 'none'; // Sembunyikan dropdown tahun
        document.querySelectorAll('.angka-cetak').forEach(el => el.classList.remove('hidden')); // Tampilkan angka di atas bar

        const kopSurat = document.getElementById('kop-surat-pdf');
        kopSurat.classList.remove('hidden');
        kopSurat.classList.add('flex'); // Pakai flex agar logo dan teks sejajar rapi

        // Konfigurasi Kualitas PDF (Landscape, High Res)
        const opsi = {
            margin:       0.4, // Margin pinggir kertas
            filename:     `Statistik_SIGAP_Subang_{{ $tahun ?? date('Y') }}.pdf`,
            image:        { type: 'jpeg', quality: 1 }, // Kualitas gambar maksimal
            html2canvas:  { scale: 2, useCORS: true, scrollY: 0 }, // Scale 2 agar tidak pecah/blur
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
        };

        // Eksekusi Pembuatan PDF
        html2pdf().set(opsi).from(areaCetak).save().then(() => {
            // Setelah selesai download, kembalikan tampilan web seperti semula
            btnBulan.style.display = 'block';
            document.querySelectorAll('.angka-cetak').forEach(el => el.classList.add('hidden'));

            kopSurat.classList.remove('flex');
            kopSurat.classList.add('hidden');

            // Kembalikan tombol seperti semula
            btnCetak.innerHTML = teksAsli;
            btnCetak.disabled = false;
            btnCetak.classList.remove('opacity-75', 'cursor-not-allowed');
        }).catch(err => {
            alert('Terjadi kesalahan saat membuat PDF: ' + err);
            btnCetak.innerHTML = teksAsli;
            btnCetak.disabled = false;
            btnCetak.classList.remove('opacity-75', 'cursor-not-allowed');
        });
    }
</script>
@endpush
