<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>SIPANDA - Laporan Catatan Kegiatan</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.2cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, 'DejaVu Sans', sans-serif;
            font-size: 8.5px;
            line-height: 1.4;
            color: #1f2937;
        }
        
        /* Kop Laporan */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .header-table td {
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
        }
        .kop-subtitle {
            font-size: 9px;
            color: #4b5563;
            margin: 2px 0 0 0;
        }
        .kop-line {
            border-bottom: 2px solid #064E3B;
            margin-top: 8px;
            margin-bottom: 15px;
        }

        /* Metadata banner */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .meta-table td {
            padding: 6px 10px;
            font-size: 8px;
            border: none;
        }
        .meta-label {
            color: #6b7280;
            font-weight: bold;
            width: 12%;
        }
        .meta-val {
            color: #1f2937;
            width: 38%;
        }
        .qr-code-wrapper {
            text-align: right;
            padding-right: 15px !important;
            vertical-align: middle !important;
        }
        .qr-text {
            font-size: 7px;
            color: #6b7280;
            margin-top: 3px;
        }

        /* Section Title */
        h2 {
            font-size: 10px;
            font-weight: bold;
            color: #064E3B;
            border-left: 3px solid #064E3B;
            padding-left: 6px;
            margin: 15px 0 8px 0;
            text-transform: uppercase;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th, 
        .data-table td {
            border: 1px solid #d1d5db;
            padding: 5px 6px;
            text-align: left;
        }
        .data-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f9fafb;
        }
        .text-center {
            text-align: center !important;
        }
        .font-semibold {
            font-weight: bold;
        }

        /* Badges */
        .pdf-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-info { background-color: #dbeafe; color: #1e40af; }

        /* Signature block */
        .signature-section {
            margin-top: 25px;
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
            height: 45px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .footer-validation {
            margin-top: 15px;
            font-size: 7.5px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    <!-- KOP HEADER -->
    <table class="header-table">
        <tr>
            <td>
                <h1 class="kop-title">SIPANDA-KPH PERHUTANI</h1>
                <p class="kop-subtitle">Laporan Ekspor Catatan Rincian Kegiatan Harian Pegawai</p>
            </td>
        </tr>
    </table>
    <div class="kop-line"></div>

    <!-- METADATA -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Periode</td>
            <td class="meta-val">: {{ $periodeLabel }}</td>
            <td rowspan="3" class="qr-code-wrapper">
                <div style="display: inline-block; text-align: center;">
                    {!! $validationQrSvg !!}
                    <div class="qr-text">Scan Validasi</div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="meta-label">Kode Laporan</td>
            <td class="meta-val">: <span class="font-semibold">{{ $validation->report_code }}</span></td>
        </tr>
        <tr>
            <td class="meta-label">Dibuat Oleh</td>
            <td class="meta-val">: {{ $validation->generatedBy->name ?? '-' }} ({{ $validation->generated_at->format('d-m-Y H:i') }})</td>
        </tr>
    </table>

    <!-- RINGKASAN REKAPITULASI CATATAN -->
    <h2>Ringkasan Catatan Kegiatan</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center">Total Catatan</th>
                <th class="text-center">Menunggu Verifikasi</th>
                <th class="text-center">Disetujui</th>
                <th class="text-center">Revisi</th>
                <th class="text-center">Ditolak</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center font-semibold">{{ $summaryCatatan['total_catatan'] }}</td>
                <td class="text-center font-semibold" style="background-color:#fef3c7;">{{ $summaryCatatan['catatan_menunggu_verifikasi'] }}</td>
                <td class="text-center font-semibold badge-success" style="background-color:#d1fae5;">{{ $summaryCatatan['catatan_disetujui'] }}</td>
                <td class="text-center font-semibold" style="background-color:#fef3c7;">{{ $summaryCatatan['catatan_revisi'] }}</td>
                <td class="text-center font-semibold badge-danger" style="background-color:#fee2e2;">{{ $summaryCatatan['catatan_ditolak'] }}</td>
            </tr>
        </tbody>
    </table>

    <!-- DAFTAR DETAIL CATATAN -->
    <h2>Daftar Detail Catatan Kegiatan</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 3%">No</th>
                <th style="width: 8%">Tanggal</th>
                <th style="width: 12%">Pegawai</th>
                <th style="width: 12%">Unit Kerja</th>
                <th style="width: 15%">Tugas Acuan</th>
                <th style="width: 20%">Deskripsi Kegiatan</th>
                <th style="width: 15%">Hasil / Output</th>
                <th class="text-center" style="width: 8%">Status</th>
                <th style="width: 10%">Verifier</th>
                <th style="width: 10%">Waktu Verifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daftarCatatan as $i => $c)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td>{{ optional($c->tanggal_kegiatan)->format('d-m-Y') ?? '-' }}</td>
                <td class="font-semibold">{{ $c->pegawai->user->name ?? '-' }}</td>
                <td>{{ $c->pegawai->unitkerja->nama_unitkerja ?? '-' }}</td>
                <td>{{ $c->penugasan->tugas->judul ?? '-' }}</td>
                <td>{!! nl2br(e(\Illuminate\Support\Str::limit($c->deskripsi ?? '-', 100))) !!}</td>
                <td>{{ \Illuminate\Support\Str::limit($c->hasil_kegiatan ?? '-', 100) }}</td>
                <td class="text-center">
                    <span class="pdf-badge {{ $c->status_verifikasi === 'disetujui' ? 'badge-success' : ($c->status_verifikasi === 'ditolak' ? 'badge-danger' : 'badge-warning') }}">
                        {{ $c->status_verifikasi_label }}
                    </span>
                </td>
                <td>{{ $c->verifier->name ?? '-' }}</td>
                <td>{{ optional($c->diverifikasi_at)->format('d-m-Y H:i') ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- SIGNATURE AREA -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <p>Mengetahui,</p>
                    <p class="font-semibold">Kepala Unit Kerja / KPH</p>
                    <div class="signature-space"></div>
                    <p class="signature-name">....................................................</p>
                    <p>NIP. ............................................</p>
                </td>
                <td>
                    <p>Dibuat oleh,</p>
                    <p class="font-semibold">Petugas Verifikasi Laporan</p>
                    <div class="signature-space"></div>
                    <p class="signature-name">{{ $validation->generatedBy->name ?? '-' }}</p>
                    <p>NIP. {{ $validation->generatedBy->nip ?? '-' }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- VALIDATION LINK FOOTER -->
    <div class="footer-validation">
        Dokumen ini diterbitkan secara resmi melalui sistem SIPANDA-KPH. Tautan Validasi Laporan: {{ $validation->validationUrl() }}
    </div>

</body>
</html>
