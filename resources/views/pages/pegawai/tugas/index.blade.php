@extends('layouts.master')

@section('title', 'Tugas Saya')

@section('content')

@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

@if (!empty($pegawaiTidakTerhubung) && $pegawaiTidakTerhubung === true)
    <x-ui.alert variant="warning" class="mb-5" title="Akun Belum Terhubung" description="Akun Anda belum terhubung dengan data pegawai. Silakan hubungi administrator." />
@endif

<x-ui.page-header title="Tugas Saya" subtitle="Daftar tugas harian yang didelegasikan kepada Anda.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
</x-ui.page-header>

<x-ui.card>
    <x-ui.table :headers="['No', 'Judul Tugas', 'Deskripsi', 'Tanggal Mulai', 'Deadline', 'Status', 'Progres', 'Kondisi', 'Aksi']" :empty="$tugas->isEmpty()">
        @foreach ($tugas as $i => $item)
            @php
                $penugasanSaya = $item->penugasan->firstWhere('pegawai.user_id', auth()->id());
            @endphp
            <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                <td class="px-4 py-3 text-xs text-ui-text-secondary">{{ $i + 1 }}</td>
                <td class="px-4 py-3 text-xs font-semibold text-ui-text-primary min-w-[150px]">{{ $item['judul'] }}</td>
                <td class="px-4 py-3 text-xs text-ui-text-secondary truncate max-w-[200px]">{{ $item['deskripsi'] }}</td>
                <td class="px-4 py-3 text-xs text-ui-text-secondary">{{ optional($item->tanggal_tugas)->format('d M Y') ?? '-' }}</td>
                <td class="px-4 py-3 text-xs text-ui-text-secondary">{{ \Carbon\Carbon::parse($item['deadline'])->format('d M Y') }}</td>
                <td class="px-4 py-3 text-xs">
                    @if ($penugasanSaya)
                        <span class="capitalize font-semibold">
                            @if($penugasanSaya->status === 'selesai')
                                <x-ui.badge variant="success" size="sm">{{ $penugasanSaya->status }}</x-ui.badge>
                            @elseif(in_array($penugasanSaya->status, ['proses', 'menunggu_verifikasi']))
                                <x-ui.badge variant="warning" size="sm">{{ str_replace('_', ' ', $penugasanSaya->status) }}</x-ui.badge>
                            @elseif($penugasanSaya->status === 'dibatalkan')
                                <x-ui.badge variant="neutral" size="sm">{{ $penugasanSaya->status }}</x-ui.badge>
                            @else
                                <x-ui.badge variant="primary" size="sm">{{ $penugasanSaya->status }}</x-ui.badge>
                            @endif
                        </span>
                    @else
                        <span class="text-ui-muted text-xs">-</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-ui-text-primary font-semibold">
                    {{ $penugasanSaya->progres_persen ?? 0 }}%
                </td>
                <td class="px-4 py-3 text-xs">
                    @if ($penugasanSaya?->is_terlambat)
                        <x-ui.badge variant="danger" size="sm">Terlambat</x-ui.badge>
                    @else
                        <span class="text-ui-muted">-</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-right">
                    <x-ui.button variant="outline" size="xs" :href="route('pegawai.tugas.show', $item['id'])">
                        Buka Tugas
                    </x-ui.button>
                </td>
            </tr>
        @endforeach
    </x-ui.table>
</x-ui.card>

@endsection
