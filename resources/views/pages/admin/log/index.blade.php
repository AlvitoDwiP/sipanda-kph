@extends('layouts.master')

@section('title', 'Log Aktivitas')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <x-ui.page-header title="Log Aktivitas Sistem" subtitle="Pantau log aksi dan riwayat kegiatan pengguna pada aplikasi SIPANDA.">
        <x-slot name="breadcrumbs">
            <x-ui.breadcrumb />
        </x-slot>
    </x-ui.page-header>

    <!-- LOG ACTIVITY CARD & TABLE -->
    <x-ui.card title="Riwayat Aktivitas" icon="history" class="overflow-hidden">
        <x-ui.table 
            :headers="['No', 'Nama Pengguna', 'Aktivitas / Aksi', 'Waktu Kejadian']"
            :empty="count($logs) === 0"
            :pagination="$logs->links()"
        >
            @foreach ($logs as $i => $log)
                <tr class="border-b border-ui-border/50 hover:bg-ui-primary-soft/10">
                    <td class="px-4 py-3 text-ui-text-secondary text-center">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-semibold text-ui-text-primary">
                        {{ $log->user->name ?? 'Pengguna Tidak Ditemukan' }}
                    </td>
                    <td class="px-4 py-3 text-ui-text-primary">{{ $log->aksi }}</td>
                    <td class="px-4 py-3 text-ui-text-secondary">
                        {{ $log->created_at->translatedFormat('d M Y H:i:s') }} WIB
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

</div>
@endsection
