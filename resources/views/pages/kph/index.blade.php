@extends('layouts.master')

@section('title', 'Dashboard KPH')

@section('content')

{{-- Welcome Greeting and Filters Card Section --}}
<div class="grid gap-4 lg:grid-cols-3 items-stretch select-none mb-4">
    <!-- Welcome card -->
    <div class="lg:col-span-2 bg-gradient-to-br from-ui-primary to-ui-primary-hover rounded-ui-xl p-4 sm:px-5 sm:py-4 text-white shadow-ui-sm flex flex-col justify-between relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute -left-12 -bottom-12 w-28 h-28 bg-ui-success-soft/10 rounded-full blur-xl"></div>
        
        <div class="relative min-w-0">
            <span class="text-[9px] uppercase font-extrabold tracking-widest text-white/80 bg-white/10 px-2 py-0.5 rounded border border-white/5">
                Sistem SIPANDA - KPH
            </span>
            <h1 class="text-lg sm:text-xl font-extrabold tracking-tight mt-1.5 text-white">
                Selamat Datang Kembali, {{ auth()->user()->name }}!
            </h1>
            <p class="text-[11px] sm:text-xs text-white/85 mt-1 max-w-xl leading-normal font-medium">
                Anda login sebagai <span class="font-bold text-white">Kepala Kesatuan Pengelolaan Hutan (KPH)</span>. Kelola dan pantau kinerja penugasan pegawai di wilayah kerja Anda secara real-time.
            </p>
        </div>
        
        <div class="mt-3 text-white/60 text-[10px] font-semibold flex items-center gap-1.5 border-t border-white/10 pt-2 w-max">
            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
            <span>Sistem Diperbarui: {{ now()->translatedFormat('d F Y H:i') }} WIB</span>
        </div>
    </div>
    
    <!-- Filter card -->
    <div class="bg-ui-surface rounded-ui-xl border border-ui-border p-4 shadow-ui-sm flex flex-col justify-between">
        <div class="border-b border-ui-border pb-1.5 mb-2">
            <h3 class="text-xs font-bold text-ui-text-primary flex items-center gap-1.5">
                <i data-lucide="filter" class="w-3.5 h-3.5 text-ui-primary"></i>
                Filter Monitoring KPH
            </h3>
            <p class="text-[9.5px] text-ui-text-secondary mt-0.5">Saring data berdasarkan tanggal & unit kerja</p>
        </div>
        
        <form method="GET" class="flex-1 flex flex-col gap-2 justify-center">
            <div class="grid grid-cols-2 gap-2">
                <x-ui.input 
                    type="date" 
                    name="tanggal" 
                    label="TANGGAL TARGET" 
                    value="{{ $tanggalFilter }}" 
                    class="py-1 px-2 text-xs"
                />
                
                <x-ui.input 
                    type="select" 
                    name="unit_kerja_id" 
                    label="UNIT KERJA"
                    class="py-1 px-2 text-xs"
                >
                    <option value="">Semua</option>
                    @foreach($unitKerjaList as $unit)
                        <option value="{{ $unit->id }}" @selected((string)$unitKerjaId === (string)$unit->id)>
                            {{ Str::limit($unit->nama_unitkerja, 12) }}
                        </option>
                    @endforeach
                </x-ui.input>
            </div>
            
            <div class="flex justify-end pt-1">
                <x-ui.button type="submit" variant="primary" size="sm" leadingIcon="check" class="w-full">
                    Terapkan Filter
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

