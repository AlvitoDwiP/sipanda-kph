@extends('layouts.master')

@section('title', 'Notifikasi')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 space-y-4">
    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Notifikasi</h1>
            <p class="text-sm text-slate-500">Notifikasi internal yang perlu ditindaklanjuti.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('notifications.index', ['filter' => 'all']) }}" class="px-3 py-2 rounded-lg text-sm {{ $filter === 'all' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700' }}">Semua</a>
            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="px-3 py-2 rounded-lg text-sm {{ $filter === 'unread' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700' }}">Belum Dibaca</a>
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button class="px-3 py-2 rounded-lg bg-slate-700 text-white text-sm">Tandai Semua Dibaca</button>
            </form>
        </div>
    </div>

    <p class="text-sm text-slate-600">Belum dibaca: <span class="font-semibold">{{ $unreadCount }}</span></p>

    <div class="space-y-3">
        @forelse($notifications as $item)
            @php
                $data = $item->data;
            @endphp
            <div class="border rounded-lg p-4 {{ $item->read_at ? 'bg-white border-slate-200' : 'bg-emerald-50 border-emerald-200' }}">
                <div class="flex justify-between gap-3">
                    <div>
                        <p class="font-semibold text-slate-800">{{ $data['title'] ?? '-' }}</p>
                        <p class="text-sm text-slate-600 mt-1">{{ $data['message'] ?? '-' }}</p>
                        <div class="mt-2 text-xs text-slate-500 flex gap-2">
                            <span class="px-2 py-1 rounded bg-slate-100">{{ str_replace('_', ' ', $data['category'] ?? 'umum') }}</span>
                            <span>{{ $item->created_at->diffForHumans() }}</span>
                            <span>{{ $item->read_at ? 'Sudah dibaca' : 'Belum dibaca' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <form method="POST" action="{{ route('notifications.read', $item->id) }}">
                            @csrf
                            <button class="text-sm px-3 py-2 rounded bg-emerald-600 text-white">Lihat Detail</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-slate-500 py-8">Belum ada notifikasi.</div>
        @endforelse
    </div>

    <div>{{ $notifications->links() }}</div>
</div>
@endsection
