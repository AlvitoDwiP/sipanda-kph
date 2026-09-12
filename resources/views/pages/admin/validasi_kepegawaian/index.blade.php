@extends('layouts.master')

@section('title', 'Validasi Perubahan Data Kepegawaian')

@section('content')
<x-ui.page-header title="Validasi Kepegawaian" subtitle="Daftar pengajuan perubahan data kepegawaian oleh pegawai.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
</x-ui.page-header>

@if(session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif

<x-ui.card>
    @if($pengajuan->isEmpty())
        <x-ui.empty-state 
            icon="check-circle" 
            title="Tidak Ada Pengajuan" 
            description="Saat ini tidak ada pengajuan perubahan data kepegawaian yang menunggu validasi." 
        />
    @else
        <div class="overflow-x-auto">
            <x-ui.table>
                <x-slot name="header">
                    <tr>
                        <th>Pegawai</th>
                        <th>Data Lama</th>
                        <th>Pengajuan Baru</th>
                        <th>Status Pengajuan</th>
                        <th>Waktu Pengajuan</th>
                        <th>Aksi</th>
                    </tr>
                </x-slot>

                @foreach($pengajuan as $item)
                    <tr>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-medium text-ui-text-primary">{{ $item->pegawai->user->name ?? '-' }}</span>
                                <span class="text-xs text-ui-text-secondary">{{ $item->pegawai->user->nip ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-xs text-ui-text-secondary">
                            <div>Unit: {{ $item->pegawai->unitkerja->nama_unitkerja ?? '-' }}</div>
                            <div>Gol: {{ $item->pegawai->golongan->nama_golongan ?? '-' }}</div>
                            <div>Jab: {{ $item->pegawai->jabatan->nama_jabatan ?? '-' }}</div>
                            <div>Status: {{ $item->pegawai->status_pegawai === 'aktif' ? 'Aktif' : 'Nonaktif' }}</div>
                        </td>
                        <td class="text-xs text-ui-text-primary font-medium">
                            <div>Unit: {{ $item->unitkerja->nama_unitkerja ?? '-' }}</div>
                            <div>Gol: {{ $item->golongan->nama_golongan ?? '-' }}</div>
                            <div>Jab: {{ $item->jabatan->nama_jabatan ?? '-' }}</div>
                            <div>Status: {{ $item->status_pegawai === 'aktif' ? 'Aktif' : 'Nonaktif' }}</div>
                        </td>
                        <td>
                            @if($item->status === 'menunggu_verifikasi')
                                <x-ui.badge variant="warning" size="sm">Menunggu</x-ui.badge>
                            @elseif($item->status === 'disetujui')
                                <x-ui.badge variant="success" size="sm">Disetujui</x-ui.badge>
                            @else
                                <x-ui.badge variant="danger" size="sm">Ditolak</x-ui.badge>
                            @endif
                        </td>
                        <td class="text-xs">{{ $item->created_at->format('d M Y H:i') }}</td>
                        <td>
                            @if($item->status === 'menunggu_verifikasi')
                                <div class="flex gap-2">
                                    <form action="{{ route('admin.validasi-kepegawaian.process', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <x-ui.button type="submit" variant="success" size="sm" leadingIcon="check" onclick="return confirm('Setujui perubahan data ini?')">Setujui</x-ui.button>
                                    </form>
                                    <form action="{{ route('admin.validasi-kepegawaian.process', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <x-ui.button type="submit" variant="danger" size="sm" leadingIcon="x" onclick="return confirm('Tolak perubahan data ini?')">Tolak</x-ui.button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-ui-text-secondary italic">Telah Diproses</span>
                            @endif
                        </td>
                    </tr>
                @endforeach

                @if($pengajuan->hasPages())
                    <x-slot name="pagination">
                        {!! $pengajuan->links() !!}
                    </x-slot>
                @endif
            </x-ui.table>
        </div>
    @endif
</x-ui.card>

@endsection
