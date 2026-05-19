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
                <p class="text-slate-500">Status Tugas</p>
                <p class="font-medium text-slate-800">Lihat per penerima di tabel bawah</p>
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
                            <th class="px-3 py-2 text-left">Progres</th>
                            <th class="px-3 py-2 text-left">Terlambat</th>
                            <th class="px-3 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($penugasan->penugasan as $item)
                            <tr>
                                <td class="px-3 py-2">{{ $item->pegawai->user->name ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $item->pegawai->user->nip ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $item->status }}</td>
                                <td class="px-3 py-2">{{ $item->progres_persen ?? 0 }}%</td>
                                <td class="px-3 py-2">
                                    @if($item->is_terlambat)
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Terlambat</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    @if($item->status === 'menunggu_verifikasi')
                                        <div class="flex flex-col gap-2">
                                            <form method="POST" action="{{ route($routePrefix . '.penugasan.setujui', $item->id) }}">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 text-xs rounded bg-green-700 text-white">Setujui</button>
                                            </form>
                                            <form method="POST" action="{{ route($routePrefix . '.penugasan.revisi', $item->id) }}" class="flex flex-col gap-1">
                                                @csrf
                                                <textarea name="catatan_revisi" rows="2" class="w-full border rounded p-1 text-xs" placeholder="Catatan revisi" required></textarea>
                                                <button type="submit" class="px-2 py-1 text-xs rounded bg-amber-600 text-white">Minta Revisi</button>
                                            </form>
                                        </div>
                                    @elseif(!in_array($item->status, ['selesai', 'dibatalkan']))
                                        <form method="POST" action="{{ route($routePrefix . '.penugasan.batalkan', $item->id) }}" class="flex flex-col gap-1">
                                            @csrf
                                            <input type="text" name="alasan_pembatalan" class="w-full border rounded p-1 text-xs" placeholder="Alasan pembatalan (opsional)">
                                            <button type="submit" class="px-2 py-1 text-xs rounded bg-red-600 text-white">Batalkan</button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 text-xs">Tidak ada aksi</span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="bg-slate-50">
                                <td colspan="6" class="px-3 py-2 text-xs text-slate-600">
                                    <strong>Catatan Progres:</strong> {{ $item->catatan_progres ?? '-' }}<br>
                                    <strong>Catatan Revisi:</strong> {{ $item->catatan_revisi ?? '-' }}<br>
                                    <strong>Update Progres:</strong> {{ $item->progres_updated_at?->format('d-m-Y H:i') ?? '-' }}<br>
                                    <strong>Selesai At:</strong> {{ $item->selesai_at?->format('d-m-Y H:i') ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-slate-400">Belum ada penerima tugas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h4 class="font-semibold text-slate-800 mb-3">Riwayat Status</h4>
            <div class="border rounded-lg p-3 text-sm">
                @php
                    $histories = $penugasan->penugasan->flatMap->statusHistories->sortByDesc('created_at');
                @endphp
                @forelse($histories as $history)
                    <div class="py-2 border-b last:border-b-0">
                        <div class="font-medium">{{ $history->status_sebelum ?? '-' }} → {{ $history->status_sesudah }}</div>
                        <div class="text-xs text-slate-500">{{ $history->created_at?->format('d-m-Y H:i') }} oleh {{ $history->user->name ?? '-' }}</div>
                        <div class="text-xs">{{ $history->catatan ?? '-' }}</div>
                    </div>
                @empty
                    <div class="text-slate-400">Belum ada riwayat status.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
