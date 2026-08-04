@extends('layouts.master')

@section('title', 'Dashboard Pegawai')

@section('content')

{{-- Welcome Greeting Section --}}
<div class="bg-gradient-to-br from-ui-primary to-ui-primary-hover rounded-ui-xl p-6 text-white shadow-ui-md relative overflow-hidden select-none mb-6">
    <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>
    <div class="absolute -left-10 -bottom-10 w-36 h-36 bg-ui-success-soft/10 rounded-full blur-xl"></div>
    
    <div class="relative min-w-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="min-w-0">
            <x-ui.badge variant="success" styleType="solid" size="sm" class="bg-white/20 border-white/10 text-white">
                Dashboard Pegawai
            </x-ui.badge>
            <h1 class="text-heading-3 font-extrabold tracking-tight mt-3 text-white">
                Selamat Datang Kembali, {{ auth()->user()->name }}!
            </h1>
            <p class="text-xs text-white/80 mt-1 max-w-xl leading-relaxed">
                Unit Kerja: <span class="font-bold text-white">{{ Auth::user()->pegawai?->unitkerja?->nama_unitkerja ?? '-' }}</span> | Jabatan: <span class="font-bold text-white">{{ Auth::user()->pegawai?->jabatan?->nama_jabatan ?? '-' }}</span>
            </p>
        </div>
        
        @if(Auth::user()->pegawai?->isAktif())
            <x-ui.badge variant="success" styleType="solid" size="md" class="shrink-0 bg-ui-success border-white/20 select-none shadow-sm">
                Status: Aktif
            </x-ui.badge>
        @endif
    </div>
</div>

{{-- Statistics cards --}}
<div class="grid gap-6 mb-6 grid-cols-1 md:grid-cols-3 select-none">
    <x-ui.card variant="statistics" title="Jumlah Tugas Saya" :value="$stats['jumlah_tugas']" icon="clipboard-list" trend="Aktif" trendType="neutral" />
    <x-ui.card variant="statistics" title="Tugas Belum Selesai" :value="$stats['tugas_belum_selesai']" icon="clock" trend="Pending" trendType="neutral" />
    <x-ui.card variant="statistics" title="Catatan Kegiatan Bulan Ini" :value="$stats['catatan_bulan_ini']" icon="file-text" trend="Laporan" trendType="neutral" />
</div>

