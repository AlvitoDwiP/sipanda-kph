@extends('layouts.master')

@section('title', 'Data Notifikasi')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <x-ui.page-header title="Notifikasi Admin" subtitle="Notifikasi sistem internal dalam 2 hari terakhir.">
        <x-slot name="breadcrumbs">
            <x-ui.breadcrumb />
        </x-slot>
        <x-slot name="actions">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.notifikasi.index') }}" class="w-full sm:w-auto">
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

        foreach ($userBaru as $user) {
            $notifications->push([
                'id'          => $user->id,
                'judul'       => $user->name ?? 'User Baru',
                'deskripsi'   => 'Akun user baru menunggu aktivasi',
                'tipe'        => 'User Baru',
                'created_at'  => $user->created_at,
                'type'        => 'user',
                'status'      => $user->status_akun,
                'user_info'   => $user,
                'link'        => route('admin.register.index'),
            ]);
        }

        foreach ($perubahanDataDiri as $dataDiri) {
            $pegawai = $dataDiri->pegawai;
            $user = $pegawai ? $pegawai->user : null;

            $notifications->push([
                'id'          => $dataDiri->id,
                'judul'       => 'Perubahan Data Diri',
                'deskripsi'   => 'Pegawai melakukan perubahan data diri',
                'tipe'        => 'Data Diri',
                'created_at'  => $dataDiri->updated_at,
                'type'        => 'data_diri',
                'status'      => null,
                'user_info'   => $user,
                'link'        => route('admin.pegawai.index'),
            ]);
        }

        // If search is applied, filter notifications based on user info
        if(request('q')) {
            $searchTerm = strtolower(request('q'));
            $notifications = $notifications->filter(function($notification) use ($searchTerm) {
                if($notification['user_info']) {
                    $user = $notification['user_info'];
                    return strpos(strtolower($user->name ?? ''), $searchTerm) !== false ||
                           strpos(strtolower($user->email ?? ''), $searchTerm) !== false ||
                           strpos(strtolower($user->nip ?? ''), $searchTerm) !== false;
                }
                return false;
            });
        }

        $notifications = $notifications->sortByDesc('created_at')->values();
    @endphp

    <!-- Search Results Info -->
    @if(request('q'))
        <div class="p-3 bg-ui-info-soft text-ui-info border border-ui-info/10 rounded-ui-md text-xs sm:text-sm flex items-center justify-between">
            <span>Menemukan <strong>{{ $notifications->count() }}</strong> notifikasi dari pencarian "{{ request('q') }}"</span>
            <a href="{{ route('admin.notifikasi.index') }}" class="underline font-semibold hover:text-blue-800">Reset</a>
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
                            <x-ui.badge :variant="$notification['type'] === 'user' ? 'primary' : 'warning'" styleType="soft" size="sm">
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
                        <x-ui.button variant="outline" size="sm" leadingIcon="eye" :href="$notification['link'] ?? '#'">
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
