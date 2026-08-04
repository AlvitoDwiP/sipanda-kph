@extends('layouts.master')

@section('title', 'Dashboard Monitoring')

@section('content')

{{-- Welcome Greeting and Filters Card Section --}}
<div class="grid gap-6 lg:grid-cols-3 items-stretch select-none">
    <!-- Welcome card -->
    <div class="lg:col-span-2 bg-gradient-to-br from-ui-primary to-ui-primary-hover rounded-ui-xl p-6 text-white shadow-ui-md flex flex-col justify-between relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute -left-10 -bottom-10 w-36 h-36 bg-ui-success-soft/10 rounded-full blur-xl"></div>
        
        <div class="relative min-w-0">
            <x-ui.badge variant="success" styleType="solid" size="sm" class="bg-white/20 border-white/10 text-white">
                Sistem SIPANDA
            </x-ui.badge>
            <h1 class="text-heading-3 font-extrabold tracking-tight mt-3 text-white">
                Selamat Datang Kembali, {{ auth()->user()->name }}!
            </h1>
            <p class="text-xs text-white/80 mt-1 max-w-xl leading-relaxed">
                Anda login sebagai <span class="font-bold text-white">{{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}</span>. Pantau dan tindak lanjuti pekerjaan harian pegawai Perhutani secara real-time.
            </p>
        </div>
        
        <div class="mt-8 text-white/60 text-[10.5px] font-semibold flex items-center gap-2">
            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
            <span>Pembaruan sistem terakhir: {{ now()->translatedFormat('d F Y H:i') }} WIB</span>
        </div>
    </div>
    
    <!-- Filter card -->
    <div class="bg-ui-surface rounded-ui-xl border border-ui-border p-5 shadow-ui-sm flex flex-col justify-between">
        <div class="border-b border-ui-border pb-2.5 mb-3">
            <h3 class="text-xs sm:text-sm font-bold text-ui-text-primary flex items-center gap-2">
                <i data-lucide="filter" class="w-4 h-4 text-ui-primary"></i>
                Filter Monitoring
            </h3>
            <p class="text-[10px] text-ui-text-secondary mt-0.5">Saring data monitoring berdasarkan tanggal dan unit kerja</p>
        </div>
        
        <form method="GET" class="flex-1 flex flex-col gap-3 justify-center">
            <x-ui.input 
                type="date" 
                name="tanggal" 
                label="TANGGAL TARGET" 
                value="{{ $tanggalFilter }}" 
            />
            
            <x-ui.input 
                type="select" 
                name="unit_kerja_id" 
                label="UNIT KERJA"
            >
                <option value="">Semua Unit Kerja</option>
                @foreach($unitKerjaList as $unit)
                    <option value="{{ $unit->id }}" @selected((string)$unitKerjaId === (string)$unit->id)>
                        {{ $unit->nama_unitkerja }}
                    </option>
                @endforeach
            </x-ui.input>
            
            <div class="pt-2 flex justify-end">
                <x-ui.button type="submit" variant="primary" size="sm" leadingIcon="check" class="w-full sm:w-auto">
                    Terapkan Filter
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

{{-- Spacing --}}
<div class="h-1"></div>

{{-- Section Headers and Stats for Tasks --}}
<div>
    <x-ui.section-header title="Statistik Penugasan Pegawai" description="Ringkasan tugas per tanggal {{ \Carbon\Carbon::parse($tanggalFilter)->format('d-m-Y') }} untuk unit kerja terpilih." />
    
    <div class="grid gap-4 grid-cols-2 md:grid-cols-4 lg:grid-cols-7 select-none">
        <x-ui.card variant="statistics" title="Total Tugas" :value="$summaryTugas['total_tugas']" icon="clipboard-list" trend="Semua" trendType="neutral" />
        <x-ui.card variant="statistics" title="Belum Mulai" :value="$summaryTugas['tugas_belum_dikerjakan']" icon="clock" trend="Pending" trendType="neutral" />
        <x-ui.card variant="statistics" title="Dikerjakan" :value="$summaryTugas['tugas_sedang_dikerjakan']" icon="play" trend="Aktif" trendType="neutral" />
        <x-ui.card variant="statistics" title="Menunggu Verif" :value="$summaryTugas['tugas_menunggu_verifikasi']" icon="eye" trend="Review" trendType="neutral" />
        <x-ui.card variant="statistics" title="Revisi" :value="$summaryTugas['tugas_revisi']" icon="alert-triangle" trend="Revisi" trendType="neutral" />
        <x-ui.card variant="statistics" title="Selesai" :value="$summaryTugas['tugas_selesai']" icon="check-circle" trend="Tuntas" trendType="up" />
        <x-ui.card variant="statistics" title="Terlambat" :value="$summaryTugas['tugas_terlambat']" icon="alert-circle" trend="Delay" trendType="down" />
    </div>
</div>

{{-- Section Headers and Stats for Activity Logs --}}
<div>
    <x-ui.section-header title="Statistik Catatan Kegiatan" description="Status pengajuan laporan aktivitas harian pegawai." />
    
    <div class="grid gap-4 grid-cols-2 lg:grid-cols-4 select-none">
        <x-ui.card variant="statistics" title="Menunggu Verifikasi" :value="$summaryCatatan['catatan_menunggu_verifikasi']" icon="clock" trend="Review" trendType="neutral" />
        <x-ui.card variant="statistics" title="Disetujui Hari Ini" :value="$summaryCatatan['catatan_disetujui_hari_ini']" icon="check-circle" trend="Tuntas" trendType="up" />
        <x-ui.card variant="statistics" title="Revisi" :value="$summaryCatatan['catatan_revisi']" icon="alert-triangle" trend="Revisi" trendType="neutral" />
        <x-ui.card variant="statistics" title="Ditolak" :value="$summaryCatatan['catatan_ditolak']" icon="x-circle" trend="Ditolak" trendType="down" />
    </div>
</div>

{{-- Chart and Activity Row --}}
<div class="grid gap-6 lg:grid-cols-3 items-stretch">
    <!-- Chart Overview -->
    <div class="lg:col-span-2 bg-ui-surface rounded-ui-xl border border-ui-border p-5 shadow-ui-sm flex flex-col justify-between">
        <div class="flex items-center justify-between border-b border-ui-border pb-3.5 mb-4 select-none">
            <div>
                <h3 class="text-xs sm:text-sm font-bold text-ui-text-primary">Progres per Unit Kerja</h3>
                <p class="text-[10px] text-ui-text-secondary mt-0.5">Visualisasi perbandingan jumlah tugas selesai versus total tugas</p>
            </div>
            <span class="p-2 bg-ui-primary-soft text-ui-primary rounded-ui-lg border border-ui-primary/10">
                <i data-lucide="bar-chart-3" class="w-4.5 h-4.5"></i>
            </span>
        </div>
        <div class="flex-1 min-h-[260px] relative">
            <canvas id="unitkerja-chart"></canvas>
        </div>
    </div>
    
    <!-- Timeline Section -->
    <div class="bg-ui-surface rounded-ui-xl border border-ui-border p-5 shadow-ui-sm flex flex-col justify-between">
        <div class="flex items-center justify-between border-b border-ui-border pb-3.5 mb-4 select-none">
            <div>
                <h3 class="text-xs sm:text-sm font-bold text-ui-text-primary">Pengajuan Baru</h3>
                <p class="text-[10px] text-ui-text-secondary mt-0.5">Catatan kegiatan pegawai yang menunggu review</p>
            </div>
            <span class="p-2 bg-ui-primary-soft text-ui-primary rounded-ui-lg border border-ui-primary/10">
                <i data-lucide="activity" class="w-4.5 h-4.5"></i>
            </span>
        </div>
        
        <div class="flex-1 overflow-y-auto max-h-[260px] pr-1 custom-scrollbar">
            @php
                $timelineItems = [];
                foreach ($catatanMenungguVerifikasi->take(5) as $item) {
                    $pegawaiName = $item->pegawai->user->name ?? 'Pegawai';
                    $tugasJudul = $item->penugasan->tugas->judul ?? 'Tugas';
                    $timestamp = $item->tanggal_kegiatan ? $item->tanggal_kegiatan->format('d-m-Y') : ($item->created_at ? $item->created_at->format('d-m-Y H:i') : '-');
                    
                    $timelineItems[] = [
                        'icon' => 'file-check',
                        'timestamp' => $timestamp,
                        'description' => "<strong>{$pegawaiName}</strong> menyerahkan laporan untuk tugas: <span class='text-ui-primary font-bold'>{$tugasJudul}</span>",
                        'details' => $item->judul . ': ' . Str::limit($item->deskripsi, 60),
                    ];
                }
            @endphp
            
            <x-ui.activity-timeline :items="$timelineItems" />
        </div>
    </div>
</div>

{{-- Urgent and Overdue Tasks Lists --}}
<div class="grid gap-6 lg:grid-cols-2">
    <!-- Urgent tasks list -->
    <x-ui.card title="Tugas Mendesak (Prioritas Tinggi)" icon="alert-octagon" subtitle="Daftar pekerjaan prioritas tinggi yang mendekati batas waktu">
        <div class="overflow-x-auto custom-scrollbar">
            <x-ui.table :headers="['Tugas', 'Pegawai', 'Deadline', 'Aksi']" :empty="$tugasMendesak->isEmpty()">
                @foreach($tugasMendesak as $item)
                    <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                        <td class="px-4 py-3 font-semibold">
                            <span class="text-ui-text-primary text-[12.5px]">{{ $item->tugas->judul ?? '-' }}</span>
                            <div class="flex items-center gap-1.5 mt-1 select-none">
                                <x-ui.badge variant="neutral" styleType="soft" size="sm">
                                    {{ $item->status }}
                                </x-ui.badge>
                                <span class="text-[10px] text-ui-text-secondary font-bold">
                                    {{ $item->progres_persen ?? 0 }}% selesai
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-ui-text-primary">{{ $item->pegawai->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-ui-text-secondary font-bold">{{ optional($item->tugas->deadline)->format('d-m-Y') ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            <x-ui.button variant="ghost" size="xs" leadingIcon="eye" href="{{ route($routePrefix . '.penugasan.show', $item->tugas_id) }}">
                                Detail
                            </x-ui.button>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        </div>
    </x-ui.card>

    <!-- Overdue tasks list -->
    <x-ui.card title="Tugas Terlambat" icon="alert-circle" subtitle="Daftar pekerjaan yang melewati batas waktu pengumpulan" class="border-red-200">
        <div class="overflow-x-auto custom-scrollbar">
            <x-ui.table :headers="['Tugas', 'Pegawai', 'Unit Kerja', 'Aksi']" :empty="$tugasTerlambat->isEmpty()">
                @foreach($tugasTerlambat as $item)
                    <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                        <td class="px-4 py-3 font-semibold">
                            <span class="text-ui-danger text-[12.5px]">{{ $item->tugas->judul ?? '-' }}</span>
                            <div class="mt-1 text-[10px] text-ui-text-secondary select-none">
                                Batas waktu: <span class="font-bold text-ui-danger">{{ optional($item->tugas->deadline)->format('d-m-Y') ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-ui-text-primary">{{ $item->pegawai->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-ui-text-secondary font-medium">{{ $item->pegawai->unitkerja->nama_unitkerja ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            <x-ui.button variant="ghost" size="xs" leadingIcon="eye" href="{{ route($routePrefix . '.penugasan.show', $item->tugas_id) }}">
                                Detail
                            </x-ui.button>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        </div>
    </x-ui.card>
</div>

{{-- Activity and Status tables --}}
<div class="grid gap-6 lg:grid-cols-2">
    <!-- Catatan Verifikasi table -->
    <x-ui.card title="Verifikasi Laporan Kegiatan" icon="check-square" subtitle="Pengajuan laporan kegiatan yang membutuhkan persetujuan">
        <div class="overflow-x-auto custom-scrollbar">
            <x-ui.table :headers="['Pegawai', 'Pekerjaan', 'Tanggal Laporan', 'Aksi']" :empty="$catatanMenungguVerifikasi->isEmpty()">
                @foreach($catatanMenungguVerifikasi as $item)
                    <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                        <td class="px-4 py-3 font-semibold text-ui-text-primary">
                            {{ $item->pegawai->user->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-ui-text-primary">
                            {{ $item->penugasan->tugas->judul ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-ui-text-secondary font-bold">
                            {{ optional($item->tanggal_kegiatan)->format('d-m-Y') ?? $item->created_at?->format('d-m-Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <x-ui.button variant="outline" size="xs" leadingIcon="check" href="{{ route($routePrefix . '.catatan_kegiatan.show', $item->id) }}">
                                Verifikasi
                            </x-ui.button>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        </div>
    </x-ui.card>

    <!-- Employees without daily update list -->
    <x-ui.card title="Pegawai Belum Update Progres Hari Ini" icon="user-x" subtitle="Daftar pegawai yang memiliki tugas aktif tapi belum memperbarui data progres">
        <div class="overflow-x-auto custom-scrollbar">
            <x-ui.table :headers="['Pegawai', 'Unit Kerja', 'Tugas Aktif', 'Belum Diperbarui']" :empty="empty($pegawaiBelumUpdate)">
                @foreach($pegawaiBelumUpdate as $row)
                    <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                        <td class="px-4 py-3 font-semibold text-ui-text-primary">{{ $row['pegawai']->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-ui-text-secondary font-medium">{{ $row['pegawai']->unitkerja->nama_unitkerja ?? '-' }}</td>
                        <td class="px-4 py-3 text-ui-text-primary text-center font-bold">{{ $row['jumlah_tugas_hari_ini'] }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center bg-ui-warning-soft text-ui-warning border border-ui-warning/15 px-2 py-0.5 rounded-full text-[10.5px] font-bold shadow-sm">
                                {{ $row['jumlah_belum_update'] }} tugas
                            </span>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        </div>
    </x-ui.card>
</div>

{{-- Unit Summary stats table --}}
<x-ui.card title="Progres Detail Per Unit Kerja" icon="building" subtitle="Statistik kumulatif penugasan di seluruh divisi unit kerja">
    <div class="overflow-x-auto custom-scrollbar">
        <x-ui.table :headers="['Unit Kerja', 'Total Tugas', 'Selesai', 'Belum Selesai', 'Terlambat', '% Selesai']" :empty="empty($summaryUnitKerja)">
            @foreach($summaryUnitKerja as $unit)
                <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                    <td class="px-4 py-3 font-semibold text-ui-text-primary">{{ $unit['nama_unitkerja'] }}</td>
                    <td class="px-4 py-3 text-ui-text-primary font-bold">{{ $unit['total_tugas'] }}</td>
                    <td class="px-4 py-3 text-ui-success font-bold">{{ $unit['selesai'] }}</td>
                    <td class="px-4 py-3 text-ui-text-secondary font-semibold">{{ $unit['belum_selesai'] }}</td>
                    <td class="px-4 py-3 text-ui-danger font-bold">{{ $unit['terlambat'] }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 w-20 bg-ui-primary-soft rounded-full h-1.5 overflow-hidden border border-ui-border">
                                <div class="bg-ui-success h-full" style="width: {{ $unit['persentase_selesai'] }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-ui-text-primary shrink-0">{{ $unit['persentase_selesai'] }}%</span>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </div>
</x-ui.card>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('unitkerja-chart');
        if (!ctx) return;
        
        const summaryData = @json($summaryUnitKerja);
        const labels = summaryData.map(item => item.nama_unitkerja);
        const selesai = summaryData.map(item => item.selesai);
        const total = summaryData.map(item => item.total_tugas);
        
        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Tugas Selesai',
                        data: selesai,
                        backgroundColor: '#059669', // ui-success green
                        hoverBackgroundColor: '#047857',
                        borderRadius: 5,
                        maxBarThickness: 32,
                    },
                    {
                        label: 'Total Tugas',
                        data: total,
                        backgroundColor: '#E0E8DF', // ui-border gray-green
                        hoverBackgroundColor: '#cbd5e1',
                        borderRadius: 5,
                        maxBarThickness: 32,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: { size: 10, family: 'Inter', weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1F2933',
                        titleFont: { size: 11, family: 'Inter', weight: 'bold' },
                        bodyFont: { size: 11, family: 'Inter' },
                        padding: 10,
                        cornerRadius: 6,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F1', drawTicks: false },
                        border: { dash: [4, 4] },
                        ticks: { font: { size: 10, family: 'Inter' }, stepSize: 2 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, family: 'Inter', weight: '500' } }
                    }
                }
            }
        });
    });
</script>
@endpush
