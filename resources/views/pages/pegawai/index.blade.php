@extends('layouts.master')

@section('title', 'Dashboard Pegawai')

@section('content')

{{-- Welcome Greeting Section --}}
<div class="bg-gradient-to-br from-ui-primary to-ui-primary-hover rounded-ui-xl p-4 sm:px-5 sm:py-4 text-white shadow-ui-sm relative overflow-hidden select-none mb-4">
    <div class="absolute -right-16 -top-16 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
    <div class="absolute -left-10 -bottom-10 w-28 h-28 bg-ui-success-soft/10 rounded-full blur-xl"></div>
    
    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-[9px] uppercase font-extrabold tracking-widest text-white/80 bg-white/10 px-2 py-0.5 rounded border border-white/5">
                    Dashboard Pegawai
                </span>
                @if(Auth::user()->pegawai?->isAktif())
                    <span class="inline-flex items-center gap-1 text-[9px] uppercase font-extrabold bg-ui-success px-2 py-0.5 rounded border border-white/10 text-white shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        Status: Aktif
                    </span>
                @endif
            </div>
            <h1 class="text-lg sm:text-xl font-extrabold tracking-tight mt-1.5 text-white">
                Selamat Datang Kembali, {{ auth()->user()->name }}!
            </h1>
            <p class="text-[11px] sm:text-xs text-white/80 mt-0.5 max-w-xl font-medium">
                Unit Kerja: <span class="font-bold text-white">{{ Auth::user()->pegawai?->unitkerja?->nama_unitkerja ?? '-' }}</span> 
                <span class="mx-1.5 text-white/40">|</span> 
                Jabatan: <span class="font-bold text-white">{{ Auth::user()->pegawai?->jabatan?->nama_jabatan ?? '-' }}</span>
            </p>
        </div>
    </div>
</div>

{{-- Statistics cards --}}
<div class="grid gap-4 mb-4 grid-cols-1 md:grid-cols-3 select-none">
    <x-ui.card variant="statistics" title="Jumlah Tugas Saya" :value="$stats['jumlah_tugas']" icon="clipboard-list" trend="Aktif" trendType="neutral" shortContext="Total seluruh penugasan kerja" />
    <x-ui.card variant="statistics" title="Tugas Belum Selesai" :value="$stats['tugas_belum_selesai']" icon="clock" trend="Pending" trendType="warning" shortContext="Membutuhkan tindak lanjut" />
    <x-ui.card variant="statistics" title="Catatan Kegiatan" :value="$stats['catatan_bulan_ini']" icon="file-text" trend="Bulan Ini" trendType="success" shortContext="Laporan kegiatan bulanan" />
</div>