{{-- Section Headers and Stats for Tasks --}}
<div class="mb-4">
    <x-ui.section-header title="Statistik Penugasan Pegawai" description="Ringkasan tugas per tanggal {{ \Carbon\Carbon::parse($tanggalFilter)->format('d-m-Y') }} untuk wilayah KPH." class="mb-2" />
    
    <div class="grid gap-3 grid-cols-2 md:grid-cols-4 lg:grid-cols-7 select-none">
        <x-ui.card variant="statistics" title="Total Tugas" :value="$summaryTugas['total_tugas']" icon="clipboard-list" trend="Semua" trendType="neutral" shortContext="Tugas terdaftar" />
        <x-ui.card variant="statistics" title="Belum Mulai" :value="$summaryTugas['tugas_belum_dikerjakan']" icon="clock" trend="Pending" trendType="neutral" shortContext="Belum diproses" />
        <x-ui.card variant="statistics" title="Dikerjakan" :value="$summaryTugas['tugas_sedang_dikerjakan']" icon="play" trend="Proses" trendType="neutral" shortContext="Sedang berjalan" />
        <x-ui.card variant="statistics" title="Menunggu Verif" :value="$summaryTugas['tugas_menunggu_verifikasi']" icon="eye" trend="Verif" trendType="warning" shortContext="Butuh review" />
        <x-ui.card variant="statistics" title="Revisi" :value="$summaryTugas['tugas_revisi']" icon="alert-triangle" trend="Revisi" trendType="warning" shortContext="Perlu perbaikan" />
        <x-ui.card variant="statistics" title="Selesai" :value="$summaryTugas['tugas_selesai']" icon="check-circle" trend="Tuntas" trendType="up" shortContext="Berhasil selesai" />
        <x-ui.card variant="statistics" title="Terlambat" :value="$summaryTugas['tugas_terlambat']" icon="alert-circle" trend="Telat" trendType="down" shortContext="Lewat tenggat" />
    </div>
</div>

{{-- Section Headers and Stats for Activity Logs --}}
<div class="mb-4">
    <x-ui.section-header title="Statistik Catatan Kegiatan" description="Status laporan aktivitas harian pegawai." class="mb-2" />
    
    <div class="grid gap-3 grid-cols-2 lg:grid-cols-4 select-none">
        <x-ui.card variant="statistics" title="Menunggu Verifikasi" :value="$summaryCatatan['catatan_menunggu_verifikasi']" icon="clock" trend="Review" trendType="neutral" shortContext="Antrean verifikasi" />
        <x-ui.card variant="statistics" title="Disetujui Hari Ini" :value="$summaryCatatan['catatan_disetujui_hari_ini']" icon="check-circle" trend="Tuntas" trendType="up" shortContext="Disetujui hari ini" />
        <x-ui.card variant="statistics" title="Revisi Laporan" :value="$summaryCatatan['catatan_revisi']" icon="alert-triangle" trend="Revisi" trendType="neutral" shortContext="Perlu diperbaiki" />
        <x-ui.card variant="statistics" title="Laporan Ditolak" :value="$summaryCatatan['catatan_ditolak']" icon="x-circle" trend="Ditolak" trendType="down" shortContext="Ditolak verifikator" />
    </div>
</div>

