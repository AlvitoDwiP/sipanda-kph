@extends('layouts.master')

@section('title', 'Detail Tugas')
@section('page-title', 'Detail Tugas Saya')

@section('content')
@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-700 text-sm">
    {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Detail Tugas Harian</h3>
        <a href="{{ route('pegawai.tugas.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Kembali</a>
    </div>

    <div class="p-6 space-y-6 text-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-slate-500">Judul</p>
                <p class="font-medium text-slate-800">{{ $tugas->judul }}</p>
            </div>
            <div>
                <p class="text-slate-500">Pemberi Tugas</p>
                <p class="font-medium text-slate-800">{{ $tugas->user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-500">Tanggal Tugas</p>
                <p class="font-medium text-slate-800">{{ optional($tugas->tanggal_tugas)->format('d-m-Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-500">Deadline</p>
                <p class="font-medium text-slate-800">{{ optional($tugas->deadline)->format('d-m-Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-500">Prioritas</p>
                <p class="font-medium text-slate-800 capitalize">{{ $tugas->prioritas }}</p>
            </div>
            <div>
                <p class="text-slate-500">Status</p>
                <p class="font-medium text-slate-800">{{ $penugasanSaya->status }}</p>
            </div>
            <div>
                <p class="text-slate-500">Progres</p>
                <p class="font-medium text-slate-800">{{ $penugasanSaya->progres_persen ?? 0 }}%</p>
            </div>
            <div>
                <p class="text-slate-500">Kondisi</p>
                @if($penugasanSaya->is_terlambat)
                    <span class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Terlambat</span>
                @else
                    <p class="font-medium text-slate-800">Normal</p>
                @endif
            </div>
            <div class="md:col-span-2">
                <p class="text-slate-500">Instruksi</p>
                <p class="font-medium text-slate-800">{{ $tugas->deskripsi }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-slate-500">Catatan Progres Terakhir</p>
                <p class="font-medium text-slate-800">{{ $penugasanSaya->catatan_progres ?? '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-slate-500">Catatan Revisi</p>
                <p class="font-medium text-slate-800">{{ $penugasanSaya->catatan_revisi ?? '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-slate-500">Template</p>
                @if ($tugas->template)
                    <a href="{{ asset('storage/' . $tugas->template) }}" target="_blank" class="text-blue-600 hover:underline">
                        Lihat Template
                    </a>
                @else
                    <p class="font-medium text-slate-800">-</p>
                @endif
            </div>
        </div>

        <div class="border-t pt-6 space-y-4">
            @if (in_array($penugasanSaya->status, ['sedang_dikerjakan', 'revisi', 'menunggu_verifikasi', 'proses']))
                <a href="{{ route('pegawai.tugas.catatan.create', $penugasanSaya->id) }}" class="inline-flex px-4 py-2 rounded bg-indigo-700 text-white text-sm">
                    Buat Catatan Kegiatan
                </a>
            @endif

            @if (in_array($penugasanSaya->status, ['selesai']))
                <div class="px-3 py-2 rounded bg-green-50 text-green-700 text-sm">Tugas sudah selesai dan tidak dapat diubah.</div>
            @elseif (in_array($penugasanSaya->status, ['dibatalkan']))
                <div class="px-3 py-2 rounded bg-red-50 text-red-700 text-sm">Tugas dibatalkan dan tidak dapat diubah.</div>
            @else
                @if (in_array($penugasanSaya->status, ['belum_dikerjakan', 'baru']))
                    <form method="POST" action="{{ route('pegawai.tugas.mulai', $penugasanSaya->id) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded bg-blue-700 text-white text-sm">Mulai Kerjakan</button>
                    </form>
                @endif

                @if (in_array($penugasanSaya->status, ['sedang_dikerjakan', 'revisi', 'proses']))
                    <form method="POST" action="{{ route('pegawai.tugas.progres', $penugasanSaya->id) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm mb-1">Progres (%)</label>
                            <input type="number" name="progres_persen" min="0" max="100" value="{{ old('progres_persen', $penugasanSaya->progres_persen ?? 0) }}" class="w-full border rounded px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Catatan Progres</label>
                            <textarea name="catatan_progres" rows="3" class="w-full border rounded px-3 py-2 text-sm" required>{{ old('catatan_progres', $penugasanSaya->catatan_progres) }}</textarea>
                        </div>
                        <button type="submit" class="px-4 py-2 rounded bg-amber-600 text-white text-sm">Update Progres</button>
                    </form>

                    <form method="POST" action="{{ route('pegawai.tugas.kirim-verifikasi', $penugasanSaya->id) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded bg-green-700 text-white text-sm">Kirim untuk Verifikasi</button>
                    </form>
                @endif
            @endif
        </div>

        <div class="border-t pt-6">
            <h4 class="font-semibold text-slate-800 mb-3">Catatan Kegiatan Terkait Tugas</h4>
            <div class="space-y-2">
                @forelse($catatanTerkait as $catatan)
                    <div class="border rounded p-3 text-sm">
                        <div class="font-medium">{{ optional($catatan->tanggal_kegiatan)->format('d-m-Y') ?? '-' }} - {{ $catatan->status_verifikasi_label }}</div>
                        <div class="text-slate-600">{{ \Illuminate\Support\Str::limit($catatan->hasil_kegiatan ?? $catatan->deskripsi, 140) }}</div>
                        @if($catatan->catatan_verifikasi)
                            <div class="text-amber-700 mt-1">Catatan verifikasi: {{ $catatan->catatan_verifikasi }}</div>
                        @endif
                        <a href="{{ route('pegawai.catatan_kegiatan.show', $catatan->id) }}" class="text-blue-700">Lihat detail</a>
                    </div>
                @empty
                    <div class="text-sm text-slate-500">Belum ada catatan kegiatan untuk tugas ini.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
