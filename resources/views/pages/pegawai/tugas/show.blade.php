@extends('layouts.master')

@section('title', 'Detail Tugas')
@section('page-title', 'Detail Tugas Saya')

@section('content')
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
            <div class="md:col-span-2">
                <p class="text-slate-500">Instruksi</p>
                <p class="font-medium text-slate-800">{{ $tugas->deskripsi }}</p>
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
    </div>
</div>
@endsection
