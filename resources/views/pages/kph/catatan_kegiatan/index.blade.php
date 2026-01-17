@extends('layouts.master')

@section('title', 'Catatan Kegiatan Pegawai')
@section('page-title', 'Catatan Kegiatan Pegawai')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">
            Catatan Kegiatan Pegawai
        </h3>
    </div>

    <!-- Table -->
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 uppercase text-xs">
                        <th class="pb-3 text-left">No</th>
                        <th class="pb-3 text-left">Pegawai</th>
                        <th class="pb-3 text-left">Unit Kerja</th>
                        <th class="pb-3 text-left">Periode</th>
                        <th class="pb-3 text-left">Judul</th>
                        <th class="pb-3 text-left">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($catatan as $i => $item)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="py-4">{{ $i + 1 }}</td>

                        <!-- Pegawai -->
                        <td class="py-4">
                            <div class="font-medium text-slate-800">
                                {{ $item->pegawai->user->name ?? '-' }}
                            </div>
                            <div class="text-xs text-slate-500">
                                NIP: {{ $item->pegawai->user->nip ?? '-' }}
                            </div>
                        </td>

                        <!-- Unit Kerja -->
                        <td class="py-4">
                            {{ $item->pegawai->unitkerja->nama_unitkerja ?? '-' }}
                        </td>

                        <!-- Periode -->
                        <td class="py-4">
                            {{ \Carbon\Carbon::create()->month($item->periode_bulan)->translatedFormat('F') }}
                            {{ $item->periode_tahun }}
                        </td>

                        <!-- Judul -->
                        <td class="py-4">
                            <div class="font-medium text-slate-800">
                                {{ $item->judul }}
                            </div>
                            <div class="text-xs text-slate-500 line-clamp-1">
                                {{ $item->deskripsi }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="py-4">
                            @if ($item->status === 'draft')
                            <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-full">
                                Draft
                            </span>
                            @elseif ($item->status === 'ajukan')
                            <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                                Diajukan
                            </span>
                            @elseif ($item->status === 'setuju')
                            <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                Disetujui
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">
                                Ditolak
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            Catatan kegiatan belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection