@extends('layouts.master')

@section('title', 'Profil Saya')

@section('content')

@php
$dataDiri = $pegawai->dataDiri;
$isEmpty = !$dataDiri;
@endphp

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif
@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

<x-ui.page-header title="Profil Saya" subtitle="Kelola informasi profil pribadi dan dokumen identitas Anda.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button id="btnAction" type="button" variant="primary" size="sm" leadingIcon="edit">
            Edit Profil
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<x-ui.card>
    <form id="profileForm"
        method="POST"
        action="{{ $isEmpty ? route('pegawai.data_diri.store') : route('pegawai.data_diri.update') }}"
        enctype="multipart/form-data"
        class="space-y-8">

        @csrf
        @if(!$isEmpty)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- FOTO & KARTU IDENTITAS -->
            <div class="flex flex-col items-center gap-6 border-r border-ui-border/50 pr-0 md:pr-8">
                <!-- FOTO -->
                <div class="flex flex-col items-center gap-3 w-full">
                    <span class="text-xs font-semibold text-ui-text-secondary self-start">Foto Profil</span>
                    <div class="relative group">
                        <img id="fotoPreview"
                            src="{{ $dataDiri?->foto ? asset('storage/'.$dataDiri->foto) : asset('assets/images/avatar.png') }}"
                            class="w-32 h-32 rounded-full object-cover border border-ui-border shadow-ui-sm bg-ui-primary-soft/30" />
                        <label for="fotoInput" id="fotoLabel" class="absolute bottom-0 right-0 p-2 rounded-full bg-ui-primary hover:bg-ui-primary-hover text-white shadow-ui-sm cursor-pointer transition-all duration-150 scale-0 shrink-0 hidden">
                            <i data-lucide="camera" class="w-4.5 h-4.5"></i>
                        </label>
                    </div>
                    <input type="file" name="foto" id="fotoInput" accept="image/*" onchange="previewImage(event)" class="hidden">
                    <span id="fotoHint" class="text-[10px] text-ui-text-secondary text-center">
                        Ukuran foto ideal 1:1, maks 2MB
                    </span>
                </div>

                <!-- KARTU IDENTITAS -->
                <div class="flex flex-col items-center gap-3 w-full">
                    <span class="text-xs font-semibold text-ui-text-secondary self-start">Kartu Identitas (KTP)</span>
                    <div class="relative group w-full">
                        <img id="kartuIdentitasPreview"
                            src="{{ $dataDiri?->kartu_identitas ? asset('storage/'.$dataDiri->kartu_identitas) : asset('images/no-image.png') }}"
                            class="w-full h-40 rounded-xl object-cover border border-ui-border shadow-ui-sm bg-ui-primary-soft/30" />
                        <label for="kartuIdentitasInput" id="kartuIdentitasLabel" class="absolute bottom-3 right-3 p-2 rounded-full bg-ui-primary hover:bg-ui-primary-hover text-white shadow-ui-sm cursor-pointer transition-all duration-150 scale-0 shrink-0 hidden">
                            <i data-lucide="upload" class="w-4.5 h-4.5"></i>
                        </label>
                    </div>
                    <input type="file" name="kartu_identitas" id="kartuIdentitasInput" accept="image/*" onchange="previewKartuIdentitas(event)" class="hidden">
                    <span id="kartuIdentitasHint" class="text-[10px] text-ui-text-secondary text-center">
                        Unggah salinan KTP atau kartu identitas resmi lainnya
                    </span>
                </div>
            </div>

            <!-- DETAIL FORM -->
            <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-5 align-start self-start">
                <div class="sm:col-span-2">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-ui-primary pb-1.5 border-b border-ui-border">
                        Informasi Kontak & Tempat Tinggal
                    </h4>
                </div>

                <!-- No HP -->
                <div>
                    <x-ui.input 
                        type="text"
                        name="no_hp"
                        id="no_hp"
                        label="No. Handphone"
                        placeholder="Masukkan nomor handphone aktif"
                        value="{{ old('no_hp', $dataDiri->no_hp ?? '') }}"
                        oninput="this.value = this.value.replace(/\D/g,'')"
                        readonly
                        required
                        :error="$errors->first('no_hp')"
                    />
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <x-ui.input 
                        type="select"
                        name="jenis_kelamin"
                        id="jenis_kelamin"
                        label="Jenis Kelamin"
                        required
                        disabled
                        :error="$errors->first('jenis_kelamin')"
                    >
                        <option value="">-- Pilih --</option>
                        <option value="L" @selected(($dataDiri->jenis_kelamin ?? '') == 'L')>Laki-laki</option>
                        <option value="P" @selected(($dataDiri->jenis_kelamin ?? '') == 'P')>Perempuan</option>
                    </x-ui.input>
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <x-ui.input 
                        type="text"
                        name="tempat_lahir"
                        id="tempat_lahir"
                        label="Tempat Lahir"
                        placeholder="Tempat lahir sesuai KTP"
                        value="{{ old('tempat_lahir', $dataDiri->tempat_lahir ?? '') }}"
                        readonly
                        required
                        :error="$errors->first('tempat_lahir')"
                    />
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <x-ui.input 
                        type="date"
                        name="tgl_lahir"
                        id="tgl_lahir"
                        label="Tanggal Lahir"
                        value="{{ old('tgl_lahir', $dataDiri->tgl_lahir ?? '') }}"
                        readonly
                        required
                        :error="$errors->first('tgl_lahir')"
                    />
                </div>

                <!-- Alamat -->
                <div class="sm:col-span-2">
                    <x-ui.input 
                        type="textarea"
                        name="alamat"
                        id="alamat"
                        label="Alamat Lengkap"
                        placeholder="Alamat domisili saat ini"
                        readonly
                        required
                        rows="3"
                        :error="$errors->first('alamat')"
                    >{{ old('alamat', $dataDiri->alamat ?? '') }}</x-ui.input>
                </div>
            </div>
        </div>
    </form>
</x-ui.card>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('btnAction');
        const form = document.getElementById('profileForm');
        
        // Target inner inputs and selects
        const textFields = form.querySelectorAll('input:not([type="file"]), textarea');
        const selects = form.querySelectorAll('select');

        const fotoInput = document.getElementById('fotoInput');
        const fotoLabel = document.getElementById('fotoLabel');
        const fotoHint = document.getElementById('fotoHint');

        const kartuIdentitasInput = document.getElementById('kartuIdentitasInput');
        const kartuIdentitasLabel = document.getElementById('kartuIdentitasLabel');
        const kartuIdentitasHint = document.getElementById('kartuIdentitasHint');

        let editMode = false;

        btn.addEventListener('click', (e) => {
            if (!editMode) {
                e.preventDefault();

                // Remove read-only & disabled
                textFields.forEach(el => el.removeAttribute('readonly'));
                selects.forEach(el => el.removeAttribute('disabled'));

                // Show camera/upload badges
                fotoLabel.classList.remove('hidden');
                fotoLabel.classList.add('scale-100');
                kartuIdentitasLabel.classList.remove('hidden');
                kartuIdentitasLabel.classList.add('scale-100');

                // Update info hints
                fotoHint.textContent = 'Klik ikon kamera untuk memilih foto baru';
                kartuIdentitasHint.textContent = 'Klik ikon unggah untuk memilih berkas KTP baru';

                // Change button state
                btn.innerHTML = `<i data-lucide="save" class="w-4 h-4 shrink-0"></i><span>Simpan Perubahan</span>`;
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
                
                editMode = true;
            } else {
                form.submit();
            }
        });
    });

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = () => {
            document.getElementById('fotoPreview').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewKartuIdentitas(event) {
        const reader = new FileReader();
        reader.onload = () => {
            document.getElementById('kartuIdentitasPreview').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush