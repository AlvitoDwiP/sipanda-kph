@extends('layouts.master')

@section('title', 'Detail Catatan Kegiatan')

@section('content')

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif
@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

<x-ui.page-header title="Detail Catatan Kegiatan" subtitle="Detail laporan aktivitas kerja harian yang diunggah oleh pegawai.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="ghost" size="sm" leadingIcon="arrow-left" :href="route($routePrefix . '.catatan_kegiatan.index')">
            Kembali
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<div class="space-y-6">
    <x-ui.card title="Rincian Kegiatan" icon="file-text">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 text-xs sm:text-sm">
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Nama Pegawai</span>
                <span class="font-bold text-ui-text-primary">{{ $catatan->pegawai->user->name ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Tugas Utama</span>
                <span class="font-medium text-ui-text-primary">{{ $catatan->penugasan->tugas->judul ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Batas Akhir Tugas</span>
                <span class="font-medium text-ui-text-secondary">{{ optional($catatan->penugasan->tugas->deadline)->format('d-m-Y') ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Status Verifikasi</span>
                <span>
                    @if($catatan->status_verifikasi === 'disetujui')
                        <x-ui.badge variant="success" size="sm">Disetujui</x-ui.badge>
                    @elseif(in_array($catatan->status_verifikasi, ['revisi', 'menunggu_verifikasi']))
                        <x-ui.badge variant="warning" size="sm">{{ $catatan->status_verifikasi_label }}</x-ui.badge>
                    @elseif($catatan->status_verifikasi === 'ditolak')
                        <x-ui.badge variant="danger" size="sm">Ditolak</x-ui.badge>
                    @else
                        <x-ui.badge variant="neutral" size="sm">{{ $catatan->status_verifikasi_label }}</x-ui.badge>
                    @endif
                </span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Waktu Kegiatan</span>
                <span class="font-medium text-ui-text-primary">{{ optional($catatan->tanggal_kegiatan)->format('d-m-Y') ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Waktu Diverifikasi</span>
                <span class="font-medium text-ui-text-primary">
                    {{ optional($catatan->diverifikasi_at)->format('d-m-Y H:i') ?? '-' }} 
                    @if($catatan->verifier)
                        oleh {{ $catatan->verifier->name }}
                    @endif
                </span>
            </div>

            <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Deskripsi Kegiatan</span>
                <div class="bg-slate-50 border rounded-lg p-3 text-ui-text-primary leading-normal whitespace-pre-line">{{ $catatan->deskripsi }}</div>
            </div>

            <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Hasil Kegiatan</span>
                <div class="bg-slate-50 border rounded-lg p-3 text-ui-text-primary leading-normal whitespace-pre-line">{{ $catatan->hasil_kegiatan ?? '-' }}</div>
            </div>

            <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Kendala / Hambatan</span>
                <div class="bg-slate-50 border rounded-lg p-3 text-ui-text-primary leading-normal whitespace-pre-line">{{ $catatan->kendala ?? '-' }}</div>
            </div>

            @if($catatan->catatan_verifikasi)
                <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                    <span class="text-[10px] sm:text-xs text-ui-text-secondary">Catatan Verifikator</span>
                    <div class="bg-ui-warning-soft/20 border border-ui-warning/10 rounded-lg p-3 text-ui-warning font-semibold leading-normal">{{ $catatan->catatan_verifikasi }}</div>
                </div>
            @endif
        </div>
    </x-ui.card>

    <!-- FORM VERIFIKASI -->
    @if($catatan->status_verifikasi === 'menunggu_verifikasi')
        <x-ui.card title="Tindakan Verifikasi Laporan" icon="check-square">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- FORM SETUJUI -->
                <div class="bg-ui-success-soft/20 border border-ui-success/10 p-4 rounded-xl flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-xs uppercase tracking-wider text-ui-success mb-2">Setujui Laporan</h4>
                        <p class="text-xs text-ui-text-secondary leading-relaxed mb-4">
                            Konfirmasi bahwa catatan kegiatan ini sudah sesuai dengan instruksi tugas dan layak disetujui.
                        </p>
                    </div>
                    <form method="POST" action="{{ route($routePrefix . '.catatan_kegiatan.setujui', $catatan->id) }}">
                        @csrf
                        <x-ui.button type="submit" variant="success" size="sm" leadingIcon="check" fullWidth>
                            Setujui Laporan
                        </x-ui.button>
                    </form>
                </div>

                <!-- FORM REVISI -->
                <div class="bg-ui-warning-soft/20 border border-ui-warning/10 p-4 rounded-xl">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-ui-warning mb-2">Minta Revisi</h4>
                    <form method="POST" action="{{ route($routePrefix . '.catatan_kegiatan.revisi', $catatan->id) }}" class="space-y-3">
                        @csrf
                        <x-ui.input 
                            type="textarea"
                            name="catatan_verifikasi"
                            placeholder="Tulis instruksi revisi..."
                            required
                            rows="2"
                        />
                        <x-ui.button type="submit" variant="warning" size="sm" leadingIcon="refresh-cw" fullWidth>
                            Minta Revisi
                        </x-ui.button>
                    </form>
                </div>

                <!-- FORM TOLAK -->
                <div class="bg-ui-danger-soft/20 border border-ui-danger/10 p-4 rounded-xl">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-ui-danger mb-2">Tolak Laporan</h4>
                    <form method="POST" action="{{ route($routePrefix . '.catatan_kegiatan.tolak', $catatan->id) }}" class="space-y-3">
                        @csrf
                        <x-ui.input 
                            type="textarea"
                            name="catatan_verifikasi"
                            placeholder="Alasan penolakan..."
                            required
                            rows="2"
                        />
                        <x-ui.button type="submit" variant="danger" size="sm" leadingIcon="x-circle" fullWidth>
                            Tolak Laporan
                        </x-ui.button>
                    </form>
                </div>
            </div>
        </x-ui.card>
    @endif
</div>

@endsection
