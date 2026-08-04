@extends('layouts.master')

@section('title', 'Catatan Kegiatan Pegawai')

@section('content')

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif
@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

<x-ui.page-header title="Catatan Kegiatan Pegawai" subtitle="Review dan verifikasi laporan catatan kegiatan harian pegawai.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
</x-ui.page-header>

<x-ui.card>
    <x-ui.table :headers="['No', 'Tanggal', 'Pegawai', 'Tugas', 'Status', 'Aksi']" :empty="$catatan->isEmpty()">
        <x-slot name="filter">
            <form method="GET" class="flex items-center gap-2">
                <x-ui.input 
                    type="select"
                    name="status"
                    class="min-w-[180px]"
                >
                    <option value="">Semua Status</option>
                    <option value="menunggu_verifikasi" @selected(request('status') === 'menunggu_verifikasi')>Menunggu Verifikasi</option>
                    <option value="disetujui" @selected(request('status') === 'disetujui')>Disetujui</option>
                    <option value="revisi" @selected(request('status') === 'revisi')>Revisi</option>
                    <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
                </x-ui.input>
                <x-ui.button type="submit" variant="secondary" size="md" leadingIcon="filter">
                    Filter
                </x-ui.button>
            </form>
        </x-slot>

        @foreach ($catatan as $i => $item)
            <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                <td class="px-4 py-3 text-xs text-ui-text-secondary">{{ $i + 1 }}</td>
                <td class="px-4 py-3 text-xs text-ui-text-secondary">
                    {{ optional($item->tanggal_kegiatan)->format('d-m-Y') ?? '-' }}
                </td>
                <td class="px-4 py-3 text-xs">
                    <div class="font-semibold text-ui-text-primary">{{ $item->pegawai->user->name ?? '-' }}</div>
                </td>
                <td class="px-4 py-3 text-xs text-ui-text-secondary truncate max-w-[200px]">
                    {{ $item->penugasan->tugas->judul ?? '-' }}
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
                <td class="px-4 py-3 text-xs text-right">
                    <x-ui.button variant="outline" size="xs" :href="route('admin.catatan_kegiatan.show', $item->id)">
                        Detail
                    </x-ui.button>
                </td>
            </tr>
        @endforeach
    </x-ui.table>
</x-ui.card>

@endsection