{{-- Chart and Activity Row --}}
<div class="grid gap-4 lg:grid-cols-3 items-stretch mb-4">
    <!-- Chart Overview -->
    <div class="lg:col-span-2 bg-ui-surface rounded-ui-xl border border-ui-border p-4 shadow-ui-sm flex flex-col justify-between">
        <div class="flex items-center justify-between border-b border-ui-border pb-2.5 mb-3 select-none">
            <div>
                <h3 class="text-xs sm:text-sm font-bold text-ui-text-primary">Progres per Unit Kerja KPH</h3>
                <p class="text-[10px] text-ui-text-secondary mt-0.5">Visualisasi perbandingan jumlah tugas selesai versus total tugas</p>
            </div>
            <span class="p-1.5 bg-ui-primary-soft text-ui-primary rounded-ui-lg border border-ui-primary/10">
                <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
            </span>
        </div>
        <div class="flex-1 min-h-[220px] relative">
            <canvas id="unitkerja-chart"></canvas>
        </div>
    </div>
    
    <!-- Timeline Section -->
    <div class="bg-ui-surface rounded-ui-xl border border-ui-border p-4 shadow-ui-sm flex flex-col justify-between">
        <div class="flex items-center justify-between border-b border-ui-border pb-2.5 mb-3 select-none">
            <div>
                <h3 class="text-xs sm:text-sm font-bold text-ui-text-primary">Pengajuan Baru</h3>
                <p class="text-[10px] text-ui-text-secondary mt-0.5">Laporan kegiatan pegawai KPH menunggu persetujuan</p>
            </div>
            <span class="p-1.5 bg-ui-primary-soft text-ui-primary rounded-ui-lg border border-ui-primary/10">
                <i data-lucide="activity" class="w-4 h-4"></i>
            </span>
        </div>
        
        <div class="flex-1 overflow-y-auto max-h-[220px] pr-1 custom-scrollbar">
            @php
                $timelineItems = [];
                foreach ($catatanMenungguVerifikasi->take(5) as $item) {
                    $pegawaiName = $item->pegawai->user->name ?? 'Pegawai';
                    $tugasJudul = $item->penugasan->tugas->judul ?? 'Tugas';
                    $relativeTime = $item->created_at ? $item->created_at->diffForHumans() : ($item->tanggal_kegiatan ? $item->tanggal_kegiatan->diffForHumans() : '-');
                    
                    $timelineItems[] = [
                        'icon' => 'file-check',
                        'timestamp' => $relativeTime,
                        'description' => "<strong>{$pegawaiName}</strong> menyerahkan laporan untuk tugas: <span class='text-ui-primary font-semibold'>{$tugasJudul}</span>",
                        'details' => $item->judul . ': ' . Str::limit($item->deskripsi, 60),
                    ];
                }
            @endphp
            
            @if ($catatanMenungguVerifikasi->isEmpty())
                <x-ui.empty-state 
                    icon="activity" 
                    title="Tidak Ada Pengajuan Laporan" 
                    description="Belum ada laporan kegiatan baru dari pegawai yang menunggu verifikasi." 
                >
                    <x-slot name="primaryAction">
                        <x-ui.button variant="outline" size="sm" leadingIcon="refresh-cw" href="{{ route($routePrefix . '.dashboard') }}">
                            Perbarui Data
                        </x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @else
                <x-ui.activity-timeline :items="$timelineItems" />
            @endif
        </div>
    </div>
</div>

