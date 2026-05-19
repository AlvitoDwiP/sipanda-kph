@extends('layouts.master')

@section('title', 'Catatan Kegiatan')
@section('page-title', 'Buat Catatan Kegiatan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Buat Catatan Kegiatan dari Tugas</h3>
        <a href="{{ route('pegawai.tugas.show', $penugasan->tugas_id) }}" class="text-sm text-slate-500 hover:text-slate-700">Kembali</a>
    </div>

    <form method="POST" action="{{ route('pegawai.tugas.catatan.store', $penugasan->id) }}" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf

        <div class="p-4 rounded border bg-slate-50 text-sm space-y-1">
            <div><strong>Tugas:</strong> {{ $penugasan->tugas->judul ?? '-' }}</div>
            <div><strong>Instruksi:</strong> {{ $penugasan->tugas->deskripsi ?? '-' }}</div>
            <div><strong>Deadline:</strong> {{ optional($penugasan->tugas->deadline)->format('d-m-Y') ?? '-' }}</div>
        </div>

        <div>
            <label class="block text-sm mb-1">Tanggal Kegiatan</label>
            <input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', now()->toDateString()) }}" class="w-full border rounded px-3 py-2" required>
            <x-input-error :messages="$errors->get('tanggal_kegiatan')" class="mt-1" />
        </div>

        <div>
            <label class="block text-sm mb-1">Deskripsi Kegiatan</label>
            <textarea name="deskripsi" rows="4" class="w-full border rounded px-3 py-2" required>{{ old('deskripsi') }}</textarea>
            <x-input-error :messages="$errors->get('deskripsi')" class="mt-1" />
        </div>

        <div>
            <label class="block text-sm mb-1">Hasil Kegiatan</label>
            <textarea name="hasil_kegiatan" rows="4" class="w-full border rounded px-3 py-2" required>{{ old('hasil_kegiatan') }}</textarea>
            <x-input-error :messages="$errors->get('hasil_kegiatan')" class="mt-1" />
        </div>

        <div>
            <label class="block text-sm mb-1">Kendala (Opsional)</label>
            <textarea name="kendala" rows="3" class="w-full border rounded px-3 py-2">{{ old('kendala') }}</textarea>
            <x-input-error :messages="$errors->get('kendala')" class="mt-1" />
        </div>

        <div>
            <label class="block text-sm mb-1">Bukti Pendukung</label>
            <input type="file" name="foto_kegiatan[]" multiple class="w-full border rounded px-3 py-2">
            <p class="text-xs text-slate-500 mt-1">Format: jpg, jpeg, png, pdf. Maks 2MB per file.</p>
            <x-input-error :messages="$errors->get('foto_kegiatan.*')" class="mt-1" />
        </div>

        <div class="pt-2">
            <button type="submit" class="px-4 py-2 rounded bg-green-800 text-white text-sm">Kirim untuk Verifikasi</button>
        </div>
    </form>
</div>
@endsection
