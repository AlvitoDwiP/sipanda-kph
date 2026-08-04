<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Pegawai - {{ $pegawai->user->name ?? '-' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ui-primary': '#15803d',
                        'ui-primary-hover': '#166534',
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .print-card { border: 2px solid #166534 !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col items-center justify-center p-6 text-slate-800 font-sans">

    <!-- ACTION BUTTONS -->
    <div class="no-print mb-6 flex gap-3">
        <button onclick="window.print()" class="px-5 py-2.5 rounded-lg bg-ui-primary hover:bg-ui-primary-hover text-white font-semibold shadow-sm transition-all flex items-center gap-2 text-sm">
            <span>Cetak QR Code</span>
        </button>
        <a href="{{ route($routePrefix . '.pegawai.index') }}" class="px-5 py-2.5 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold shadow-sm transition-all text-sm">
            Kembali
        </a>
    </div>

    <!-- QR CARD -->
    <div class="print-card bg-white w-full max-w-sm border border-slate-200 rounded-2xl p-6 shadow-md flex flex-col items-center text-center">
        <!-- HEADER -->
        <div class="mb-4">
            <h1 class="text-lg font-extrabold text-ui-primary tracking-tight">SIPANDA-KPH</h1>
            <p class="text-xs text-slate-500 font-medium">QR Display Job Desk Harian</p>
        </div>

        <!-- META DETAILS -->
        <div class="w-full text-left bg-slate-50 border border-slate-100 rounded-xl p-4 mb-5 space-y-2 text-xs">
            <div class="flex justify-between">
                <span class="text-slate-500">Nama:</span>
                <span class="font-bold text-slate-900 text-right ml-2">{{ $pegawai->user->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Jabatan:</span>
                <span class="font-semibold text-slate-800 text-right ml-2">{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Unit Kerja:</span>
                <span class="font-semibold text-slate-800 text-right ml-2">{{ $pegawai->unitkerja->nama_unitkerja ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Status:</span>
                <span class="font-semibold text-green-700">{{ $pegawai->status_pegawai === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}</span>
            </div>
        </div>

        <!-- QR CODE IMAGE -->
        <div class="bg-white p-3 border border-slate-100 rounded-2xl shadow-inner mb-4">
            {!! QrCode::size(200)->margin(1)->generate($pegawai->qr_token) !!}
        </div>

        <!-- QR TOKEN -->
        <div class="font-mono text-xs text-slate-400 select-all mb-4 break-all max-w-xs">
            {{ $pegawai->qr_token }}
        </div>

        <!-- FOOTER / NOTE -->
        <div class="text-[10px] text-slate-400 border-t border-slate-100 pt-3 w-full">
            QR ini digunakan untuk menampilkan job desk harian pada display monitor.
        </div>
    </div>

</body>
</html>
