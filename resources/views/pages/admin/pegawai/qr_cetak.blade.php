<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Pegawai</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f7fa; margin: 0; padding: 20px; }
        .actions { margin-bottom: 16px; }
        .btn { display:inline-block; padding:8px 12px; border-radius:6px; text-decoration:none; margin-right:8px; font-size:14px; }
        .btn-primary { background:#166534; color:#fff; }
        .btn-secondary { background:#334155; color:#fff; }
        .card { max-width:420px; margin:0 auto; background:#fff; border:1px solid #dbe3ea; border-radius:12px; padding:20px; }
        .title { text-align:center; font-weight:700; margin-bottom:6px; }
        .subtitle { text-align:center; color:#334155; font-size:14px; margin-bottom:16px; }
        .meta { font-size:14px; margin-bottom:12px; }
        .meta p { margin:4px 0; }
        .qr-wrap { text-align:center; margin:14px 0; }
        .token { text-align:center; font-family: monospace; font-size:13px; margin-top:6px; }
        .note { font-size:12px; color:#475569; text-align:center; margin-top:14px; }
        @media print {
            .actions { display:none; }
            body { background:#fff; padding:0; }
            .card { border:1px solid #000; box-shadow:none; }
        }
    </style>
</head>
<body>
    <div class="actions">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
        <a class="btn btn-secondary" href="{{ route($routePrefix . '.pegawai.index') }}">Kembali</a>
    </div>

    <div class="card">
        <div class="title">SIPANDA-KPH</div>
        <div class="subtitle">QR Display Job Desk Harian</div>

        <div class="meta">
            <p><strong>Nama:</strong> {{ $pegawai->user->name ?? '-' }}</p>
            <p><strong>Jabatan:</strong> {{ $pegawai->jabatan->nama_jabatan ?? '-' }}</p>
            <p><strong>Unit Kerja:</strong> {{ $pegawai->unitkerja->nama_unitkerja ?? '-' }}</p>
            <p><strong>Status:</strong> {{ $pegawai->status_pegawai === 'aktif' ? 'Pegawai Aktif' : 'Pegawai Tidak Aktif' }}</p>
        </div>

        <div class="qr-wrap">
            {!! QrCode::size(240)->margin(1)->generate($pegawai->qr_token) !!}
        </div>

        <div class="token">{{ $pegawai->qr_token }}</div>

        <div class="note">
            QR ini digunakan untuk menampilkan job desk harian, bukan untuk absensi.
        </div>
    </div>
</body>
</html>
