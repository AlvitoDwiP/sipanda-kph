@extends('layouts.master')

@section('title', 'Catatan Kegiatan')
@section('page-title', 'Edit Catatan Kegiatan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Edit Catatan Kegiatan</h3>
        <a href="{{ route('pegawai.catatan_kegiatan.index') }}"
            class="text-sm text-slate-500 hover:text-slate-700">
            ✕
        </a>
    </div>

    <!-- Form -->
    <form method="POST"
        action="{{ route('pegawai.catatan_kegiatan.update', $catatan_kegiatan) }}"
        enctype="multipart/form-data"
        class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Periode Bulan -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Periode Bulan
                </label>
                <select name="periode_bulan" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="">-- Pilih Bulan --</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}"
                        @selected(old('periode_bulan', $catatan_kegiatan->periode_bulan) == $i)>
                        {{ $i }}
                        </option>
                        @endfor
                </select>
                <x-input-error :messages="$errors->get('periode_bulan')" class="mt-1" />
            </div>

            <!-- Periode Tahun -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Periode Tahun
                </label>
                <input type="number" name="periode_tahun" required
                    value="{{ old('periode_tahun', $catatan_kegiatan->periode_tahun) }}"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                <x-input-error :messages="$errors->get('periode_tahun')" class="mt-1" />
            </div>

            <!-- Judul -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Judul Kegiatan
                </label>
                <input type="text" name="judul" required
                    value="{{ old('judul', $catatan_kegiatan->judul) }}"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                <x-input-error :messages="$errors->get('judul')" class="mt-1" />
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Deskripsi
                </label>
                <textarea name="deskripsi" rows="5" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">{{ old('deskripsi', $catatan_kegiatan->deskripsi) }}</textarea>
                <x-input-error :messages="$errors->get('deskripsi')" class="mt-1" />
            </div>

            <!-- TAMBAH FOTO -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tambah Foto Kegiatan
                </label>

                <input type="file"
                    name="foto_kegiatan[]"
                    multiple
                    accept="image/*"
                    id="foto_kegiatan"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">

                <x-input-error :messages="$errors->get('foto_kegiatan')" class="mt-1" />
                <x-input-error :messages="$errors->get('foto_kegiatan.*')" class="mt-1" />

                <div id="preview-container"
                    class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
            </div>

            <!-- FOTO LAMA -->
            @if ($catatan_kegiatan->foto_kegiatan)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Foto Kegiatan Saat Ini
                </label>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($catatan_kegiatan->foto_kegiatan as $foto)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $foto) }}"
                            class="w-full h-32 object-cover rounded-lg border">

                        <label class="absolute top-2 right-2 bg-white rounded px-2 py-1 text-xs shadow">
                            <input type="checkbox" name="hapus_foto[]" value="{{ $foto }}">
                            Hapus
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <!-- Action -->
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">

            <!-- Batal -->
            <a href="{{ route('pegawai.catatan_kegiatan.index') }}"
                class="px-4 py-2 text-sm rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                Batal
            </a>

            <!-- Draft -->
            <button type="submit"
                name="aksi"
                value="draft"
                class="px-5 py-2 text-sm rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">
                Simpan Draft
            </button>

            <!-- Ajukan -->
            <button type="submit"
                name="aksi"
                value="ajukan"
                class="px-5 py-2 text-sm rounded-lg bg-green-800 text-white hover:bg-green-900">
                Ajukan
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    const inputFoto = document.getElementById('foto_kegiatan');
    const previewContainer = document.getElementById('preview-container');

    inputFoto.addEventListener('change', function() {
        previewContainer.innerHTML = '';

        Array.from(this.files).forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-32 object-cover rounded-lg border';
                previewContainer.appendChild(img);
            };

            reader.readAsDataURL(file);
        });
    });
</script>
@endpush