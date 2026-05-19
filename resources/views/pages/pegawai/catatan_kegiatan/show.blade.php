@extends('layouts.master')

@section('title', 'Detail Catatan Kegiatan')
@section('page-title', 'Detail Catatan Kegiatan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Detail Catatan Kegiatan</h3>
        <a href="{{ route('pegawai.catatan_kegiatan.index') }}" class="text-sm text-slate-500">Kembali</a>
    </div>
    <div class="p-6 text-sm space-y-3">
        <div><strong>Tugas:</strong> {{ $catatan->penugasan->tugas->judul ?? '-' }}</div>
        <div><strong>Tanggal Kegiatan:</strong> {{ optional($catatan->tanggal_kegiatan)->format('d-m-Y') ?? '-' }}</div>
        <div><strong>Status Verifikasi:</strong> {{ $catatan->status_verifikasi_label }}</div>
        <div><strong>Deskripsi:</strong><br>{{ $catatan->deskripsi }}</div>
        <div><strong>Hasil:</strong><br>{{ $catatan->hasil_kegiatan ?? '-' }}</div>
        <div><strong>Kendala:</strong><br>{{ $catatan->kendala ?? '-' }}</div>
        <div><strong>Catatan Admin/KPH:</strong><br>{{ $catatan->catatan_verifikasi ?? '-' }}</div>
        <div><strong>Diverifikasi Oleh:</strong> {{ $catatan->verifier->name ?? '-' }}</div>
        <div><strong>Waktu Verifikasi:</strong> {{ optional($catatan->diverifikasi_at)->format('d-m-Y H:i') ?? '-' }}</div>

        @if(is_array($catatan->foto_kegiatan) && count($catatan->foto_kegiatan))
            <div>
                <strong>Bukti Pendukung:</strong>
                <ul class="list-disc pl-5">
                    @foreach($catatan->foto_kegiatan as $file)
                        <li><a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-blue-700">{{ basename($file) }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
@endsection