{{-- Quick Actions Section --}}
<div class="mb-4">
    <div class="bg-ui-surface rounded-ui-xl border border-ui-border p-4 shadow-ui-sm">
        <div class="mb-3.5">
            <h3 class="text-xs sm:text-sm font-bold text-ui-text-primary flex items-center gap-2">
                <i data-lucide="zap" class="w-4 h-4 text-ui-warning"></i>
                Aksi Cepat
            </h3>
            <p class="text-[10px] text-ui-text-secondary mt-0.5">Pintasan cepat untuk memperbarui status pekerjaan harian Anda</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

            {{-- Card 1: Update Progress --}}
            <a href="{{ route('pegawai.tugas.index') }}"
               class="quick-action-card group flex flex-col gap-2 p-3.5 rounded-ui-lg border border-ui-border bg-ui-surface shadow-ui-sm cursor-pointer no-underline">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 rounded-ui-md bg-ui-primary-soft text-ui-primary border border-ui-primary/10 shrink-0 transition-colors group-hover:bg-ui-primary group-hover:text-white">
                        <i data-lucide="check-square" class="w-4 h-4"></i>
                    </div>
                    <span class="text-xs font-bold text-ui-text-primary leading-tight">Update Progress</span>
                </div>
                <p class="text-[10px] text-ui-text-secondary leading-relaxed">Perbarui progres dan status tugas yang sedang berjalan.</p>
                <span class="mt-auto inline-flex items-center gap-1 text-[10px] font-semibold text-ui-primary group-hover:gap-1.5 transition-all">
                    Buka <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </span>
            </a>

            {{-- Card 2: Tambah Laporan --}}
            <a href="{{ route('pegawai.catatan_kegiatan.index') }}"
               class="quick-action-card group flex flex-col gap-2 p-3.5 rounded-ui-lg border border-ui-border bg-ui-surface shadow-ui-sm cursor-pointer no-underline">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 rounded-ui-md bg-ui-success-soft text-ui-success border border-ui-success/10 shrink-0 transition-colors group-hover:bg-ui-success group-hover:text-white">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    </div>
                    <span class="text-xs font-bold text-ui-text-primary leading-tight">Tambah Laporan</span>
                </div>
                <p class="text-[10px] text-ui-text-secondary leading-relaxed">Catat kegiatan harian atau buat laporan kerja baru.</p>
                <span class="mt-auto inline-flex items-center gap-1 text-[10px] font-semibold text-ui-success group-hover:gap-1.5 transition-all">
                    Buka <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </span>
            </a>

            {{-- Card 3: Lihat Semua Tugas --}}
            <a href="{{ route('pegawai.tugas.index') }}"
               class="quick-action-card group flex flex-col gap-2 p-3.5 rounded-ui-lg border border-ui-border bg-ui-surface shadow-ui-sm cursor-pointer no-underline">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 rounded-ui-md bg-ui-info-soft text-ui-info border border-ui-info/10 shrink-0 transition-colors group-hover:bg-ui-info group-hover:text-white">
                        <i data-lucide="list-todo" class="w-4 h-4"></i>
                    </div>
                    <span class="text-xs font-bold text-ui-text-primary leading-tight">Lihat Semua Tugas</span>
                </div>
                <p class="text-[10px] text-ui-text-secondary leading-relaxed">Pantau seluruh daftar penugasan yang diberikan kepada Anda.</p>
                <span class="mt-auto inline-flex items-center gap-1 text-[10px] font-semibold text-ui-info group-hover:gap-1.5 transition-all">
                    Buka <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </span>
            </a>

        </div>
    </div>
</div>

