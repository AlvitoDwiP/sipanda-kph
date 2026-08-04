<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Catatan Kegiatan Harian</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #1f2937;
        }
        
        /* Kop Laporan */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .kop-title {
            font-size: 15px;
            font-weight: bold;
            color: #064E3B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            text-align: center;
        }
        .kop-subtitle {
            font-size: 10px;
            color: #4b5563;
            margin: 2px 0 0 0;
            text-align: center;
        }
        .kop-line {
            border-bottom: 2px solid #064E3B;
            margin-top: 8px;
            margin-bottom: 20px;
        }

        /* Detail Pegawai Table */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .details-table td {
            padding: 8px 10px;
            font-size: 9.5px;
            border: none;
            vertical-align: top;
        }
        .details-label {
            font-weight: bold;
            width: 25%;
            color: #4b5563;
        }
        .details-separator {
            width: 3%;
            text-align: center;
            color: #9ca3af;
        }
        .details-val {
            width: 72%;
            color: #1f2937;
        }

        /* Section Headings */
        h4 {
            font-size: 11px;
            font-weight: bold;
            color: #064E3B;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
            margin: 20px 0 8px 0;
            text-transform: uppercase;
        }
        
        .content-box {
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            padding: 10px 12px;
            font-size: 9.5px;
            color: #1f2937;
            text-align: justify;
            white-space: pre-line;
        }

        /* Fotos Grid */
        .foto-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .foto-table td {
            padding: 8px;
            text-align: center;
            border: none;
            width: 50%;
        }
        .foto-card {
            border: 1px solid #d1d5db;
            padding: 6px;
            background-color: #f9fafb;
            display: inline-block;
        }
        .foto {
            width: 240px;
            height: auto;
            display: block;
            border: 1px solid #e5e7eb;
        }
        .foto-caption {
            font-size: 8px;
            color: #6b7280;
            margin-top: 5px;
            font-style: italic;
        }

        /* Signature block */
        .signature-section {
            margin-top: 35px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            border: none;
            padding: 0;
            vertical-align: top;
        }
        .signature-space {
            height: 55px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- KOP HEADER -->
    <table class="kop-table">
        <tr>
            <td>
                <h1 class="kop-title">CATATAN KEGIATAN HARIAN PEGAWAI</h1>
                <p class="kop-subtitle">SIPANDA-KPH PERUM PERHUTANI</p>
            </td>
        </tr>
    </table>
    <div class="kop-line"></div>

    <!-- DATA PEGAWAI -->
    <table class="details-table">
        <tr>
            <td class="details-label">Nama Pegawai</td>
            <td class="details-separator">:</td>
            <td class="details-val font-semibold">{{ $catatan->pegawai->user->name }}</td>
        </tr>
        <tr>
            <td class="details-label">NIP</td>
            <td class="details-separator">:</td>
            <td class="details-val">{{ $catatan->pegawai->user->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td class="details-label">Jabatan</td>
            <td class="details-separator">:</td>
            <td class="details-val">{{ $catatan->pegawai->jabatan->nama_jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="details-label">Golongan</td>
            <td class="details-separator">:</td>
            <td class="details-val">{{ $catatan->pegawai->golongan->nama_golongan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="details-label">Unit Kerja</td>
            <td class="details-separator">:</td>
            <td class="details-val font-semibold">{{ $catatan->pegawai->unitkerja->nama_unitkerja ?? '-' }}</td>
        </tr>
        <tr>
            <td class="details-label">Periode Kegiatan</td>
            <td class="details-separator">:</td>
            <td class="details-val">
                {{ \Carbon\Carbon::create()->month($catatan->periode_bulan)->translatedFormat('F') }}
                {{ $catatan->periode_tahun }}
            </td>
        </tr>
    </table>

    <!-- JUDUL KEGIATAN -->
    <h4>Judul Kegiatan</h4>
    <div class="content-box font-semibold" style="border-left: 3px solid #064E3B;">
        {{ $catatan->judul }}
    </div>

    <!-- DESKRIPSI -->
    <h4>Uraian Rincian Kegiatan</h4>
    <div class="content-box">
        {!! nl2br(e($catatan->deskripsi)) !!}
    </div>

    <!-- HASIL KEGIATAN -->
    @if($catatan->hasil_kegiatan)
        <h4>Hasil Kegiatan (Output)</h4>
        <div class="content-box">
            {!! nl2br(e($catatan->hasil_kegiatan)) !!}
        </div>
    @endif

    <!-- FOTO KEGIATAN -->
    <h4>Bukti Foto Kegiatan</h4>
    @php
        $fotos = is_string($catatan->foto_kegiatan)
            ? json_decode($catatan->foto_kegiatan, true)
            : $catatan->foto_kegiatan;
    @endphp

    @if (!empty($fotos))
        <table class="foto-table">
            <tr>
                @foreach ($fotos as $index => $foto)
                    <td>
                        <div class="foto-card">
                            <img src="{{ storage_path('app/public/' . $foto) }}" class="foto">
                            <div class="foto-caption">Foto Lampiran #{{ $index + 1 }}</div>
                        </div>
                    </td>
                    @if (($index + 1) % 2 === 0)
                        </tr><tr>
                    @endif
                @endforeach
            </tr>
        </table>
    @else
        <p style="font-style: italic; color: #9ca3af; font-size: 9px; margin-top: 5px;">* Tidak ada foto dokumentasi terlampir.</p>
    @endif

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <p>Mengetahui,</p>
                    <p class="font-semibold">Atasan / Verifikator</p>
                    <div class="signature-space"></div>
                    <p class="signature-name">....................................................</p>
                    <p>NIP. ............................................</p>
                </td>
                <td>
                    <p>Hormat Kami,</p>
                    <p class="font-semibold">Pegawai Bersangkutan</p>
                    <div class="signature-space"></div>
                    <p class="signature-name">{{ $catatan->pegawai->user->name }}</p>
                    <p>NIP. {{ $catatan->pegawai->user->nip ?? '-' }}</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
