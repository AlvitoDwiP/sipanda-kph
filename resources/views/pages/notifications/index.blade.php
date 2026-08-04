@extends('layouts.master')

@section('title', 'Notifikasi')

@section('content')
<div class="space-y-6">
    <!-- PAGE HEADER -->
    <x-ui.page-header title="Notifikasi" subtitle="Notifikasi internal dan tugas penting yang memerlukan tindakan Anda.">
        <x-slot name="breadcrumbs">
            <x-ui.breadcrumb />
        </x-slot>
        <x-slot name="actions">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center px-3 py-1 rounded-ui-md text-xs font-bold bg-ui-primary-soft text-ui-primary border border-ui-primary/10 mr-2">
                    Belum Dibaca: {{ $unreadCount }}
                </span>
                <x-ui.button variant="secondary" size="sm" :href="route('notifications.index', ['filter' => 'all'])" class="{{ $filter === 'all' ? 'bg-ui-border text-ui-text-primary' : '' }}">
                    Semua
                </x-ui.button>
                <x-ui.button variant="secondary" size="sm" :href="route('notifications.index', ['filter' => 'unread'])" class="{{ $filter === 'unread' ? 'bg-ui-border text-ui-text-primary' : '' }}">
                    Belum Dibaca
                </x-ui.button>
                @if($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}" class="inline">
                        @csrf
                        <x-ui.button type="submit" variant="primary" size="sm" leadingIcon="check-check">
                            Tandai Semua Dibaca
                        </x-ui.button>
                    </form>
                @endif
            </div>
        </x-slot>
    </x-ui.page-header>

    <!-- NOTIFICATION LIST -->
    <div class="space-y-3">
        @forelse($notifications as $item)
            @php
                $data = $item->data;
                $unread = !$item->read_at;
            @endphp
            <x-ui.card variant="{{ $unread ? 'flat' : 'default' }}" class="{{ $unread ? 'border-l-4 border-l-ui-primary bg-ui-primary-soft/30' : '' }}">
                <div class="flex items-start justify-between gap-4 flex-wrap sm:flex-nowrap">
                    <div class="space-y-1.5 flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-bold text-xs sm:text-sm text-ui-text-primary truncate">
                                {{ $data['title'] ?? 'Notifikasi Sistem' }}
                            </span>
                            @if($unread)
                                <x-ui.badge variant="primary" styleType="solid" size="sm">Baru</x-ui.badge>
                            @endif
                        </div>
                        <p class="text-xs sm:text-sm text-ui-text-secondary leading-relaxed">
                            {{ $data['message'] ?? '-' }}
                        </p>
                        <div class="flex items-center gap-2 text-[10px] text-ui-muted font-semibold mt-1">
                            <span class="px-2 py-0.5 rounded bg-ui-border text-ui-text-secondary capitalize">
                                {{ str_replace('_', ' ', $data['category'] ?? 'umum') }}
                            </span>
                            <span>•</span>
                            <span>{{ $item->created_at->diffForHumans() }}</span>
                            @if($item->read_at)
                                <span>•</span>
                                <span class="text-ui-success flex items-center gap-0.5">
                                    <i data-lucide="check" class="w-3 h-3"></i> Sudah Dibaca
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="shrink-0 flex items-center self-center">
                        <form method="POST" action="{{ route('notifications.read', $item->id) }}">
                            @csrf
                            <x-ui.button type="submit" variant="{{ $unread ? 'primary' : 'outline' }}" size="sm" leadingIcon="eye">
                                Lihat Detail
                            </x-ui.button>
                        </form>
                    </div>
                </div>
            </x-ui.card>
        @empty
            <x-ui.empty-state 
                icon="bell-off" 
                title="Tidak Ada Notifikasi" 
                description="Semua notifikasi sudah Anda baca atau belum ada pembaruan baru untuk akun Anda saat ini." 
            />
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if($notifications->hasPages())
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
