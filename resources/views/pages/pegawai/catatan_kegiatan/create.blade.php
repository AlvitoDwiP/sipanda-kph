@extends('layouts.master')

@section('title', 'Catatan Kegiatan')
@section('page-title', 'Tambah Catatan Kegiatan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Tambah Catatan Kegiatan</h3>
        <a href="{{ route('pegawai.catatan_kegiatan.index') }}"
            class="text-sm text-slate-500 hover:text-slate-700">
            ✕
        </a>
    </div>

    <!-- Form -->
    <form method="POST"
        action="{{ route('pegawai.catatan_kegiatan.store') }}"
        enctype="multipart/form-data"
        class="p-6 space-y-6">
        @csrf

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
                        @selected(old('periode_bulan')==$i)>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
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
                <input type="number"
                    name="periode_tahun"
                    value="{{ old('periode_tahun', date('Y')) }}"
                    required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                <x-input-error :messages="$errors->get('periode_tahun')" class="mt-1" />
            </div>

            <!-- Judul -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Judul Kegiatan
                </label>
                <input type="text"
                    name="judul"
                    value="{{ old('judul') }}"
                    required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                <x-input-error :messages="$errors->get('judul')" class="mt-1" />
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Deskripsi Kegiatan
                </label>
                <textarea name="deskripsi"
                    rows="5"
                    required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">{{ old('deskripsi') }}</textarea>
                <x-input-error :messages="$errors->get('deskripsi')" class="mt-1" />
            </div>

            <!-- Foto Kegiatan -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Foto Kegiatan
                </label>

                <input type="file"
                    name="foto_kegiatan[]"
                    multiple
                    accept="image/*"
                    id="foto_kegiatan"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">

                <p class="text-xs text-slate-500 mt-1">
                    Dapat mengunggah lebih dari 1 foto (jpg, png, max 2MB per foto)
                </p>

                <x-input-error :messages="$errors->get('foto_kegiatan')" class="mt-1" />
                <x-input-error :messages="$errors->get('foto_kegiatan.*')" class="mt-1" />

                <!-- PREVIEW -->
                <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
            </div>

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
                const div = document.createElement('div');
                div.className = 'relative group';

                div.innerHTML = `
                    <img src="${e.target.result}"
                        class="w-full h-32 object-cover rounded-lg border">
                `;

                previewContainer.appendChild(div);
            };

            reader.readAsDataURL(file);
        });
    });
</script>
@endpush