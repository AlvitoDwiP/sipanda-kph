@extends('layouts.master')

@section('title', 'Penugasan')
@section('page-title', 'Tambah Penugasan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Tambah Penugasan</h3>
        <a href="{{ route('admin.penugasan.index') }}"
            class="text-sm text-slate-500 hover:text-slate-700">
            ✕
        </a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.penugasan.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Judul Tugas -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Judul Tugas
                </label>
                <input type="text" name="judul" required
                    value="{{ old('judul') }}"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                <x-input-error :messages="$errors->get('judul')" class="mt-1" />
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Deskripsi
                </label>
                <textarea name="deskripsi" rows="4" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">{{ old('deskripsi') }}</textarea>
                <x-input-error :messages="$errors->get('deskripsi')" class="mt-1" />
            </div>

            <!-- Deadline -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Deadline
                </label>
                <input type="date" name="deadline" required
                    value="{{ old('deadline') }}"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                <x-input-error :messages="$errors->get('deadline')" class="mt-1" />
            </div>

            <!-- Prioritas -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Prioritas
                </label>
                <select name="prioritas" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="">-- Pilih Prioritas --</option>
                    <option value="rendah" @selected(old('prioritas')=='rendah' )>Rendah</option>
                    <option value="sedang" @selected(old('prioritas')=='sedang' )>Sedang</option>
                    <option value="tinggi" @selected(old('prioritas')=='tinggi' )>Tinggi</option>
                </select>
                <x-input-error :messages="$errors->get('prioritas')" class="mt-1" />
            </div>

            <!-- Template Tugas -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Template Tugas <span class="text-red-600">*</span>
                </label>

                <input type="file"
                    name="template"
                    required
                    accept=".pdf,.doc,.docx"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100">

                <p class="text-xs text-slate-500 mt-1">
                    Format: PDF, DOC, DOCX (maks 2MB)
                </p>

                <x-input-error :messages="$errors->get('template')" class="mt-1" />
            </div>

            <div class="md:col-span-2" id="pegawai-wrapper">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Pegawai yang Ditugaskan
                </label>

                <div class="flex gap-2 mb-2">
                    <select name="pegawai_id[]" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach ($pegawai as $item)
                        <option value="{{ $item->id }}">{{ $item->user->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" onclick="addPegawaiDropdown()"
                        class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        +
                    </button>
                </div>

                <x-input-error :messages="$errors->get('pegawai_id')" class="mt-1" />
            </div>
        </div>

        <!-- Action -->
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.penugasan.index') }}"
                class="px-4 py-2 text-sm rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                Batal
            </a>
            <button type="submit"
                class="px-5 py-2 text-sm rounded-lg bg-green-800 text-white hover:bg-green-900">
                Simpan
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    function addPegawaiDropdown() {
        const wrapper = document.getElementById('pegawai-wrapper');
        const div = document.createElement('div');
        div.classList.add('flex', 'gap-2', 'mb-2');

        div.innerHTML = `
        <select name="pegawai_id[]" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
            <option value="">-- Pilih Pegawai --</option>
            @foreach ($pegawai as $item)
                <option value="{{ $item->id }}">{{ $item->user->name }}</option>
            @endforeach
        </select>
        <button type="button" onclick="this.parentNode.remove()" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            -
        </button>
    `;
        wrapper.appendChild(div);
    }
</script>
@endpush