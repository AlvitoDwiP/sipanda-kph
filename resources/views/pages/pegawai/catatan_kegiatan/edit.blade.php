@extends('layouts.master')

@section('title', 'Catatan Kegiatan')
@section('page-title', 'Edit Catatan Kegiatan Revisi')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Perbaiki Catatan Kegiatan</h3>
        <a href="{{ route('pegawai.catatan_kegiatan.show', $catatan_kegiatan->id) }}" class="text-sm text-slate-500 hover:text-slate-700">Kembali</a>
    </div>

    <form method="POST" action="{{ route('pegawai.catatan_kegiatan.update', $catatan_kegiatan) }}" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="p-4 rounded border bg-amber-50 text-sm text-amber-800">
            Catatan verifikasi: {{ $catatan_kegiatan->catatan_verifikasi ?? '-' }}
        </div>

        <div>
            <label class="block text-sm mb-1">Tanggal Kegiatan</label>
            <input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', optional($catatan_kegiatan->tanggal_kegiatan)->toDateString()) }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm mb-1">Deskripsi Kegiatan</label>
            <textarea name="deskripsi" rows="4" class="w-full border rounded px-3 py-2" required>{{ old('deskripsi', $catatan_kegiatan->deskripsi) }}</textarea>
        </div>

        <div>
            <label class="block text-sm mb-1">Hasil Kegiatan</label>
            <textarea name="hasil_kegiatan" rows="4" class="w-full border rounded px-3 py-2" required>{{ old('hasil_kegiatan', $catatan_kegiatan->hasil_kegiatan) }}</textarea>
        </div>

        <div>
            <label class="block text-sm mb-1">Kendala</label>
            <textarea name="kendala" rows="3" class="w-full border rounded px-3 py-2">{{ old('kendala', $catatan_kegiatan->kendala) }}</textarea>
        </div>

        @if ($catatan_kegiatan->foto_kegiatan)
            <div>
                <label class="block text-sm mb-2">File Saat Ini</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($catatan_kegiatan->foto_kegiatan as $foto)
                        <label class="border rounded p-2 text-xs">
                            <a href="{{ asset('storage/' . $foto) }}" target="_blank" class="text-blue-700">Lihat File</a>
                            <div class="mt-1"><input type="checkbox" name="hapus_foto[]" value="{{ $foto }}"> Hapus</div>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <div>
            <label class="block text-sm mb-1">Tambah File Baru</label>
            <input type="file" name="foto_kegiatan[]" multiple class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <button type="submit" class="px-4 py-2 rounded bg-green-800 text-white text-sm">Kirim Ulang Verifikasi</button>
        </div>
    </form>
</div>
@endsection
