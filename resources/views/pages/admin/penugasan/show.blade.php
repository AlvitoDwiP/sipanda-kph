@extends('layouts.master')
@php($routePrefix = $routePrefix ?? 'admin')

@section('title', 'Detail Penugasan')
@section('page-title', 'Detail Penugasan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Detail Tugas Harian</h3>
        <a href="{{ route($routePrefix . '.penugasan.index') }}"
            class="text-sm text-slate-500 hover:text-slate-700">Kembali</a>
    </div>

    <div class="p-6 space-y-6 text-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-slate-500">Judul</p>
                <p class="font-medium text-slate-800">{{ $penugasan->judul }}</p>
            </div>
            <div>
                <p class="text-slate-500">Pemberi Tugas</p>
                <p class="font-medium text-slate-800">{{ $penugasan->user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-500">Tanggal Tugas</p>
                <p class="font-medium text-slate-800">{{ optional($penugasan->tanggal_tugas)->format('d-m-Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-500">Deadline</p>
                <p class="font-medium text-slate-800">{{ optional($penugasan->deadline)->format('d-m-Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-500">Prioritas</p>
                <p class="font-medium text-slate-800 capitalize">{{ $penugasan->prioritas }}</p>
            </div>
            <div>
                <p class="text-slate-500">Status Awal</p>
                <p class="font-medium text-slate-800">belum_dikerjakan</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-slate-500">Instruksi Singkat</p>
                <p class="font-medium text-slate-800">{{ $penugasan->deskripsi }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-slate-500">Template</p>
                @if ($penugasan->template)
                    <a href="{{ asset('storage/' . $penugasan->template) }}" target="_blank" class="text-blue-600 hover:underline">
                        Lihat Template
                    </a>
                @else
                    <p class="font-medium text-slate-800">-</p>
                @endif
            </div>
        </div>

        <div>
            <h4 class="font-semibold text-slate-800 mb-3">Daftar Pegawai Penerima</h4>
            <div class="overflow-x-auto border rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-3 py-2 text-left">Nama</th>
                            <th class="px-3 py-2 text-left">NIP</th>
                            <th class="px-3 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($penugasan->penugasan as $item)
                            <tr>
                                <td class="px-3 py-2">{{ $item->pegawai->user->name ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $item->pegawai->user->nip ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $item->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-center text-slate-400">Belum ada penerima tugas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
