@extends('layouts.master')

@section('title', 'Riwayat Kepegawaian')
@section('page-title', 'Riwayat Kepegawaian')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Riwayat Kepegawaian</h3>
    </div>

    <!-- Table -->
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 uppercase text-xs">
                        <th class="pb-3 text-left">No</th>
                        <th class="pb-3 text-left">Nama</th>
                        <th class="pb-3 text-left">Unit Kerja</th>
                        <th class="pb-3 text-left">Golongan</th>
                        <th class="pb-3 text-left">Jabatan</th>
                        <th class="pb-3 text-left">Tgl Mulai</th>
                        <th class="pb-3 text-left">Tgl Selesai</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($riwayat as $i => $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4">{{ $i + 1 }}</td>
                        <td class="py-4 font-medium text-slate-800">
                            {{ $item->user->name ?? '-' }}
                        </td>
                        <td class="py-4">
                            {{ $item->unitkerja->nama_unitkerja ?? '-' }}
                        </td>
                        <td class="py-4">
                            {{ $item->golongan->nama_golongan ?? '-' }}
                        </td>
                        <td class="py-4">
                            {{ $item->jabatan->nama_jabatan ?? '-' }}
                        </td>
                        <td class="py-4">
                            {{ \Carbon\Carbon::parse($item->tgl_mulai)->format('d-m-Y') }}
                        </td>
                        <td class="py-4">
                            @if (!$item->tgl_selesai)
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                Masih Aktif
                            </span>
                            @else
                            {{ \Carbon\Carbon::parse($item->tgl_selesai)->format('d-m-Y') }}
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400">
                            Data riwayat kepegawaian belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection