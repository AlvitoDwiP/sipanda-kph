@extends('layouts.master')

@section('title', 'Data Notifikasi')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <x-ui.page-header title="Notifikasi Pegawai" subtitle="Notifikasi tugas dan kegiatan Anda dalam 2 hari terakhir.">
        <x-slot name="breadcrumbs">
            <x-ui.breadcrumb />
        </x-slot>
        <x-slot name="actions">
            <!-- Search Form -->
            <form method="GET" action="{{ route('pegawai.notifikasi.index') }}" class="w-full sm:w-auto">
                <x-ui.input 
                    type="search" 
                    name="q" 
                    placeholder="Cari notifikasi..." 
                    value="{{ request('q') }}"
                    prefixIcon="search"
                />
            </form>
        </x-slot>
    </x-ui.page-header>

    @php
        $notifications = collect();

        foreach ($tugas as $task) {
            $notifications->push([
                'id' => $task->id,
                'judul' => $task->judul,
                'deskripsi' => 'Anda mendapatkan tugas baru',
                'tipe' => 'Tugas Baru',
                'created_at'=> $task->created_at,
                'type' => 'tugas',
                'link' => route('pegawai.tugas.index'),
                'status' => null,
                'searchable_text' => $task->judul . ' ' . $task->deskripsi,
            ]);
        }

        foreach ($catatanKegiatan as $activity) {
            $notifications->push([
                'id' => $activity->id,
                'judul' => $activity->judul,
                'deskripsi' => $activity->status === 'tolak'
                    ? 'Catatan kegiatan Anda ditolak oleh verifikator'
                    : 'Catatan kegiatan Anda disetujui oleh verifikator',
                'tipe' => 'Catatan Kegiatan',
                'created_at'=> $activity->created_at,
                'type' => 'catatan',
                'status' => $activity->status,
                'link' => route('pegawai.catatan_kegiatan.index'),
                'searchable_text' => $activity->judul . ' ' . $activity->deskripsi,
            ]);
        }

        // If search is applied, filter notifications based on searchable text
        if(request('q')) {
            $searchTerm = strtolower(request('q'));
            $notifications = $notifications->filter(function($notification) use ($searchTerm) {
                return strpos(strtolower($notification['searchable_text']), $searchTerm) !== false;
            });
        }

        $notifications = $notifications->sortByDesc('created_at')->values();
    @endphp

    <!-- Search Results Info -->
    @if(request('q'))
        <div class="p-3 bg-ui-info-soft text-ui-info border border-ui-info/10 rounded-ui-md text-xs sm:text-sm flex items-center justify-between">
            <span>Menemukan <strong>{{ $notifications->count() }}</strong> notifikasi dari pencarian "{{ request('q') }}"</span>
            <a href="{{ route('pegawai.notifikasi.index') }}" class="underline font-semibold hover:text-blue-800">Reset</a>
        </div>
    @endif

    <!-- NOTIFICATION LIST -->
    <div class="space-y-3">
        @forelse($notifications as $notification)
            <x-ui.card variant="default">
                <div class="flex items-start justify-between gap-4 flex-wrap sm:flex-nowrap">
                    <div class="space-y-1.5 flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold text-xs sm:text-sm text-ui-text-primary truncate">
                                {{ $notification['judul'] }}
                            </h4>
                            <x-ui.badge :variant="$notification['type'] === 'tugas' ? 'info' : ($notification['status'] === 'tolak' ? 'danger' : 'success')" styleType="soft" size="sm">
                                {{ $notification['tipe'] }}
                            </x-ui.badge>
                        </div>
                        <p class="text-xs sm:text-sm text-ui-text-secondary leading-relaxed">
                            {{ $notification['deskripsi'] }}
                        </p>
                        <div class="text-[10px] text-ui-muted font-semibold mt-1">
                            {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                        </div>
                    </div>
                    <div class="shrink-0 self-center">
                        <x-ui.button variant="outline" size="sm" leadingIcon="eye" :href="$notification['link']">
                            Detail
                        </x-ui.button>
                    </div>
                </div>
            </x-ui.card>
        @empty
            <x-ui.empty-state 
                icon="bell-off" 
                title="Tidak Ada Notifikasi" 
                description="{{ request('q') ? 'Tidak ditemukan notifikasi yang cocok dengan pencarian Anda.' : 'Tidak ada notifikasi baru dalam 2 hari terakhir.' }}" 
            />
        @endforelse
    </div>

</div>
@endsection