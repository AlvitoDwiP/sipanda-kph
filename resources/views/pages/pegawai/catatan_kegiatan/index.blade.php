@extends('layouts.master')

@section('title', 'Catatan Kegiatan Saya')

@section('content')

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif
@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

<x-ui.page-header title="Catatan Kegiatan" subtitle="Daftar laporan harian dan riwayat verifikasi catatan kegiatan Anda.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
</x-ui.page-header>

<x-ui.card>
    <x-ui.table :headers="['No', 'Tanggal', 'Tugas', 'Ringkasan', 'Status', 'Aksi']" :empty="$catatan->isEmpty()">
        @foreach ($catatan as $i => $item)
            <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                <td class="px-4 py-3 text-xs text-ui-text-secondary">{{ $i + 1 }}</td>
                <td class="px-4 py-3 text-xs text-ui-text-secondary">
                    {{ optional($item->tanggal_kegiatan)->format('d-m-Y') ?? '-' }}
                </td>
                <td class="px-4 py-3 text-xs font-semibold text-ui-text-primary min-w-[150px]">
                    {{ $item->penugasan->tugas->judul ?? '-' }}
                </td>
                <td class="px-4 py-3 text-xs text-ui-text-secondary truncate max-w-[250px]">
                    {{ \Illuminate\Support\Str::limit($item->hasil_kegiatan ?? $item->deskripsi, 100) }}
                </td>
                <td class="px-4 py-3 text-xs">
                    @if($item->status_verifikasi === 'disetujui')
                        <x-ui.badge variant="success" size="sm">Disetujui</x-ui.badge>
                    @elseif(in_array($item->status_verifikasi, ['revisi', 'menunggu_verifikasi']))
                        <x-ui.badge variant="warning" size="sm">{{ $item->status_verifikasi_label }}</x-ui.badge>
                    @elseif($item->status_verifikasi === 'ditolak')
                        <x-ui.badge variant="danger" size="sm">Ditolak</x-ui.badge>
                    @else
                        <x-ui.badge variant="neutral" size="sm">{{ $item->status_verifikasi_label }}</x-ui.badge>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-right whitespace-nowrap">
                    <div class="flex items-center justify-end gap-1.5">
                        <x-ui.button variant="outline" size="xs" :href="route('pegawai.catatan_kegiatan.show', $item->id)">
                            Detail
                        </x-ui.button>
                        @if($item->status_verifikasi === 'revisi')
                            <x-ui.button variant="secondary" size="xs" leadingIcon="edit" class="text-ui-warning" :href="route('pegawai.catatan_kegiatan.edit', $item->id)">
                                Edit
                            </x-ui.button>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </x-ui.table>
</x-ui.card>

@endsection
