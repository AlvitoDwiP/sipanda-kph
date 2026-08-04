<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Validasi Laporan | SIPANDA-KPH</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#F1F5F1',
                            500: '#064E3B',
                            600: '#055040',
                            900: '#022c22',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #F6F8F5;
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex flex-col justify-between py-10 px-4">

    <div class="w-full max-w-2xl mx-auto space-y-6 my-auto">
        <!-- Logo / Brand Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-brand-500 text-white font-extrabold text-lg shadow-md">
                S
            </div>
            <div>
                <h1 class="text-xs font-extrabold tracking-widest text-brand-900 uppercase">SIPANDA-KPH</h1>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">PERUM PERHUTANI</p>
            </div>
        </div>

        <!-- Verification Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
            <!-- Header Pattern (Subtle decorative green bar) -->
            <div class="absolute top-0 inset-x-0 h-2 @if($status === 'valid') bg-emerald-500 @elseif($status === 'revoked') bg-amber-500 @else bg-red-500 @endif"></div>

            <!-- Status Icon and Title -->
            <div class="flex flex-col items-center text-center space-y-3 pb-6 border-b border-slate-100">
                @if($status === 'valid')
                    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner border border-emerald-100">
                        <i data-lucide="shield-check" class="w-9 h-9"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Dokumen Laporan Valid</h2>
                    <p class="text-xs text-slate-500 max-w-sm leading-relaxed">
                        Laporan ini terdaftar secara resmi di dalam database SIPANDA-KPH dan dinyatakan berlaku.
                    </p>
                @elseif($status === 'revoked')
                    <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shadow-inner border border-amber-100">
                        <i data-lucide="shield-alert" class="w-9 h-9"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Dokumen Laporan Dicabut</h2>
                    <p class="text-xs text-slate-500 max-w-sm leading-relaxed">
                        Dokumen ini pernah diterbitkan namun telah dicabut atau dinyatakan tidak berlaku lagi oleh pihak berwenang.
                    </p>
                @else
                    <div class="w-16 h-16 rounded-full bg-red-50 text-red-600 flex items-center justify-center shadow-inner border border-red-100">
                        <i data-lucide="shield-ban" class="w-9 h-9"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Dokumen Tidak Ditemukan</h2>
                    <p class="text-xs text-slate-500 max-w-sm leading-relaxed">
                        Tautan atau token validasi tidak sah. Dokumen tidak terdaftar di dalam sistem SIPANDA-KPH.
                    </p>
                @endif
            </div>

            <!-- Metadata Details Table -->
            @if($report)
                <div class="pt-6 space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Metadata Dokumen</h3>
                    <div class="bg-slate-50/50 rounded-xl border border-slate-100 divide-y divide-slate-100">
                        <div class="flex flex-col sm:flex-row p-3.5 gap-1 sm:gap-4">
                            <span class="text-xs font-semibold text-slate-400 sm:w-1/3">Kode Dokumen</span>
                            <span class="text-xs font-bold text-slate-800 sm:w-2/3 select-all">{{ $report->report_code }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row p-3.5 gap-1 sm:gap-4">
                            <span class="text-xs font-semibold text-slate-400 sm:w-1/3">Jenis Laporan</span>
                            <span class="text-xs font-semibold text-slate-800 sm:w-2/3 capitalize">
                                Laporan {{ str_replace('_', ' ', $report->report_type) }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row p-3.5 gap-1 sm:gap-4">
                            <span class="text-xs font-semibold text-slate-400 sm:w-1/3">Periode Dokumen</span>
                            <span class="text-xs font-semibold text-slate-800 sm:w-2/3">{{ $report->periodLabel() }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row p-3.5 gap-1 sm:gap-4">
                            <span class="text-xs font-semibold text-slate-400 sm:w-1/3">Tanggal Penerbitan</span>
                            <span class="text-xs font-semibold text-slate-800 sm:w-2/3">
                                {{ optional($report->generated_at)->translatedFormat('d F Y H:i') }} WIB
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row p-3.5 gap-1 sm:gap-4">
                            <span class="text-xs font-semibold text-slate-400 sm:w-1/3">Diterbitkan Oleh</span>
                            <span class="text-xs font-semibold text-slate-800 sm:w-2/3">{{ $report->generatedBy->name ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row p-3.5 gap-1 sm:gap-4">
                            <span class="text-xs font-semibold text-slate-400 sm:w-1/3">Status Keabsahan</span>
                            <span class="sm:w-2/3 flex items-center">
                                @if($status === 'valid')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif / Berlaku
                                    </span>
                                @elseif($status === 'revoked')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Dicabut
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div class="pt-6 text-center">
                    <p class="text-xs text-slate-400">
                        Pastikan Anda memindai kode QR dari berkas cetak resmi yang dikeluarkan oleh instansi KPH Perhutani.
                    </p>
                </div>
            @endif
        </div>

        <!-- Security Notice -->
        <div class="flex gap-3 p-4 bg-slate-100 border border-slate-200 rounded-xl">
            <div class="text-slate-400 shrink-0">
                <i data-lucide="info" class="w-5 h-5"></i>
            </div>
            <p class="text-[10px] text-slate-500 leading-normal">
                <strong>Catatan Keamanan:</strong> Halaman ini diterbitkan secara resmi oleh sistem administrasi SIPANDA-KPH untuk memverifikasi keabsahan dokumen fisik/digital. Isi laporan lengkap tidak ditampilkan untuk menjaga kerahasiaan data internal pegawai.
            </p>
        </div>
    </div>

    <!-- Footer Copyright -->
    <div class="text-center py-4 text-[10px] font-semibold text-slate-400 tracking-wider">
        &copy; {{ date('Y') }} SIPANDA-KPH Perum Perhutani. All rights reserved.
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
