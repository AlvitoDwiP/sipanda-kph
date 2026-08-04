@extends('layouts.master')
@php($routePrefix = $routePrefix ?? 'admin')

@section('title', 'Detail Penugasan')

@section('content')

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif
@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

<x-ui.page-header title="Detail Penugasan Harian" subtitle="Informasi penugasan beserta status pengerjaan oleh masing-masing penerima.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="ghost" size="sm" leadingIcon="arrow-left" :href="route($routePrefix . '.penugasan.index')">
            Kembali
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<div class="space-y-6">
    <!-- METADATA TUGAS -->
    <x-ui.card title="Detail Tugas" icon="file-text">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 text-xs sm:text-sm">
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Judul Tugas</span>
                <span class="font-bold text-ui-text-primary">{{ $penugasan->judul }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Pemberi Tugas</span>
                <span class="font-medium text-ui-text-primary">{{ $penugasan->user->name ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Prioritas</span>
                <span>
                    @if ($penugasan->prioritas === 'rendah')
                        <x-ui.badge variant="success" size="sm">Rendah</x-ui.badge>
                    @elseif ($penugasan->prioritas === 'sedang')
                        <x-ui.badge variant="warning" size="sm">Sedang</x-ui.badge>
                    @elseif ($penugasan->prioritas === 'tinggi')
                        <x-ui.badge variant="danger" size="sm">Tinggi</x-ui.badge>
                    @else
                        <x-ui.badge variant="neutral" size="sm">-</x-ui.badge>
                    @endif
                </span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Tanggal Mulai</span>
                <span class="font-medium text-ui-text-primary">{{ optional($penugasan->tanggal_tugas)->format('d-m-Y') ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Batas Akhir (Deadline)</span>
                <span class="font-medium text-ui-text-primary">{{ optional($penugasan->deadline)->format('d-m-Y') ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Dokumen Acuan</span>
                <div>
                    @if ($penugasan->template)
                        <x-ui.button variant="secondary" size="xs" leadingIcon="file-text" :href="asset('storage/' . $penugasan->template)" target="_blank">
                            Lihat Template
                        </x-ui.button>
                    @else
                        <span class="text-ui-muted font-medium">-</span>
                    @endif
                </div>
            </div>
            <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Deskripsi & Instruksi</span>
                <span class="font-medium text-ui-text-primary leading-relaxed bg-ui-primary-soft/30 p-3 rounded-lg border border-ui-border">{{ $penugasan->deskripsi }}</span>
            </div>
        </div>
    </x-ui.card>

    <!-- PENERIMA TUGAS -->
    <x-ui.card title="Pegawai Penerima Tugas" icon="users">
        <x-ui.table :headers="['Nama & NIP', 'Status', 'Progres', 'Keterlambatan', 'Informasi Progres / Revisi', 'Tindakan Verifikasi']" :empty="$penugasan->penugasan->isEmpty()">
            @foreach ($penugasan->penugasan as $item)
                <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                    <td class="px-4 py-3 text-xs align-top">
                        <div class="font-semibold text-ui-text-primary">{{ $item->pegawai->user->name ?? '-' }}</div>
                        <div class="text-[10px] text-ui-text-secondary mt-0.5">NIP: {{ $item->pegawai->user->nip ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-3 text-xs align-top">
                        <span class="capitalize font-semibold">
                            @if($item->status === 'selesai')
                                <x-ui.badge variant="success" size="sm">{{ $item->status }}</x-ui.badge>
                            @elseif(in_array($item->status, ['proses', 'menunggu_verifikasi']))
                                <x-ui.badge variant="warning" size="sm">{{ str_replace('_', ' ', $item->status) }}</x-ui.badge>
                            @elseif($item->status === 'dibatalkan')
                                <x-ui.badge variant="neutral" size="sm">{{ $item->status }}</x-ui.badge>
                            @else
                                <x-ui.badge variant="primary" size="sm">{{ $item->status }}</x-ui.badge>
                            @endif
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs align-top font-semibold text-ui-text-primary">
                        {{ $item->progres_persen ?? 0 }}%
                    </td>
                    <td class="px-4 py-3 text-xs align-top">
                        @if($item->is_terlambat)
                            <x-ui.badge variant="danger" size="sm">Terlambat</x-ui.badge>
                        @else
                            <span class="text-ui-muted">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs align-top space-y-1 max-w-[280px]">
                        @if($item->catatan_progres)
                            <div><strong class="text-ui-text-secondary">Update:</strong> <span class="text-ui-text-primary">{{ $item->catatan_progres }}</span></div>
                        @endif
                        @if($item->catatan_revisi)
                            <div><strong class="text-ui-text-secondary">Revisi:</strong> <span class="text-ui-danger">{{ $item->catatan_revisi }}</span></div>
                        @endif
                        <div class="text-[10px] text-ui-text-secondary">
                            Mulai/Update: {{ $item->progres_updated_at?->format('d-m-Y H:i') ?? '-' }}<br>
                            Selesai: {{ $item->selesai_at?->format('d-m-Y H:i') ?? '-' }}
                        </div>
                    </td>
                    <td class="px-4 py-3 text-xs align-top">
                        @if($item->status === 'menunggu_verifikasi')
                            <div class="space-y-3 p-2 bg-slate-50 border rounded-lg max-w-[200px]">
                                <form method="POST" action="{{ route($routePrefix . '.penugasan.setujui', $item->id) }}">
                                    @csrf
                                    <x-ui.button type="submit" variant="success" size="xs" fullWidth leadingIcon="check">
                                        Setujui
                                    </x-ui.button>
                                </form>
                                <form method="POST" action="{{ route($routePrefix . '.penugasan.revisi', $item->id) }}" class="space-y-1.5">
                                    @csrf
                                    <x-ui.input 
                                        type="textarea"
                                        name="catatan_revisi"
                                        placeholder="Catatan revisi..."
                                        required
                                        rows="2"
                                    />
                                    <x-ui.button type="submit" variant="warning" size="xs" fullWidth leadingIcon="refresh-cw">
                                        Minta Revisi
                                    </x-ui.button>
                                </form>
                            </div>
                        @elseif(!in_array($item->status, ['selesai', 'dibatalkan']))
                            <form method="POST" action="{{ route($routePrefix . '.penugasan.batalkan', $item->id) }}" class="space-y-1.5 max-w-[200px]">
                                @csrf
                                <x-ui.input 
                                    type="text"
                                    name="alasan_pembatalan"
                                    placeholder="Alasan (opsional)"
                                />
                                <x-ui.button type="submit" variant="danger" size="xs" fullWidth leadingIcon="x-circle">
                                    Batalkan
                                </x-ui.button>
                            </form>
                        @else
                            <span class="text-ui-muted">Tidak ada tindakan</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

    <!-- RIWAYAT PENERIMAAN -->
    <x-ui.card title="Riwayat Aktivitas & Perubahan Status" icon="clock">
        @php
            $histories = $penugasan->penugasan->flatMap->statusHistories->sortByDesc('created_at');
        @endphp
        
        <div class="relative pl-6 border-l border-ui-border space-y-5 text-xs sm:text-sm text-left">
            @forelse($histories as $history)
                <div class="relative">
                    <!-- Dot timeline icon replacement -->
                    <span class="absolute -left-[30px] top-1 bg-ui-primary-soft text-ui-primary rounded-full p-1 border border-ui-border">
                        <i data-lucide="check-circle" class="w-3 h-3"></i>
                    </span>
                    <div class="font-bold text-ui-text-primary">
                        {{ $history->status_sebelum ?? '-' }} &rarr; <span class="text-ui-primary">{{ $history->status_sesudah }}</span>
                    </div>
                    <div class="text-[10px] text-ui-text-secondary mt-0.5">
                        {{ $history->created_at?->format('d-m-Y H:i') }} &bull; Oleh {{ $history->user->name ?? '-' }}
                    </div>
                    @if($history->catatan)
                        <div class="text-ui-text-secondary bg-ui-primary-soft/20 px-3 py-1.5 rounded-lg border border-ui-border/50 mt-1 max-w-xl leading-normal">
                            {{ $history->catatan }}
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-ui-muted italic">Belum ada riwayat aktivitas tugas ini.</p>
            @endforelse
        </div>
    </x-ui.card>
</div>

@endsection
