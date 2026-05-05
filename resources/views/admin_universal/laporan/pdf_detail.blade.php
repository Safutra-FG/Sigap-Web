<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Laporan {{ $laporan->id_laporan }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; line-height: 1.5; }
        .kop-surat { width: 100%; border-bottom: 3px solid #1E3A8A; padding-bottom: 15px; margin-bottom: 20px; text-align: center; position: relative; }
        .logo { position: absolute; left: 0; top: 0; width: 90px; }
        .instansi { font-size: 20px; font-weight: bold; color: #1E3A8A; margin: 0; padding-top: 15px; letter-spacing: 1px; }
        .dinas { font-size: 14px; margin-top: 5px; color: #444; }
        .alamat-kop { font-size: 11px; color: #666; margin-top: 5px; }

        .judul-dokumen { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 25px; text-transform: uppercase; text-decoration: underline; }

        .tabel-info { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .tabel-info th, .tabel-info td { padding: 10px; border: 1px solid #ddd; vertical-align: top; }
        .tabel-info th { width: 30%; background-color: #f8fafc; text-align: left; font-size: 13px; color: #555; }
        .tabel-info td { font-weight: bold; color: #222; }

        .status-badge { display: inline-block; padding: 5px 12px; background-color: #1E3A8A; color: white; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }

        .ttd-section { width: 100%; margin-top: 50px; }
        .ttd-box { width: 40%; float: right; text-align: center; }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <img src="{{ public_path('gambar/puprlogo.png') }}" class="logo" alt="Logo PUPR">
        <h1 class="instansi">PEMERINTAH KABUPATEN SUBANG</h1>
        <p class="dinas">DINAS PEKERJAAN UMUM DAN PENATAAN RUANG (PUPR)</p>
        <p class="alamat-kop">Jl. KS. Tubun No.14, Cigadung, Kec. Subang, Kabupaten Subang, Jawa Barat 41213</p>
    </div>

    <!-- JUDUL -->
    <div class="judul-dokumen">
        TANDA TERIMA LAPORAN INFRASTRUKTUR
    </div>

    <!-- TABEL INFORMASI -->
    <table class="tabel-info">
        <tr>
            <th>Nomor Registrasi Laporan</th>
            <td style="font-size: 16px; color: #1E3A8A;">#{{ $laporan->id_laporan }}</td>
        </tr>
        <tr>
            <th>Tanggal Pelaporan</th>
            <td>{{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('l, d F Y - H:i') }} WIB</td>
        </tr>
        <tr>
            <th>Nama Pelapor</th>
            <td>{{ $laporan->pelapor->nama_lengkap ?? 'Anonim' }}</td>
        </tr>
        <tr>
            <th>Kategori Pekerjaan</th>
            <td>{{ $laporan->kategori_bidang }}</td>
        </tr>
        <tr>
            <th>Lokasi Kejadian</th>
            <td>{{ $laporan->alamat_map }}<br><span style="font-size:11px; color:#666; font-weight:normal;">(Koor: {{ $laporan->lokasi_gps }})</span></td>
        </tr>
        <tr>
            <th>Deskripsi Kerusakan</th>
            <td style="font-style: italic; font-weight: normal;">"{{ $laporan->deskripsi_laporan }}"</td>
        </tr>
        <tr>
            <th>Status Saat Ini</th>
            <td>
                <span class="status-badge">
                    {{ $laporan->status }}
                </span>
            </td>
        </tr>
    </table>

    <p style="font-size: 12px; color: #666; text-align: justify;">
        Catatan: Dokumen ini diterbitkan oleh sistem SIGAP (Sistem Informasi Geospasial Pengaduan) secara otomatis dan sah digunakan sebagai bukti laporan masyarakat untuk segera ditindaklanjuti oleh tim teknis lapangan.
    </p>

    <!-- TANDA TANGAN -->
    <div class="ttd-section">
        <div class="ttd-box">
            <p>Subang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p style="margin-bottom: 60px;">Administrator SIGAP,</p>
            <p style="font-weight: bold; text-decoration: underline;">Tito & Wi'am</p>
            <p style="font-size: 12px;">NIP. 19880123 201001 1 001</p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>
