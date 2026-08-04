<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>SIPANDA - Rekap Pekerjaan</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #1f2937;
        }
        
        /* Kop Laporan (Official Header) */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .kop-title {
            font-size: 16px;
            font-weight: bold;
            color: #064E3B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .kop-subtitle {
            font-size: 10px;
            color: #4b5563;
            margin: 2px 0 0 0;
        }
        .kop-line {
            border-bottom: 2px solid #064E3B;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        /* Metadata */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .meta-table td {
            padding: 8px 12px;
            font-size: 9px;
            border: none;
        }
        .meta-label {
            color: #6b7280;
            font-weight: bold;
            width: 15%;
        }
        .meta-val {
            color: #1f2937;
            width: 35%;
        }
        .qr-code-wrapper {
            text-align: right;
            padding-right: 15px !important;
            vertical-align: middle !important;
        }
        .qr-text {
            font-size: 8px;
            color: #6b7280;
            margin-top: 4px;
        }

        /* Section Headings */
        h2 {
            font-size: 12px;
            font-weight: bold;
            color: #064E3B;
            border-left: 3px solid #064E3B;
            padding-left: 8px;
            margin: 25px 0 10px 0;
            text-transform: uppercase;
        }
        h2:first-of-type {
            margin-top: 0;
        }

        /* Tables styling */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px;
        }
        .data-table th, 
        .data-table td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
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
        .text-right {
            text-align: right !important;
        }
        .font-semibold {
            font-weight: bold;
        }

        /* Badges for PDF */
        .pdf-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-info { background-color: #dbeafe; color: #1e40af; }
        .badge-neutral { background-color: #f3f4f6; color: #374151; }

        /* Signature block */
        .signature-section {
            margin-top: 40px;
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
            height: 60px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Page breaks */
        .page-break {
            page-break-before: always;
        }
        .footer-validation {
            margin-top: 15px;
            font-size: 8px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <!-- KOP DOKUMEN -->
    <table class="header-table">
        <tr>
            <td>
                <h1 class="kop-title">SIPANDA-KPH PERHUTANI</h1>
                <p class="kop-subtitle">Laporan Rekapitulasi Pekerjaan & Catatan Kegiatan Harian Pegawai</p>
            </td>
        </tr>
    </table>
    <div class="kop-line"></div>

    <!-- METADATA DOKUMEN -->
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

    <!-- RINGKASAN TUGAS -->
    <h2>Ringkasan Kinerja Tugas</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center">Total Tugas</th>
                <th class="text-center">Selesai</th>
                <th class="text-center">Belum Selesai</th>
                <th class="text-center">Menunggu Verifikasi</th>
                <th class="text-center">Revisi</th>
                <th class="text-center">Terlambat</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center font-semibold">{{ $summaryTugas['total_tugas'] }}</td>
                <td class="text-center font-semibold text-center badge-success" style="background-color:#d1fae5;">{{ $summaryTugas['tugas_selesai'] }}</td>
                <td class="text-center font-semibold">{{ $summaryTugas['tugas_belum_dikerjakan'] + $summaryTugas['tugas_sedang_dikerjakan'] + $summaryTugas['tugas_menunggu_verifikasi'] + $summaryTugas['tugas_revisi'] }}</td>
                <td class="text-center font-semibold" style="background-color:#fef3c7;">{{ $summaryTugas['tugas_menunggu_verifikasi'] }}</td>
                <td class="text-center font-semibold" style="background-color:#fef3c7;">{{ $summaryTugas['tugas_revisi'] }}</td>
                <td class="text-center font-semibold badge-danger" style="background-color:#fee2e2;">{{ $summaryTugas['tugas_terlambat'] }}</td>
            </tr>
        </tbody>
    </table>

    <!-- RINGKASAN CATATAN -->
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

    <!-- REKAP PER PEGAWAI -->
    <h2>Rekap Per Pegawai</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Pegawai</th>
                <th>Unit Kerja</th>
                <th class="text-center">Tugas</th>
                <th class="text-center">Selesai</th>
                <th class="text-center">Belum</th>
                <th class="text-center">Terlambat</th>
                <th class="text-center">Catatan</th>
                <th class="text-center">Disetujui</th>
                <th class="text-right">% Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapPegawai as $r)
            <tr>
                <td class="font-semibold">{{ $r->nama_pegawai }}</td>
                <td>{{ $r->unit_kerja ?? '-' }}</td>
                <td class="text-center">{{ $r->total_tugas }}</td>
                <td class="text-center" style="color: #065f46;">{{ $r->tugas_selesai }}</td>
                <td class="text-center">{{ $r->tugas_belum_selesai }}</td>
                <td class="text-center" style="color: #991b1b;">{{ $r->tugas_terlambat }}</td>
                <td class="text-center">{{ $r->total_catatan }}</td>
                <td class="text-center" style="color: #065f46;">{{ $r->catatan_disetujui }}</td>
                <td class="text-right font-semibold">{{ $r->persentase_selesai }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- REKAP PER UNIT KERJA -->
    <h2>Rekap Per Unit Kerja</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Unit Kerja</th>
                <th class="text-center">Total Pegawai</th>
                <th class="text-center">Total Tugas</th>
                <th class="text-center">Selesai</th>
                <th class="text-center">Belum</th>
                <th class="text-center">Terlambat</th>
                <th class="text-center">Catatan</th>
                <th class="text-center">Catatan Disetujui</th>
                <th class="text-right">% Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapUnitKerja as $u)
            <tr>
                <td class="font-semibold">{{ $u->nama_unitkerja }}</td>
                <td class="text-center">{{ $u->jumlah_pegawai }}</td>
                <td class="text-center">{{ $u->total_tugas }}</td>
                <td class="text-center" style="color: #065f46;">{{ $u->tugas_selesai }}</td>
                <td class="text-center">{{ $u->tugas_belum_selesai }}</td>
                <td class="text-center" style="color: #991b1b;">{{ $u->tugas_terlambat }}</td>
                <td class="text-center">{{ $u->total_catatan }}</td>
                <td class="text-center" style="color: #065f46;">{{ $u->catatan_disetujui }}</td>
                <td class="text-right font-semibold">{{ $u->persentase_selesai }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- DETAIL TUGAS -->
    <h2>Detail Tugas</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Pegawai</th>
                <th>Judul Tugas</th>
                <th class="text-center">Prioritas</th>
                <th>Deadline</th>
                <th class="text-center">Status</th>
                <th class="text-right">Progres</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daftarTugas as $t)
            <tr>
                <td>{{ optional($t->tugas->tanggal_tugas)->format('d-m-Y') ?? '-' }}</td>
                <td class="font-semibold">{{ $t->pegawai->user->name ?? '-' }}</td>
                <td>{{ $t->tugas->judul ?? '-' }}</td>
                <td class="text-center">
                    <span class="pdf-badge {{ $t->tugas->prioritas === 'tinggi' ? 'badge-danger' : ($t->tugas->prioritas === 'sedang' ? 'badge-info' : 'badge-neutral') }}">
                        {{ $t->tugas->prioritas }}
                    </span>
                </td>
                <td>{{ optional($t->tugas->deadline)->format('d-m-Y') ?? '-' }}</td>
                <td class="text-center">
                    <span class="pdf-badge {{ $t->status === 'selesai' ? 'badge-success' : ($t->status === 'terlambat' ? 'badge-danger' : 'badge-warning') }}">
                        {{ str_replace('_', ' ', $t->status) }}
                    </span>
                </td>
                <td class="text-right font-semibold">{{ (int)($t->progres_persen ?? 0) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- DETAIL CATATAN KEGIATAN -->
    <h2>Detail Catatan Kegiatan</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Pegawai</th>
                <th>Tugas Acuan</th>
                <th>Rincian Hasil Kegiatan</th>
                <th class="text-center">Verifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daftarCatatan as $c)
            <tr>
                <td>{{ optional($c->tanggal_kegiatan)->format('d-m-Y') ?? '-' }}</td>
                <td class="font-semibold">{{ $c->pegawai->user->name ?? '-' }}</td>
                <td>{{ $c->penugasan->tugas->judul ?? '-' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($c->hasil_kegiatan ?? $c->deskripsi ?? '-', 100) }}</td>
                <td class="text-center">
                    <span class="pdf-badge {{ $c->status_verifikasi === 'disetujui' ? 'badge-success' : ($c->status_verifikasi === 'ditolak' ? 'badge-danger' : 'badge-warning') }}">
                        {{ $c->status_verifikasi_label }}
                    </span>
                </td>
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
