@extends('layouts.master')

@section('title', 'Catatan Kegiatan Pegawai')
@section('page-title', 'Catatan Kegiatan Pegawai')

@section('content')
@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-700 text-sm">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Catatan Kegiatan Pegawai</h3>
        <form method="GET" class="flex items-center gap-2 text-sm">
            <select name="status" class="border rounded px-3 py-2">
                <option value="">Semua Status</option>
                <option value="menunggu_verifikasi" @selected(request('status') === 'menunggu_verifikasi')>Menunggu Verifikasi</option>
                <option value="disetujui" @selected(request('status') === 'disetujui')>Disetujui</option>
                <option value="revisi" @selected(request('status') === 'revisi')>Revisi</option>
                <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
            </select>
            <button class="px-3 py-2 bg-green-800 text-white rounded">Filter</button>
        </form>
    </div>

    <div class="p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-slate-500 uppercase text-xs">
                    <th class="pb-3 text-left">Tanggal</th>
                    <th class="pb-3 text-left">Pegawai</th>
                    <th class="pb-3 text-left">Tugas</th>
                    <th class="pb-3 text-left">Status</th>
                    <th class="pb-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($catatan as $item)
                    <tr>
                        <td class="py-3">{{ optional($item->tanggal_kegiatan)->format('d-m-Y') ?? '-' }}</td>
                        <td class="py-3">{{ $item->pegawai->user->name ?? '-' }}</td>
                        <td class="py-3">{{ $item->penugasan->tugas->judul ?? '-' }}</td>
                        <td class="py-3">{{ $item->status_verifikasi_label }}</td>
                        <td class="py-3 text-right"><a href="{{ route('kph.catatan_kegiatan.show', $item->id) }}" class="text-blue-700">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-slate-400">Catatan kegiatan belum tersedia</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
