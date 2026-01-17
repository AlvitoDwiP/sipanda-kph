@extends('layouts.master')

@section('title', 'Data Notifikasi')
@section('page-title', 'Data Notifikasi')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <div class="p-6 border-b border-slate-100">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="font-bold text-slate-800">Data Notifikasi (2 Hari Terakhir)</h3>

            <!-- Search Form -->
            <form method="GET" action="{{ route('pegawai.notifikasi.index') }}" class="w-full sm:w-auto">
                <div class="relative">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari notifikasi..."
                        class="w-full sm:w-64 px-4 py-2 pl-10 border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                    >
                    <div class="absolute left-3 top-2.5 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    @if(request('q'))
                        <a href="{{ route('pegawai.notifikasi.index') }}"
                           class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="p-6 space-y-4">

        @php
        $notifications = collect();

        foreach ($tugas as $task) {
        $notifications->push([
        'id' => $task->id,
        'judul' => $task->judul,
        'deskripsi' => 'Kamu mendapatkan tugas baru',
        'tipe' => 'Tugas',
        'created_at'=> $task->created_at,
        'type' => 'tugas',
        'link' => route('pegawai.tugas.index'),
        'status' => null,
        'searchable_text' => $task->judul . ' ' . $task->deskripsi, // For search filtering
        ]);
        }

        foreach ($catatanKegiatan as $activity) {
        $notifications->push([
        'id' => $activity->id,
        'judul' => $activity->judul,
        'deskripsi' => $activity->status === 'tolak'
        ? 'Catatan kegiatan kamu ditolak'
        : 'Catatan kegiatan kamu diterima',
        'tipe' => 'Catatan Kegiatan',
        'created_at'=> $activity->created_at,
        'type' => 'catatan',
        'status' => $activity->status,
        'link' => null,
        'searchable_text' => $activity->judul . ' ' . $activity->deskripsi, // For search filtering
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
        <div class="mb-4 p-3 bg-blue-50 text-blue-700 rounded-lg text-sm">
            Menemukan {{ $notifications->count() }} notifikasi dari pencarian "{{ request('q') }}"
            <a href="{{ route('pegawai.notifikasi.index') }}" class="ml-2 underline">Hapus pencarian</a>
        </div>
        @endif

        @forelse($notifications as $notification)
        <div class="flex items-start gap-4 p-4 border border-slate-100 rounded-lg hover:bg-slate-50 transition">

            <div class="flex-shrink-0">
                @if($notification['type'] === 'tugas')
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12
                   a2 2 0 002 2h10
                   a2 2 0 002-2V7
                   a2 2 0 00-2-2h-2
                   M9 5a2 2 0 002 2h2
                   a2 2 0 002-2
                   M9 5a2 2 0 012-2h2
                   a2 2 0 012 2" />
                    </svg>
                </div>
                @else
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6
                   M7 4h5.586
                   a1 1 0 01.707.293
                   l4.414 4.414
                   a1 1 0 01.293.707V20
                   a2 2 0 01-2 2H7
                   a2 2 0 01-2-2V6
                   a2 2 0 012-2z" />
                    </svg>
                </div>
                @endif

            </div>

            <div class="flex-1">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold text-slate-800">
                            {{ $notification['judul'] }}
                        </h4>
                        <p class="text-sm text-slate-600 mt-1">
                            {{ $notification['deskripsi'] }}
                        </p>
                    </div>

                    <span class="text-xs text-slate-500 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                    </span>
                </div>

                <div class="mt-3 flex items-center justify-between">
                    <span class="text-xs font-medium px-3 py-1 rounded-full
                        {{ $notification['type'] === 'tugas'
                            ? 'bg-blue-100 text-blue-700'
                            : 'bg-green-100 text-green-700' }}">
                        {{ $notification['tipe'] }}
                    </span>

                    @if($notification['link'])
                    <a href="{{ $notification['link'] }}"
                        class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">
                        Detail
                    </a>
                    @else
                    <a href="{{ route('pegawai.catatan_kegiatan.index') }}"
                        class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">
                        Detail
                    </a>

                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center text-slate-500 py-10">
            @if(request('q'))
                Tidak ditemukan notifikasi yang cocok dengan pencarian "{{ request('q') }}"
            @else
                Tidak ada notifikasi dalam 2 hari terakhir
            @endif
        </div>
        @endforelse

    </div>
</div>

<script>
    function showNotificationDetail(notification) {
        let message =
            'Judul: ' + notification.judul + '\n' +
            'Deskripsi: ' + notification.deskripsi + '\n' +
            'Tipe: ' + notification.tipe + '\n' +
            'Tanggal: ' + notification.created_at;

        if (notification.status) {
            message += '\nStatus: ' +
                (notification.status === 'tolak' ? 'Ditolak' : 'Diterima');
        }

        alert(message);
    }
</script>

@endsection