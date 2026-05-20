<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Validasi Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen py-10 px-4">
<div class="max-w-3xl mx-auto bg-white border border-slate-200 rounded-xl p-6 space-y-4">
    <h1 class="text-2xl font-bold text-slate-800">Validasi Dokumen Laporan SIPANDA-KPH</h1>
    <div class="rounded-lg p-4 @if($status==='valid') bg-emerald-50 border border-emerald-200 text-emerald-800 @elseif($status==='revoked') bg-amber-50 border border-amber-200 text-amber-800 @else bg-red-50 border border-red-200 text-red-800 @endif">
        <p class="font-semibold">{{ strtoupper($status) }}</p>
        <p class="text-sm mt-1">{{ $message }}</p>
    </div>

    @if($report)
    <div class="grid md:grid-cols-2 gap-3 text-sm">
        <div><span class="text-slate-500">Kode Laporan:</span> <span class="font-medium">{{ $report->report_code }}</span></div>
        <div><span class="text-slate-500">Jenis Laporan:</span> <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $report->report_type)) }}</span></div>
        <div><span class="text-slate-500">Periode:</span> <span class="font-medium">{{ $report->periodLabel() }}</span></div>
        <div><span class="text-slate-500">Tanggal Dibuat:</span> <span class="font-medium">{{ optional($report->generated_at)->format('d-m-Y H:i') }}</span></div>
        <div><span class="text-slate-500">Dibuat Oleh:</span> <span class="font-medium">{{ $report->generatedBy->name ?? '-' }}</span></div>
        <div><span class="text-slate-500">Status Dokumen:</span> <span class="font-medium">{{ $report->statusLabel() }}</span></div>
    </div>
    <p class="text-xs text-slate-500">Halaman ini hanya menampilkan metadata validasi dokumen, bukan isi laporan lengkap.</p>
    @endif
</div>
</body>
</html>
