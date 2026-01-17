@extends('layouts.master')

@section('title', 'Log Aktifitas')
@section('page-title', 'Log Aktifitas')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-slate-100">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Log Aktifitas</h3>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 uppercase text-xs">
                        <th class="pb-3 text-left">No</th>
                        <th class="pb-3 text-left">Nama Pengguna</th>
                        <th class="pb-3 text-left">Aktifitas</th>
                        <th class="pb-3 text-left">Waktu</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $i => $log)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4">{{ $i + 1 }}</td>

                        <td class="py-4 font-medium text-slate-800">
                            {{ $log->user->name ?? 'User tidak ditemukan' }}
                        </td>

                        <td class="py-4">
                            {{ $log->aksi }}
                        </td>

                        <td class="py-4">
                            {{ $log->created_at->format('d M Y H:i:s') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400">
                            Data log aktivitas belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
