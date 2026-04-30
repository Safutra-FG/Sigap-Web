@extends('layouts.app')

@section('konten')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="fas fa-chart-bar"></i>
            </div>
            <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full flex items-center">
                <i class="fas fa-arrow-trend-up mr-1"></i> 12%
            </span>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Total Laporan</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($statistik['total_laporan'], 0, ',', '.') }}</h3>
        </div>
        <div class="mt-4 text-xs text-gray-400 font-medium flex justify-between">
            <span>Dibandingkan bulan lalu</span>
            <span class="text-gray-800 font-bold">+142</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                <i class="fas fa-check-circle"></i>
            </div>
            <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full flex items-center">
                <i class="fas fa-arrow-trend-up mr-1"></i> 8%
            </span>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Selesai</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($statistik['selesai'], 0, ',', '.') }}</h3>
        </div>
        <div class="mt-4 text-xs text-gray-400 font-medium flex justify-between">
            <span>Penyelesaian tepat waktu</span>
            <span class="text-gray-800 font-bold">92%</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600">
                <i class="fas fa-sync-alt"></i>
            </div>
            <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-1 rounded-full flex items-center">
                <i class="fas fa-arrow-trend-down mr-1"></i> 3%
            </span>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Proses</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($statistik['dalam_proses'], 0, ',', '.') }}</h3>
        </div>
        <div class="mt-4 text-xs text-gray-400 font-medium flex justify-between">
            <span>Sedang dikerjakan tim</span>
            <span class="text-gray-800 font-bold">24 Aktif</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <span class="bg-red-50 text-red-500 text-xs font-bold px-2 py-1 rounded-full flex items-center">
                <i class="fas fa-exclamation-triangle mr-1"></i> High
            </span>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Ditunda / Terbaru</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($statistik['laporan_terbaru'], 0, ',', '.') }}</h3>
        </div>
        <div class="mt-4 text-xs text-gray-400 font-medium flex justify-between">
            <span>Perlu atensi segera</span>
            <span class="text-gray-800 font-bold">12 Urgent</span>
        </div>
    </div>