{{-- Urgent and Overdue Tasks Lists --}}
<div class="grid gap-4 lg:grid-cols-2 mb-4">
    <!-- Urgent tasks list -->
    <x-ui.card title="Tugas Mendesak (Prioritas Tinggi)" icon="alert-octagon" subtitle="Daftar pekerjaan prioritas tinggi KPH yang mendekati batas waktu">
        <div class="overflow-x-auto custom-scrollbar">
            <x-ui.table :headers="['Tugas', 'Pegawai', 'Deadline', 'Aksi']" :empty="$tugasMendesak->isEmpty()">
                <x-slot name="emptyState">
                    <x-ui.empty-state 
                        icon="alert-octagon" 
                        title="Tidak Ada Tugas Mendesak" 
                        description="Tidak ada penugasan prioritas tinggi yang mendekati batas waktu." 
                    >
                        <x-slot name="primaryAction">
                            <x-ui.button variant="outline" size="sm" leadingIcon="plus" href="{{ route($routePrefix . '.penugasan.create') }}">
                                Buat Penugasan
                            </x-ui.button>
                        </x-slot>
                    </x-ui.empty-state>
                </x-slot>

                @foreach($tugasMendesak as $item)
                    <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                        <td class="px-4 py-3 font-semibold">
                            <span class="text-ui-text-primary text-[12px] block leading-tight">{{ $item->tugas->judul ?? '-' }}</span>
                            <div class="flex items-center gap-1.5 mt-1 select-none">
                                <x-ui.badge variant="neutral" styleType="soft" size="sm">
                                    {{ $item->status }}
                                </x-ui.badge>
                                <span class="text-[9.5px] text-ui-text-secondary font-bold">
                                    {{ $item->progres_percent ?? $item->progres_persen ?? 0 }}% selesai
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-ui-text-primary text-[12px] font-medium">{{ $item->pegawai->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($item->tugas->deadline)
                                @php
                                    $deadlineDate = \Carbon\Carbon::parse($item->tugas->deadline);
                                    $daysLeft = now()->startOfDay()->diffInDays($deadlineDate, false);
                                    $badgeVariant = $daysLeft < 0 ? 'danger' : ($daysLeft <= 2 ? 'warning' : 'success');
                                    $badgeLabel = $daysLeft < 0 ? 'Terlewat' : ($daysLeft == 0 ? 'Hari Ini' : ($daysLeft == 1 ? 'Besok' : ($daysLeft == 2 ? '2 Hari Lagi' : $daysLeft . ' Hari Lagi')));
                                @endphp
                                <div class="flex flex-col gap-0.5 select-none">
                                    <span class="inline-flex items-center w-max px-1.5 py-0.2 rounded text-[9px] font-extrabold uppercase tracking-wider
                                        @if($daysLeft < 0) bg-ui-danger-soft text-ui-danger border border-ui-danger/10
                                        @elseif($daysLeft <= 2) bg-ui-warning-soft text-ui-warning border border-ui-warning/10
                                        @else bg-ui-success-soft text-ui-success border border-ui-success/10 @endif">
                                        {{ $badgeLabel }}
                                    </span>
                                    <span class="text-[9.5px] text-ui-text-secondary font-semibold mt-0.5">
                                        {{ $deadlineDate->format('d/m/Y') }}
                                    </span>
                                </div>
                            @else
                                <span class="text-ui-muted">-</span>
                            @endif
                        </td>
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
    <x-ui.card title="Tugas Terlambat" icon="alert-circle" subtitle="Daftar pekerjaan yang melewati batas waktu pengumpulan" class="border-red-100">
        <div class="overflow-x-auto custom-scrollbar">
            <x-ui.table :headers="['Tugas', 'Pegawai', 'Unit Kerja', 'Aksi']" :empty="$tugasTerlambat->isEmpty()">
                <x-slot name="emptyState">
                    <x-ui.empty-state 
                        icon="check-circle" 
                        title="Tidak Ada Tugas Terlambat" 
                        description="Hebat! Tidak ada penugasan KPH yang melewati batas waktu saat ini." 
                    >
                        <x-slot name="primaryAction">
                            <x-ui.button variant="outline" size="sm" leadingIcon="list-todo" href="{{ route($routePrefix . '.penugasan.index') }}">
                                Kelola Penugasan
                            </x-ui.button>
                        </x-slot>
                    </x-ui.empty-state>
                </x-slot>

                @foreach($tugasTerlambat as $item)
                    <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                        <td class="px-4 py-3 font-semibold">
                            <span class="text-ui-danger text-[12px] block leading-tight">{{ $item->tugas->judul ?? '-' }}</span>
                            @if($item->tugas->deadline)
                                @php
                                    $deadlineDate = \Carbon\Carbon::parse($item->tugas->deadline);
                                    $daysLeft = now()->startOfDay()->diffInDays($deadlineDate, false);
                                @endphp
                                <div class="mt-1 flex flex-col gap-0.5 select-none">
                                    <span class="inline-flex items-center w-max px-1.5 py-0.2 rounded text-[9px] font-extrabold uppercase tracking-wider bg-ui-danger-soft text-ui-danger border border-ui-danger/10">
                                        Terlambat ({{ abs($daysLeft) }} Hari)
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-ui-text-primary text-[12px] font-medium">{{ $item->pegawai->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-ui-text-secondary text-[12px] font-medium">{{ $item->pegawai->unitkerja->nama_unitkerja ?? '-' }}</td>
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
<div class="grid gap-4 lg:grid-cols-2 mb-4">
    <!-- Catatan Verifikasi table -->
    <x-ui.card title="Persetujuan Laporan Kegiatan" icon="check-square" subtitle="Pengajuan laporan kegiatan pegawai KPH yang membutuhkan verifikasi">
        <div class="overflow-x-auto custom-scrollbar">
            <x-ui.table :headers="['Pegawai', 'Pekerjaan', 'Tanggal Laporan', 'Aksi']" :empty="$catatanMenungguVerifikasi->isEmpty()">
                <x-slot name="emptyState">
                    <x-ui.empty-state 
                        icon="check-square" 
                        title="Laporan Bersih" 
                        description="Tidak ada pengajuan laporan kegiatan pegawai KPH yang membutuhkan verifikasi." 
                    >
                        <x-slot name="primaryAction">
                            <x-ui.button variant="outline" size="sm" leadingIcon="history" href="{{ route($routePrefix . '.catatan_kegiatan.index') }}">
                                Riwayat Laporan
                            </x-ui.button>
                        </x-slot>
                    </x-ui.empty-state>
                </x-slot>

                @foreach($catatanMenungguVerifikasi as $item)
                    <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                        <td class="px-4 py-3 font-semibold text-ui-text-primary text-[12px]">
                            {{ $item->pegawai->user->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-ui-text-primary text-[12px]">
                            {{ $item->penugasan->tugas->judul ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-0.5 select-none">
                                <span class="text-xs font-bold text-ui-text-primary">
                                    {{ optional($item->tanggal_kegiatan)->format('d/m/Y') ?? '-' }}
                                </span>
                                <span class="text-[9.5px] text-ui-text-secondary font-medium">
                                    {{ $item->created_at ? $item->created_at->diffForHumans() : '-' }}
                                </span>
                            </div>
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
    <x-ui.card title="Pegawai Belum Update Progres Hari Ini" icon="user-x" subtitle="Daftar pegawai yang memiliki tugas aktif tapi belum memperbarui progres">
        <div class="overflow-x-auto custom-scrollbar">
            <x-ui.table :headers="['Pegawai', 'Unit Kerja', 'Tugas Aktif', 'Belum Diperbarui']" :empty="empty($pegawaiBelumUpdate)">
                <x-slot name="emptyState">
                    <x-ui.empty-state 
                        icon="users" 
                        title="Semua Pegawai Ter-update" 
                        description="Seluruh pegawai dengan tugas aktif telah memperbarui progres kerjanya hari ini." 
                    >
                        <x-slot name="primaryAction">
                            <x-ui.button variant="outline" size="sm" leadingIcon="user-check" href="{{ route($routePrefix . '.pegawai.index') }}">
                                Lihat Direktori Pegawai
                            </x-ui.button>
                        </x-slot>
                    </x-ui.empty-state>
                </x-slot>

                @foreach($pegawaiBelumUpdate as $row)
                    <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                        <td class="px-4 py-3 font-semibold text-ui-text-primary text-[12px]">{{ $row['pegawai']->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-ui-text-secondary text-[12px] font-medium">{{ $row['pegawai']->unitkerja->nama_unitkerja ?? '-' }}</td>
                        <td class="px-4 py-3 text-ui-text-primary text-center font-bold text-[12px]">{{ $row['jumlah_tugas_hari_ini'] }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center bg-ui-warning-soft text-ui-warning border border-ui-warning/15 px-2 py-0.5 rounded-full text-[10px] font-extrabold shadow-sm">
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
<div class="mb-4">
    <x-ui.card title="Progres Detail Per Unit Kerja KPH" icon="building" subtitle="Statistik kumulatif penugasan di seluruh divisi KPH">
        <div class="overflow-x-auto custom-scrollbar">
            <x-ui.table :headers="['Unit Kerja', 'Total Tugas', 'Selesai', 'Belum Selesai', 'Terlambat', '% Selesai']" :empty="empty($summaryUnitKerja)">
                <x-slot name="emptyState">
                    <x-ui.empty-state 
                        icon="building" 
                        title="Tidak Ada Data Unit Kerja" 
                        description="Belum ada data progres unit kerja yang tercatat." 
                    />
                </x-slot>

                @foreach($summaryUnitKerja as $unit)
                    <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                        <td class="px-4 py-3 font-semibold text-ui-text-primary text-[12px]">{{ $unit['nama_unitkerja'] }}</td>
                        <td class="px-4 py-3 text-ui-text-primary font-bold text-[12px]">{{ $unit['total_tugas'] }}</td>
                        <td class="px-4 py-3 text-ui-success font-bold text-[12px]">{{ $unit['selesai'] }}</td>
                        <td class="px-4 py-3 text-ui-text-secondary font-semibold text-[12px]">{{ $unit['belum_selesai'] }}</td>
                        <td class="px-4 py-3 text-ui-danger font-bold text-[12px]">{{ $unit['terlambat'] }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 w-24 bg-ui-primary-soft rounded-full h-2 overflow-hidden border border-ui-border relative">
                                    <div class="bg-ui-success h-full transition-all duration-500 ease-out" style="width: {{ $unit['persentase_selesai'] }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-ui-text-primary shrink-0">{{ $unit['persentase_selesai'] }}%</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        </div>
    </x-ui.card>
</div>

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
