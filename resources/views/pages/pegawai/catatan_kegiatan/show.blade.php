@extends('layouts.master')

@section('title', 'Detail Catatan Kegiatan')

@section('content')

<x-ui.page-header title="Detail Catatan Kegiatan" subtitle="Informasi detail mengenai laporan catatan kegiatan harian Anda.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="ghost" size="sm" leadingIcon="arrow-left" :href="route('pegawai.catatan_kegiatan.index')">
            Kembali
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<div class="space-y-6">
    <x-ui.card title="Rincian Kegiatan Saya" icon="file-text">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 text-xs sm:text-sm">
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Tugas Utama</span>
                <span class="font-bold text-ui-text-primary">{{ $catatan->penugasan->tugas->judul ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Tanggal Kegiatan</span>
                <span class="font-medium text-ui-text-primary">{{ optional($catatan->tanggal_kegiatan)->format('d-m-Y') ?? '-' }}</span>
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
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Waktu Diverifikasi</span>
                <span class="font-medium text-ui-text-primary">
                    {{ optional($catatan->diverifikasi_at)->format('d-m-Y H:i') ?? '-' }} 
                    @if($catatan->verifier)
                        oleh {{ $catatan->verifier->name }}
                    @endif
                </span>
            </div>

            <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Deskripsi Rincian Kegiatan</span>
                <div class="bg-slate-50 border rounded-lg p-3 text-ui-text-primary leading-normal whitespace-pre-line">{{ $catatan->deskripsi }}</div>
            </div>

            <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Hasil Kegiatan (Output)</span>
                <div class="bg-slate-50 border rounded-lg p-3 text-ui-text-primary leading-normal whitespace-pre-line">{{ $catatan->hasil_kegiatan ?? '-' }}</div>
            </div>

            <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Kendala / Hambatan</span>
                <div class="bg-slate-50 border rounded-lg p-3 text-ui-text-primary leading-normal whitespace-pre-line">{{ $catatan->kendala ?? '-' }}</div>
            </div>

            @if($catatan->catatan_verifikasi)
                <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                    <span class="text-[10px] sm:text-xs text-ui-text-secondary">Catatan Verifikator (Admin/KPH)</span>
                    <div class="bg-ui-warning-soft/20 border border-ui-warning/10 rounded-lg p-3 text-ui-warning font-semibold leading-normal">{{ $catatan->catatan_verifikasi }}</div>
                </div>
            @endif

            @if(is_array($catatan->foto_kegiatan) && count($catatan->foto_kegiatan))
                <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                    <span class="text-[10px] sm:text-xs text-ui-text-secondary mb-2">Lampiran Bukti Pendukung</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($catatan->foto_kegiatan as $file)
                            <div class="border border-ui-border rounded-xl p-3 bg-slate-50 flex items-center justify-between gap-3 text-xs">
                                <span class="truncate font-medium text-ui-text-primary">{{ basename($file) }}</span>
                                <x-ui.button variant="outline" size="xs" leadingIcon="external-link" :href="asset('storage/' . $file)" target="_blank">
                                    Buka
                                </x-ui.button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-ui.card>
</div>

@endsection