</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8 relative">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Peta Sebaran Laporan</h3>
            <p class="text-sm text-gray-400 font-medium">Visualisasi geografis infrastruktur per wilayah</p>
        </div>
        <div class="flex space-x-3">
            <button class="w-10 h-10 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 flex items-center justify-center transition">
                <i class="fas fa-layer-group"></i>
            </button>
            <button class="w-10 h-10 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 flex items-center justify-center transition">
                <i class="fas fa-filter"></i>
            </button>
            <button class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white text-sm font-bold rounded-lg transition flex items-center">
                <i class="fas fa-expand mr-2"></i> Layar Penuh
            </button>
        </div>
    </div>

    <div class="relative w-full h-[400px] bg-teal-500/80 rounded-xl overflow-hidden border border-gray-200">
        <div class="absolute inset-0 opacity-40" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>

        <svg class="absolute inset-0 w-full h-full opacity-60" preserveAspectRatio="none">
            <path d="M0,200 Q300,100 800,300 T1200,100" stroke="white" stroke-width="4" fill="none"/>
            <path d="M200,400 Q400,200 600,400 T1000,150" stroke="white" stroke-width="2" fill="none" stroke-dasharray="5,5"/>
        </svg>

        <div class="absolute top-6 left-6 bg-white/95 backdrop-blur rounded-xl p-5 shadow-lg border border-white/50 z-10">
            <h4 class="text-xs font-extrabold text-gray-700 mb-4 tracking-widest uppercase">Legenda Kepadatan</h4>
            <div class="space-y-3">
                <div class="flex items-center text-sm font-medium text-gray-600">
                    <span class="w-3 h-3 rounded-full bg-red-500 mr-3"></span> Laporan Mendesak
                </div>
                <div class="flex items-center text-sm font-medium text-gray-600">
                    <span class="w-3 h-3 rounded-full bg-yellow-400 mr-3"></span> Dalam Pengerjaan
                </div>
                <div class="flex items-center text-sm font-medium text-gray-600">
                    <span class="w-3 h-3 rounded-full bg-green-500 mr-3"></span> Infrastruktur Stabil
                </div>
            </div>
        </div>

        <div class="absolute bottom-6 right-6 flex flex-col space-y-2 z-10">
            <button class="w-10 h-10 bg-white rounded-lg shadow text-gray-600 font-bold hover:bg-gray-50">+</button>
            <button class="w-10 h-10 bg-white rounded-lg shadow text-gray-600 font-bold hover:bg-gray-50">-</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-10">

    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Grafik Laporan per Bulan</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tren Aktivitas 2024</p>
            </div>
            <select class="text-sm border-gray-200 rounded-lg text-gray-600 font-medium py-1.5 px-3 bg-gray-50 hover:bg-gray-100 cursor-pointer outline-none">
                <option>Tahun 2024</option>
                <option>Tahun 2023</option>
            </select>
        </div>
        <div class="h-64 flex items-end justify-between px-2 pb-6 border-b border-gray-100 relative">
            <div class="absolute bottom-0 w-full flex justify-between text-xs font-bold text-gray-400 px-4">
                <span>JAN</span><span>FEB</span><span>MAR</span><span>APR</span><span class="text-blue-600">MEI</span><span>JUN</span><span>JUL</span>
            </div>
            <div class="w-10 bg-gray-100 rounded-t-sm h-24"></div>
            <div class="w-10 bg-gray-100 rounded-t-sm h-32"></div>
            <div class="w-10 bg-gray-100 rounded-t-sm h-20"></div>
            <div class="w-10 bg-gray-100 rounded-t-sm h-40"></div>
            <div class="w-10 bg-blue-100 rounded-t-sm h-48 relative border-t-2 border-blue-500"></div>
            <div class="w-10 bg-gray-100 rounded-t-sm h-36"></div>
            <div class="w-10 bg-gray-100 rounded-t-sm h-28"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative">
        <h3 class="text-lg font-bold text-gray-800 mb-8">Laporan per Bidang</h3>

        <div class="flex justify-center mb-8">
            <div class="w-40 h-40 rounded-full border-[12px] border-gray-50 flex items-center justify-center relative">
                <div class="absolute inset-0 rounded-full border-[12px] border-blue-600" style="clip-path: polygon(50% 50%, 100% 0, 100% 100%, 0 100%);"></div>
                <div class="absolute inset-0 rounded-full border-[12px] border-yellow-400" style="clip-path: polygon(50% 50%, 0 100%, 0 0);"></div>
                <div class="absolute inset-0 rounded-full border-[12px] border-red-500" style="clip-path: polygon(50% 50%, 0 0, 100% 0);"></div>

                <div class="text-center bg-white w-full h-full rounded-full flex flex-col items-center justify-center z-10">
                    <span class="text-2xl font-extrabold text-gray-800">{{ number_format($statistik['total_laporan'], 0, ',', '.') }}</span>
                    <span class="text-[10px] font-bold text-gray-400 tracking-widest">TOTAL</span>
                </div>
            </div>
        </div>

        <div class="space-y-4 px-4">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center text-gray-600 font-medium"><span class="w-3 h-3 bg-blue-600 rounded-sm mr-3"></span> Bina Marga</div>
                <span class="font-bold text-gray-800">45%</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center text-gray-600 font-medium"><span class="w-3 h-3 bg-yellow-400 rounded-sm mr-3"></span> Cipta Karya</div>
                <span class="font-bold text-gray-800">25%</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center text-gray-600 font-medium"><span class="w-3 h-3 bg-red-500 rounded-sm mr-3"></span> SDA</div>
                <span class="font-bold text-gray-800">15%</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center text-gray-600 font-medium"><span class="w-3 h-3 bg-gray-200 rounded-sm mr-3"></span> Lainnya</div>
                <span class="font-bold text-gray-800">15%</span>
            </div>
        </div>

        <button class="absolute -bottom-5 -right-5 w-14 h-14 bg-yellow-400 hover:bg-yellow-500 text-white rounded-full shadow-lg flex items-center justify-center text-xl transition-transform hover:scale-110">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>
@endsection