{{-- Progress and Deadlines Row --}}
<div class="grid gap-4 lg:grid-cols-2 items-stretch mb-4">
    <!-- Progress circle card -->
    <x-ui.card title="Progres Tugas Bulan Ini" icon="trending-up" subtitle="Rasio penyelesaian tugas aktif bulanan Anda">
        <div class="flex flex-row items-center justify-around gap-4 py-1 select-none">
            <div class="relative flex items-center justify-center">
                <svg width="105" height="105" viewBox="0 0 36 36" class="transform -rotate-90">
                    <circle
                        cx="18"
                        cy="18"
                        r="15.915"
                        fill="transparent"
                        stroke="var(--ui-primary-soft)"
                        stroke-width="2.5" />
                    <circle
                        cx="18"
                        cy="18"
                        r="15.915"
                        fill="transparent"
                        stroke="var(--ui-success)"
                        stroke-width="2.5"
                        stroke-dasharray="{{ $progress_data['persentase_selesai'] }} {{ 100 - $progress_data['persentase_selesai'] }}"
                        stroke-linecap="round"
                        class="transition-all duration-500 ease-out" />
                </svg>
                <div class="absolute flex flex-col items-center justify-center">
                    <span class="text-xl font-extrabold text-ui-text-primary tracking-tight leading-none">
                        {{ $progress_data['persentase_selesai'] }}%
                    </span>
                    <span class="text-[8px] text-ui-text-secondary font-bold uppercase tracking-wider mt-0.5 leading-none">
                        SELESAI
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-2 min-w-0">
                <div class="flex items-center gap-2 text-xs">
                    <span class="w-2.5 h-2.5 rounded-full bg-ui-success shrink-0"></span>
                    <span class="text-ui-text-secondary font-semibold truncate">Selesai: <strong class="text-ui-text-primary font-bold">{{ $progress_data['jumlah_selesai'] }}</strong></span>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="w-2.5 h-2.5 rounded-full bg-ui-warning shrink-0"></span>
                    <span class="text-ui-text-secondary font-semibold truncate">Pending: <strong class="text-ui-text-primary font-bold">{{ $progress_data['jumlah_pending'] }}</strong></span>
                </div>
                <p class="text-[10px] text-ui-muted mt-1 leading-normal max-w-[150px]">
                    Selesaikan semua penugasan sebelum tenggat berakhir.
                </p>
            </div>
        </div>
    </x-ui.card>
    
    <!-- Upcoming deadlines card -->
    <x-ui.card title="Tenggat Waktu Terdekat" icon="clock" subtitle="Daftar tugas aktif Anda dengan tenggat waktu paling dekat">
        @php
            $upcomingDeadlines = $tugas_aktif->filter(fn($item) => $item->tugas && $item->tugas->deadline)
                ->sortBy(fn($item) => $item->tugas->deadline)
                ->take(3);
        @endphp
        
        <div class="space-y-2.5 py-1">
            @forelse($upcomingDeadlines as $item)
                <div class="flex items-center justify-between gap-3 border-b border-ui-border/50 pb-2 last:border-0 last:pb-0">
                    <div class="min-w-0">
                        <span class="text-xs font-bold text-ui-text-primary block truncate">
                            {{ $item->tugas->judul ?? '-' }}
                        </span>
                        <span class="text-[10px] text-ui-text-secondary mt-0.5 inline-block">
                            Status: <strong class="text-ui-primary">{{ ucfirst($item->status) }}</strong>
                        </span>
                    </div>
                    
                    @php
                        $deadlineDate = \Carbon\Carbon::parse($item->tugas->deadline);
                        $daysLeft = now()->startOfDay()->diffInDays($deadlineDate, false);
                        $badgeVariant = $daysLeft < 0 ? 'danger' : ($daysLeft <= 2 ? 'warning' : 'success');
                        $badgeLabel = $daysLeft < 0 ? 'Terlewat' : ($daysLeft == 0 ? 'Hari Ini' : ($daysLeft == 1 ? 'Besok' : ($daysLeft == 2 ? '2 Hari Lagi' : $daysLeft . ' Hari Lagi')));
                    @endphp
                    
                    <div class="text-right shrink-0 select-none">
                        <x-ui.badge :variant="$badgeVariant" styleType="soft" size="sm" class="text-[9px] font-bold">
                            {{ $badgeLabel }}
                        </x-ui.badge>
                        <span class="text-[9.5px] text-ui-text-secondary block mt-0.5 font-semibold">
                            {{ $deadlineDate->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center text-center py-4">
                    <div class="w-9 h-9 rounded-full bg-ui-success-soft text-ui-success flex items-center justify-center mb-1.5 shrink-0">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-xs font-bold text-ui-text-primary mb-0.5">Semua Tugas Aman</h4>
                    <p class="text-[10px] text-ui-text-secondary mb-2 max-w-[200px]">Tidak ada tenggat waktu penugasan terdekat.</p>
                    <x-ui.button variant="ghost" size="xs" leadingIcon="list-todo" href="{{ route('pegawai.tugas.index') }}">
                        Kelola Tugas
                    </x-ui.button>
                </div>
            @endforelse
        </div>
    </x-ui.card>
</div>

{{-- Tasks table list --}}
<div class="mb-4">
    <x-ui.card title="Daftar Tugas Aktif Saya" icon="clipboard-list" subtitle="Daftar seluruh penugasan kerja yang didelegasikan ke Anda saat ini">
        <div class="overflow-x-auto custom-scrollbar">
            <x-ui.table :headers="['No', 'Judul Tugas', 'Deskripsi Pekerjaan', 'Deadline', 'Status Tugas', 'Aksi']" :empty="$tugas_aktif->isEmpty()">
                <x-slot name="emptyState">
                    <x-ui.empty-state 
                        icon="clipboard-list" 
                        title="Belum Ada Tugas Aktif" 
                        description="Saat ini Anda tidak memiliki tugas aktif yang didelegasikan." 
                    >
                        <x-slot name="primaryAction">
                            <x-ui.button variant="primary" size="sm" leadingIcon="refresh-cw" href="{{ route('pegawai.dashboard') }}">
                                Segarkan Halaman
                            </x-ui.button>
                        </x-slot>
                    </x-ui.empty-state>
                </x-slot>

                @foreach($tugas_aktif as $index => $penugasan)
                    @php
                        $statusBadgeVariant = match($penugasan->status) {
                            'selesai' => 'success',
                            'proses' => 'warning',
                            'revisi' => 'danger',
                            default => 'neutral'
                        };
                    @endphp
                    <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                        <td class="px-6 py-4 text-ui-text-secondary font-bold">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-ui-text-primary">
                            {{ $penugasan->tugas->judul ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-ui-text-secondary max-w-xs truncate">
                            {{ $penugasan->tugas->deskripsi ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($penugasan->tugas->deadline)
                                @php
                                    $deadlineDate = \Carbon\Carbon::parse($penugasan->tugas->deadline);
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
                                    <span class="text-[10px] text-ui-text-secondary font-semibold mt-0.5">
                                        {{ $deadlineDate->format('d/m/Y') }}
                                    </span>
                                </div>
                            @else
                                <span class="text-ui-muted">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 select-none">
                            <x-ui.badge :variant="$statusBadgeVariant" styleType="soft" size="md">
                                {{ ucfirst($penugasan->status) }}
                            </x-ui.badge>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <x-ui.button variant="ghost" size="xs" leadingIcon="eye" href="{{ route('pegawai.tugas.index') }}">
                                Kelola
                            </x-ui.button>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        </div>
    </x-ui.card>
</div>

{{-- Activity and Notification Rows --}}
<div class="grid gap-4 lg:grid-cols-2">
    <!-- Activity Timeline card -->
    <x-ui.card title="Aktivitas Laporan Terbaru" icon="activity" subtitle="Catatan pengajuan laporan harian terakhir yang Anda submit">
        <div class="overflow-y-auto max-h-[300px] pr-1 custom-scrollbar">
            @php
                $recentCatatan = auth()->user()->pegawai?->catatanKegiatan()->latest()->take(5)->get() ?? collect();
                $timelineItems = [];
                foreach ($recentCatatan as $item) {
                    $tugasJudul = $item->penugasan->tugas->judul ?? 'Tugas';
                    $statusLabel = $item->status_verifikasi_label;
                    $statusIcon = match($item->status_verifikasi) {
                        'disetujui' => 'check-circle',
                        'revisi' => 'alert-triangle',
                        'ditolak' => 'x-circle',
                        default => 'clock',
                    };
                    $badgeVariant = match($item->status_verifikasi) {
                        'disetujui' => 'success',
                        'revisi' => 'warning',
                        'ditolak' => 'danger',
                        default => 'neutral',
                    };
                    $relativeTime = $item->created_at ? $item->created_at->diffForHumans() : ($item->tanggal_kegiatan ? $item->tanggal_kegiatan->diffForHumans() : '-');
                    
                    $timelineItems[] = [
                        'icon' => $statusIcon,
                        'timestamp' => $relativeTime,
                        'description' => "Menyerahkan laporan tugas: <strong class='text-ui-text-primary'>{$tugasJudul}</strong> <span class='ml-1 inline-flex items-center px-1.5 py-0.2 bg-ui-{$badgeVariant}-soft text-ui-{$badgeVariant} border border-ui-{$badgeVariant}/10 rounded text-[9px] font-bold'>{$statusLabel}</span>",
                        'details' => $item->judul,
                    ];
                }
            @endphp
            
            @if (count($timelineItems) > 0)
                <x-ui.activity-timeline :items="$timelineItems" />
            @else
                <x-ui.empty-state 
                    icon="activity" 
                    title="Belum Ada Laporan" 
                    description="Anda belum menyerahkan laporan kegiatan terbaru untuk penugasan Anda." 
                >
                    <x-slot name="primaryAction">
                        <x-ui.button variant="outline" size="sm" leadingIcon="plus-circle" href="{{ route('pegawai.tugas.index') }}">
                            Perbarui Tugas
                        </x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @endif
        </div>
    </x-ui.card>

    <!-- Latest system notification card -->
    <x-ui.card title="Pemberitahuan & Alert" icon="bell" subtitle="Notifikasi sistem terbaru yang dikirimkan untuk Anda">
        @php
            $dashNotifications = collect();
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                    $dashNotifications = auth()->user()->notifications()->take(5)->get();
                }
            } catch (\Exception $e) {}
        @endphp
        
        <div class="divide-y divide-ui-border/50 overflow-y-auto max-h-[300px] custom-scrollbar pr-1">
            @forelse ($dashNotifications as $notification)
                @php
                    $msg = $notification->data['message'] ?? $notification->data['judul'] ?? 'Notifikasi Baru';
                    $isUnread = is_null($notification->read_at);
                    $iconName = 'info';
                    $iconColor = 'text-ui-info bg-ui-info-soft border-ui-info/10';
                    $priorityLabel = 'Info';
                    $priorityColor = 'bg-ui-info-soft text-ui-info border-ui-info/10';
                    
                    if (str_contains(strtolower($msg), 'revisi') || str_contains(strtolower($msg), 'perlu')) {
                        $iconName = 'alert-triangle';
                        $iconColor = 'text-ui-warning bg-ui-warning-soft border-ui-warning/10';
                        $priorityLabel = 'Penting';
                        $priorityColor = 'bg-ui-warning-soft text-ui-warning border-ui-warning/10';
                    } elseif (str_contains(strtolower($msg), 'ditolak') || str_contains(strtolower($msg), 'batal') || str_contains(strtolower($msg), 'terlambat')) {
                        $iconName = 'x-circle';
                        $iconColor = 'text-ui-danger bg-ui-danger-soft border-ui-danger/10';
                        $priorityLabel = 'Tinggi';
                        $priorityColor = 'bg-ui-danger-soft text-ui-danger border-ui-danger/10';
                    } elseif (str_contains(strtolower($msg), 'disetujui') || str_contains(strtolower($msg), 'selesai')) {
                        $iconName = 'check-circle';
                        $iconColor = 'text-ui-success bg-ui-success-soft border-ui-success/10';
                        $priorityLabel = 'Selesai';
                        $priorityColor = 'bg-ui-success-soft text-ui-success border-ui-success/10';
                    }
                @endphp
                <div class="py-2.5 flex items-start gap-3 relative first:pt-0 last:pb-0 transition-colors hover:bg-ui-primary-soft/10 rounded px-1.5">
                    <div class="w-8 h-8 rounded-ui-md flex items-center justify-center shrink-0 border {{ $iconColor }}">
                        <i data-lucide="{{ $iconName }}" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="inline-flex items-center px-1.5 py-0.2 bg-ui-primary-soft text-ui-text-secondary border border-ui-border rounded text-[9px] font-bold">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                            <span class="inline-flex items-center px-1.5 py-0.2 rounded border text-[9px] font-bold {{ $priorityColor }}">
                                {{ $priorityLabel }}
                            </span>
                        </div>
                        <p class="text-xs text-ui-text-primary leading-normal font-semibold mt-1">
                            {{ $msg }}
                        </p>
                    </div>
                    @if ($isUnread)
                        <span class="w-2 h-2 rounded-full bg-ui-primary shrink-0 mt-1.5 shadow-sm" title="Belum dibaca"></span>
                    @endif
                </div>
            @empty
                <x-ui.empty-state 
                    icon="bell-off" 
                    title="Tidak Ada Notifikasi Baru" 
                    description="Semua pemberitahuan sistem telah dibaca atau belum ada notifikasi baru." 
                >
                    <x-slot name="primaryAction">
                        <x-ui.button variant="outline" size="sm" leadingIcon="refresh-cw" href="{{ route('pegawai.dashboard') }}">
                            Segarkan
                        </x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @endforelse
        </div>
    </x-ui.card>
</div>

@endsection
