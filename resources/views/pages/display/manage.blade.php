@extends('layouts.master')

@section('title', $pageTitle)

@section('content')

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif
@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

<x-ui.page-header :title="$pageTitle" subtitle="Kelola pengaturan durasi display monitor, reset tampilan, dan monitoring log scan QR pegawai.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
</x-ui.page-header>

<div class="space-y-6">
    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <x-ui.card variant="statistics" title="Status Aktivitas" icon="activity">
            <x-slot name="trend">
                @if($summary['last_scan_at'])
                    Aktif
                @else
                    Idle
                @endif
            </x-slot>
            @if($summary['last_scan_at'])
                <span class="text-sm font-semibold text-ui-success mt-2 block">
                    Terakhir: {{ $summary['last_scan_at']->diffForHumans() }}
                </span>
            @else
                <span class="text-xs text-ui-text-secondary mt-2 block">
                    Belum ada aktivitas scan hari ini
                </span>
            @endif
        </x-ui.card>

        <x-ui.card variant="statistics" title="Status Scanner USB" icon="monitor">
            <span class="text-xs sm:text-sm font-semibold text-ui-text-primary mt-2 block">
                Siap jika halaman TV terbuka
            </span>
            <span class="text-[10px] sm:text-xs text-ui-text-secondary mt-0.5 block">
                Alat scanner bertindak sebagai input keyboard.
            </span>
        </x-ui.card>

        <x-ui.card variant="statistics" title="Total Scan Hari Ini" icon="qr-code">
            <div class="text-2xl sm:text-3xl font-extrabold text-ui-primary mt-1">
                {{ $summary['total'] }}
            </div>
            <div class="text-[10px] text-ui-text-secondary mt-0.5">
                Jumlah sukses & gagal terakumulasi
            </div>
        </x-ui.card>
    </div>

    <!-- ACTION BUTTONS -->
    <x-ui.card>
        <div class="flex flex-wrap gap-3">
            <x-ui.button variant="primary" size="sm" leadingIcon="tv" :href="$tvRoute">
                Buka Mode TV
            </x-ui.button>
            <x-ui.button variant="outline" size="sm" leadingIcon="external-link" :href="$tvRoute" target="_blank" rel="noopener">
                Buka di Tab Baru
            </x-ui.button>
            <form action="{{ $resetRoute }}" method="POST" class="inline">
                @csrf
                <x-ui.button type="submit" variant="secondary" size="sm" leadingIcon="refresh-cw">
                    Reset Tampilan TV
                </x-ui.button>
            </form>
        </div>
    </x-ui.card>

    <!-- SETTINGS -->
    <x-ui.card title="Pengaturan Display Monitor" icon="settings">
        <form method="POST" action="{{ $settingUpdateRoute }}" class="space-y-5">
            @csrf
            
            <div class="max-w-md">
                <x-ui.input 
                    type="number"
                    name="display_duration_seconds"
                    label="Durasi Tampil Hasil Scan (Detik)"
                    min="5"
                    max="60"
                    value="{{ old('display_duration_seconds', $setting->display_duration_seconds) }}"
                    required
                    :error="$errors->first('display_duration_seconds')"
                />
            </div>

            <div>
                <x-ui.input 
                    type="checkbox"
                    name="show_employee_photo"
                    label="Tampilkan foto pegawai di layar TV"
                    value="1"
                    :checked="old('show_employee_photo', $setting->show_employee_photo)"
                />
            </div>

            <x-ui.button type="submit" variant="success" size="sm" leadingIcon="save">
                Simpan Pengaturan
            </x-ui.button>
        </form>
    </x-ui.card>

    <!-- SCAN HISTORY -->
    <x-ui.card title="Riwayat Scan Hari Ini" subtitle="Monitor aktivitas scan QR harian secara real-time.">
        <x-ui.table :headers="['Waktu', 'Pegawai', 'Status', 'Jumlah Tugas', 'Keterangan']" :empty="$logs->isEmpty()">
            @foreach ($logs as $log)
                <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                    <td class="px-4 py-3 text-xs text-ui-text-secondary">
                        {{ optional($log->scanned_at)->format('H:i:s') }}
                    </td>
                    <td class="px-4 py-3 text-xs font-semibold text-ui-text-primary">
                        {{ $log->pegawai->user->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                        @if ($log->status === 'success')
                            <x-ui.badge variant="success" size="sm">Success</x-ui.badge>
                        @elseif ($log->status === 'empty_task')
                            <x-ui.badge variant="warning" size="sm">Empty Task</x-ui.badge>
                        @else
                            <x-ui.badge variant="danger" size="sm">{{ str_replace('_', ' ', $log->status) }}</x-ui.badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs font-semibold text-ui-text-primary">
                        {{ $log->task_count }}
                    </td>
                    <td class="px-4 py-3 text-xs text-ui-text-secondary">
                        {{ $log->message }}
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

    <!-- GUIDE -->
    <x-ui.card variant="flat" title="Panduan Pengoperasian Display TV" icon="info">
        <div class="text-xs sm:text-sm text-ui-text-secondary leading-relaxed">
            <ol class="list-decimal list-inside space-y-1.5 font-medium">
                <li>Sambungkan alat barcode/QR scanner USB ke PC/Laptop Display.</li>
                <li>Klik tombol <span class="font-bold text-ui-primary">Buka Mode TV</span> di atas.</li>
                <li>Buat tampilan browser menjadi layar penuh (fullscreen/F11).</li>
                <li>Pastikan kursor fokus berada pada area input scan otomatis di layar TV.</li>
                <li>Pegawai men-scan kartu QR mereka. Layar akan otomatis memperbarui detail tugas pegawai.</li>
            </ol>
        </div>
    </x-ui.card>
</div>

@endsection