{{-- Progress, Actions, and Deadlines Row --}}
<div class="grid gap-6 lg:grid-cols-3 items-stretch mb-6">
    <!-- Progress circle card -->
    <x-ui.card title="Progres Tugas Bulan Ini" icon="trending-up" subtitle="Rasio penyelesaian tugas aktif bulanan Anda">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 py-2 select-none">
            <div class="relative flex items-center justify-center">
                <svg width="130" height="130" viewBox="0 0 42 42" class="transform -rotate-90">
                    <circle
                        cx="21"
                        cy="21"
                        r="15.9155"
                        fill="transparent"
                        stroke="var(--ui-border)"
                        stroke-width="3" />
                    <circle
                        cx="21"
                        cy="21"
                        r="15.9155"
                        fill="transparent"
                        stroke="var(--ui-success)"
                        stroke-width="3"
                        stroke-dasharray="{{ $progress_data['persentase_selesai'] }} {{ 100 - $progress_data['persentase_selesai'] }}"
                        stroke-linecap="round"
                        class="transition-all duration-500" />
                </svg>
                <div class="absolute flex flex-col items-center justify-center">
                    <span class="text-2xl font-extrabold text-ui-text-primary tracking-tight leading-none">
                        {{ $progress_data['persentase_selesai'] }}%
                    </span>
                    <span class="text-[9px] text-ui-text-secondary font-bold uppercase tracking-wider mt-1 leading-none">
                        SELESAI
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-2 text-xs">
                    <span class="w-3 h-3 rounded-full bg-ui-success shrink-0"></span>
                    <span class="text-ui-text-secondary font-medium">Selesai: <strong class="text-ui-text-primary">{{ $progress_data['jumlah_selesai'] }}</strong></span>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="w-3 h-3 rounded-full bg-ui-warning shrink-0"></span>
                    <span class="text-ui-text-secondary font-medium">Pending: <strong class="text-ui-text-primary">{{ $progress_data['jumlah_pending'] }}</strong></span>
                </div>
                <p class="text-[10px] text-ui-muted mt-2 max-w-[120px] leading-relaxed">
                    Selesaikan semua penugasan sebelum tenggat berakhir.
                </p>
            </div>
        </div>
    </x-ui.card>
    
    <!-- Quick Actions card -->
    <x-ui.card title="Aksi Cepat" icon="zap" subtitle="Pintasan cepat untuk memperbarui pekerjaan harian Anda">
        <div class="flex flex-col gap-3 py-1">
            <x-ui.button 
                variant="primary" 
                size="md" 
                leadingIcon="list-todo" 
                href="{{ route('pegawai.tugas.index') }}"
                fullWidth
            >
                Perbarui Status Tugas
            </x-ui.button>
            
            <x-ui.button 
                variant="outline" 
                size="md" 
                leadingIcon="plus" 
                href="{{ route('pegawai.catatan_kegiatan.index') }}"
                fullWidth
            >
                Kelola Laporan Kegiatan
            </x-ui.button>
        </div>
        
        <p class="text-[10.5px] text-ui-text-secondary leading-relaxed mt-4 bg-ui-primary-soft/50 border border-ui-border rounded-ui-md p-2.5">
            <strong>Catatan:</strong> Pengajuan catatan kegiatan baru akan langsung dikirimkan ke KPH / Admin untuk proses verifikasi data.
        </p>
    </x-ui.card>

    <!-- Upcoming deadlines card -->
    <x-ui.card title="Tenggat Waktu Terdekat" icon="clock" subtitle="Daftar tugas aktif Anda dengan tenggat waktu paling dekat">
        @php
            $upcomingDeadlines = $tugas_aktif->filter(fn($item) => $item->tugas && $item->tugas->deadline)
                ->sortBy(fn($item) => $item->tugas->deadline)
                ->take(3);
        @endphp
        
        <div class="space-y-3.5 py-1">
            @forelse($upcomingDeadlines as $item)
                <div class="flex items-start justify-between gap-3 border-b border-ui-border/50 pb-2.5 last:border-0 last:pb-0">
                    <div class="min-w-0">
                        <span class="text-xs font-bold text-ui-text-primary block truncate">
                            {{ $item->tugas->judul ?? '-' }}
                        </span>
                        <span class="text-[10.5px] text-ui-text-secondary mt-0.5 inline-block">
                            Status: <strong class="text-ui-primary">{{ ucfirst($item->status) }}</strong>
                        </span>
                    </div>
                    
                    @php
                        $deadlineDate = \Carbon\Carbon::parse($item->tugas->deadline);
                        $daysLeft = now()->startOfDay()->diffInDays($deadlineDate, false);
                        $badgeVariant = $daysLeft <= 2 ? 'danger' : ($daysLeft <= 5 ? 'warning' : 'success');
                        $badgeLabel = $daysLeft < 0 ? 'Terlewat' : ($daysLeft == 0 ? 'Hari ini' : ($daysLeft == 1 ? 'Besok' : $daysLeft . ' hari lagi'));
                    @endphp
                    
                    <div class="text-right shrink-0 select-none">
                        <x-ui.badge :variant="$badgeVariant" styleType="soft" size="sm">
                            {{ $badgeLabel }}
                        </x-ui.badge>
                        <span class="text-[9px] text-ui-muted block mt-1 font-bold">
                            {{ $deadlineDate->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-ui-muted text-xs">
                    Tidak ada tenggat waktu penugasan terdekat.
                </div>
            @endforelse
        </div>
    </x-ui.card>
</div>

{{-- Tasks table list --}}
<div class="mb-6">
    <x-ui.card title="Daftar Tugas Aktif Saya" icon="clipboard-list" subtitle="Daftar seluruh penugasan kerja yang didelegasikan ke Anda saat ini">
        <div class="overflow-x-auto custom-scrollbar">
            <x-ui.table :headers="['No', 'Judul Tugas', 'Deskripsi Pekerjaan', 'Deadline', 'Status Tugas', 'Aksi']" :empty="$tugas_aktif->isEmpty()">
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
                        <td class="px-6 py-4 text-ui-text-secondary font-bold">
                            {{ $penugasan->tugas->deadline ? \Carbon\Carbon::parse($penugasan->tugas->deadline)->format('d/m/Y') : 'N/A' }}
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
<div class="grid gap-6 lg:grid-cols-2">
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
                    
                    $timelineItems[] = [
                        'icon' => $statusIcon,
                        'timestamp' => $item->tanggal_kegiatan ? $item->tanggal_kegiatan->format('d-m-Y') : ($item->created_at ? $item->created_at->format('d-m-Y H:i') : '-'),
                        'description' => "Menyerahkan laporan tugas: <strong>{$tugasJudul}</strong>",
                        'details' => $item->judul . " (Verifikasi: " . $statusLabel . ")",
                    ];
                }
            @endphp
            
            <x-ui.activity-timeline :items="$timelineItems" />
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
                    
                    if (str_contains(strtolower($msg), 'revisi') || str_contains(strtolower($msg), 'perlu')) {
                        $iconName = 'alert-triangle';
                        $iconColor = 'text-ui-warning bg-ui-warning-soft border-ui-warning/10';
                    } elseif (str_contains(strtolower($msg), 'ditolak') || str_contains(strtolower($msg), 'batal')) {
                        $iconName = 'x-circle';
                        $iconColor = 'text-ui-danger bg-ui-danger-soft border-ui-danger/10';
                    } elseif (str_contains(strtolower($msg), 'disetujui') || str_contains(strtolower($msg), 'selesai')) {
                        $iconName = 'check-circle';
                        $iconColor = 'text-ui-success bg-ui-success-soft border-ui-success/10';
                    }
                @endphp
                <div class="py-3 flex items-start gap-3 relative first:pt-0 last:pb-0">
                    <div class="w-8 h-8 rounded-ui-md flex items-center justify-center shrink-0 border {{ $iconColor }}">
                        <i data-lucide="{{ $iconName }}" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-ui-text-primary leading-normal font-semibold">
                            {{ $msg }}
                        </p>
                        <span class="text-[10px] text-ui-muted mt-1 inline-block">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                    @if ($isUnread)
                        <span class="w-2 h-2 rounded-full bg-ui-primary shrink-0 mt-1.5 shadow-sm"></span>
                    @endif
                </div>
            @empty
                <div class="text-center py-10 text-ui-muted text-xs">
                    <div class="w-10 h-10 rounded-full bg-ui-primary-soft flex items-center justify-center mx-auto mb-2 text-ui-text-secondary">
                        <i data-lucide="bell-off" class="w-5 h-5 text-ui-muted"></i>
                    </div>
                    <span>Tidak ada notifikasi sistem untuk saat ini.</span>
                </div>
            @endforelse
        </div>
    </x-ui.card>
</div>

@endsection
