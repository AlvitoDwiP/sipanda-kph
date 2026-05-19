@extends('layouts.master')

@section('title', 'Detail Catatan Kegiatan')
@section('page-title', 'Detail Catatan Kegiatan')

@section('content')
@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-800 text-sm">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">
    <div class="p-6 border-b border-slate-100 flex justify-between">
        <h3 class="font-bold text-slate-800">Detail Catatan Kegiatan</h3>
        <a href="{{ route('admin.catatan_kegiatan.index') }}" class="text-sm text-slate-500">Kembali</a>
    </div>

    <div class="p-6 space-y-3 text-sm">
        <div><strong>Pegawai:</strong> {{ $catatan->pegawai->user->name ?? '-' }}</div>
        <div><strong>Tugas:</strong> {{ $catatan->penugasan->tugas->judul ?? '-' }}</div>
        <div><strong>Deadline Tugas:</strong> {{ optional($catatan->penugasan->tugas->deadline)->format('d-m-Y') ?? '-' }}</div>
        <div><strong>Status Verifikasi:</strong> {{ $catatan->status_verifikasi_label }}</div>
        <div><strong>Deskripsi:</strong><br>{{ $catatan->deskripsi }}</div>
        <div><strong>Hasil:</strong><br>{{ $catatan->hasil_kegiatan ?? '-' }}</div>
        <div><strong>Kendala:</strong><br>{{ $catatan->kendala ?? '-' }}</div>
        <div><strong>Catatan Verifikasi:</strong><br>{{ $catatan->catatan_verifikasi ?? '-' }}</div>
        <div><strong>Diverifikasi Oleh:</strong> {{ $catatan->verifier->name ?? '-' }}</div>
        <div><strong>Waktu Verifikasi:</strong> {{ optional($catatan->diverifikasi_at)->format('d-m-Y H:i') ?? '-' }}</div>

        @if($catatan->status_verifikasi === 'menunggu_verifikasi')
            <div class="pt-4 border-t space-y-3">
                <form method="POST" action="{{ route('admin.catatan_kegiatan.setujui', $catatan->id) }}">
                    @csrf
                    <button class="px-4 py-2 rounded bg-green-700 text-white text-sm">Setujui</button>
                </form>

                <form method="POST" action="{{ route('admin.catatan_kegiatan.revisi', $catatan->id) }}" class="space-y-2">
                    @csrf
                    <textarea name="catatan_verifikasi" rows="3" class="w-full border rounded px-3 py-2" placeholder="Catatan revisi" required></textarea>
                    <button class="px-4 py-2 rounded bg-amber-600 text-white text-sm">Minta Revisi</button>
                </form>

                <form method="POST" action="{{ route('admin.catatan_kegiatan.tolak', $catatan->id) }}" class="space-y-2">
                    @csrf
                    <textarea name="catatan_verifikasi" rows="3" class="w-full border rounded px-3 py-2" placeholder="Alasan penolakan" required></textarea>
                    <button class="px-4 py-2 rounded bg-red-700 text-white text-sm">Tolak</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
