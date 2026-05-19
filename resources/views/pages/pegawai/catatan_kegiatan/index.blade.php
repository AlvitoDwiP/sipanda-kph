@extends('layouts.master')

@section('title', 'Catatan Kegiatan')
@section('page-title', 'Catatan Kegiatan')

@section('content')
@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-800 text-sm">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">
    <div class="p-6 border-b border-slate-100">
        <h3 class="font-bold text-slate-800">Daftar Catatan Kegiatan</h3>
    </div>

    <div class="p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-slate-500 uppercase text-xs">
                    <th class="pb-3 text-left">Tanggal</th>
                    <th class="pb-3 text-left">Tugas</th>
                    <th class="pb-3 text-left">Ringkasan</th>
                    <th class="pb-3 text-left">Status</th>
                    <th class="pb-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($catatan as $item)
                <tr>
                    <td class="py-3">{{ optional($item->tanggal_kegiatan)->format('d-m-Y') ?? '-' }}</td>
                    <td class="py-3">{{ $item->penugasan->tugas->judul ?? '-' }}</td>
                    <td class="py-3">{{ \Illuminate\Support\Str::limit($item->hasil_kegiatan ?? $item->deskripsi, 90) }}</td>
                    <td class="py-3">{{ $item->status_verifikasi_label }}</td>
                    <td class="py-3 text-right">
                        <a href="{{ route('pegawai.catatan_kegiatan.show', $item->id) }}" class="text-blue-700">Detail</a>
                        @if($item->status_verifikasi === 'revisi')
                            <span class="mx-2 text-slate-300">|</span>
                            <a href="{{ route('pegawai.catatan_kegiatan.edit', $item->id) }}" class="text-amber-700">Edit</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-400">Catatan kegiatan belum tersedia</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
