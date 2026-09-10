@extends('layouts.master')
@php($routePrefix = $routePrefix ?? 'admin')

@section('title', 'Tambah Penugasan')

@section('content')

<x-ui.page-header title="Tambah Penugasan" subtitle="Buat penugasan baru dan tentukan pegawai pelaksana.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="ghost" size="sm" leadingIcon="arrow-left" :href="route($routePrefix . '.penugasan.index')">
            Kembali
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<x-ui.card>
    <form method="POST" action="{{ route($routePrefix . '.penugasan.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Judul Tugas -->
            <div class="md:col-span-2">
                <x-ui.input 
                    type="text"
                    name="judul"
                    label="Judul Tugas"
                    placeholder="Masukkan judul penugasan"
                    value="{{ old('judul') }}"
                    required
                    :error="$errors->first('judul')"
                />
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <x-ui.input 
                    type="textarea"
                    name="deskripsi"
                    label="Deskripsi Tugas"
                    placeholder="Berikan detail deskripsi atau instruksi pengerjaan tugas"
                    required
                    rows="4"
                    :error="$errors->first('deskripsi')"
                >{{ old('deskripsi') }}</x-ui.input>
            </div>

            <!-- Tanggal Tugas -->
            <div>
                <x-ui.input 
                    type="date"
                    name="tanggal_tugas"
                    label="Tanggal Mulai Tugas"
                    value="{{ old('tanggal_tugas') }}"
                    required
                    :error="$errors->first('tanggal_tugas')"
                />
            </div>

            <!-- Deadline -->
            <div>
                <x-ui.input 
                    type="date"
                    name="deadline"
                    label="Batas Akhir (Deadline)"
                    value="{{ old('deadline') }}"
                    required
                    :error="$errors->first('deadline')"
                />
            </div>

            <!-- Prioritas -->
            <div>
                <x-ui.input 
                    type="select"
                    name="prioritas"
                    label="Prioritas Tugas"
                    required
                    :error="$errors->first('prioritas')"
                >
                    <option value="">-- Pilih Prioritas --</option>
                    <option value="rendah" @selected(old('prioritas')=='rendah')>Rendah</option>
                    <option value="sedang" @selected(old('prioritas')=='sedang')>Sedang</option>
                    <option value="tinggi" @selected(old('prioritas')=='tinggi')>Tinggi</option>
                </x-ui.input>
            </div>

            <!-- Template Tugas -->
            <div class="md:col-span-2">
                <x-ui.input 
                    type="file"
                    name="template"
                    label="Dokumen Acuan / Template Tugas"
                    required
                    accept=".pdf,.doc,.docx"
                    :error="$errors->first('template')"
                />
            </div>

            <!-- Pegawai yang Ditugaskan -->
            <div class="md:col-span-2 space-y-3" id="pegawai-wrapper">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-semibold text-ui-text-primary">
                        Pegawai yang Ditugaskan <span class="text-ui-danger font-bold">*</span>
                    </label>
                    <x-ui.button type="button" variant="secondary" size="xs" leadingIcon="plus" onclick="addPegawaiDropdown()">
                        Tambah Pegawai
                    </x-ui.button>
                </div>

                <div class="flex gap-2">
                    <div class="flex-1">
                        <x-ui.input 
                            type="select"
                            name="pegawai_id[]"
                            required
                        >
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($pegawai as $item)
                                <option value="{{ $item->id }}">{{ $item->user->name }}</option>
                            @endforeach
                        </x-ui.input>
                    </div>
                    <!-- Placeholder button space matching width of minus button -->
                    <div class="w-10"></div>
                </div>

                @if($errors->has('pegawai_id'))
                    <p class="text-[11px] font-semibold text-ui-danger mt-1">
                        {{ $errors->first('pegawai_id') }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" :href="route($routePrefix . '.penugasan.index')">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="sm" leadingIcon="save">
                Simpan Penugasan
            </x-ui.button>
        </x-slot>
    </form>
</x-ui.card>

@endsection

@push('scripts')
<script>
    function addPegawaiDropdown() {
        const wrapper = document.getElementById('pegawai-wrapper');
        const div = document.createElement('div');
        div.classList.add('flex', 'gap-2', 'mt-2');

        div.innerHTML = `
            <div class="flex-1">
                <x-ui.input 
                    type="select"
                    name="pegawai_id[]"
                    required
                >
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach ($pegawai as $item)
                        <option value="{{ $item->id }}">{{ $item->user->name }}</option>
                    @endforeach
                </x-ui.input>
            </div>
            <x-ui.button type="button" variant="ghost" size="md" class="w-10 px-0 text-ui-danger hover:bg-ui-danger-soft shrink-0 border border-ui-border" onclick="this.parentNode.remove()">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </x-ui.button>
        `;
        wrapper.appendChild(div);
        
        // Reinitialize lucide icons for the newly added button
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>
@endpush
