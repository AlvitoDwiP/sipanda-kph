@extends('layouts.master')

@section('title', 'Data Diri')
@section('page-title', 'Profil Saya')

@section('content')

@php
$dataDiri = $pegawai->dataDiri;
$isEmpty = !$dataDiri;
@endphp

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Profil Saya</h3>

        <button id="btnAction"
            type="button"
            class="px-4 py-2 text-sm rounded-lg text-white bg-green-600 hover:bg-green-700">
            Edit
        </button>

    </div>

    <!-- FORM -->
    <form id="profileForm"
        method="POST"
        action="{{ $isEmpty ? route('pegawai.data_diri.store') : route('pegawai.data_diri.update') }}"
        enctype="multipart/form-data"
        class="p-6 border border-slate-200 rounded-lg bg-white">

        @csrf
        @if(!$isEmpty)
        @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- FOTO -->
            <div class="flex flex-col items-center gap-4">
                <img id="fotoPreview"
                    src="{{ $dataDiri?->foto ? asset('storage/'.$dataDiri->foto) : asset('images/avatar.png') }}"
                    class="w-40 h-40 rounded-full object-cover border border-slate-300">

                <input type="file" name="foto" id="fotoInput" accept="image/*"
                    onchange="previewImage(event)"
                    class="hidden">

                <span id="fotoHint" class="text-sm text-slate-400">
                    Klik Edit untuk mengubah foto
                </span>
            </div>

            <!-- FORM -->
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- No HP -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">No HP</label>
                    <input type="text" name="no_hp"
                        oninput="this.value = this.value.replace(/\D/g,'')"
                        value="{{ old('no_hp', $dataDiri->no_hp ?? '') }}"
                        readonly
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-slate-50 text-sm">
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir"
                        value="{{ old('tempat_lahir', $dataDiri->tempat_lahir ?? '') }}"
                        readonly
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-slate-50 text-sm">
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir"
                        value="{{ old('tgl_lahir', $dataDiri->tgl_lahir ?? '') }}"
                        readonly
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-slate-50 text-sm">
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" disabled
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-slate-50 text-sm">
                        <option value="">-- Pilih --</option>
                        <option value="L" @selected(($dataDiri->jenis_kelamin ?? '') == 'L')>Laki-laki</option>
                        <option value="P" @selected(($dataDiri->jenis_kelamin ?? '') == 'P')>Perempuan</option>
                    </select>
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="3" readonly
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-slate-50 text-sm resize-none">{{ old('alamat', $dataDiri->alamat ?? '') }}</textarea>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    const btn = document.getElementById('btnAction');
    const form = document.getElementById('profileForm');
    const inputs = document.querySelectorAll('input, textarea');
    const selects = document.querySelectorAll('select');
    const fotoInput = document.getElementById('fotoInput');
    const fotoHint = document.getElementById('fotoHint');

    let editMode = false; // SELALU mulai dari view mode

    btn.addEventListener('click', () => {
        if (!editMode) {
            // MASUK MODE EDIT
            inputs.forEach(el => el.removeAttribute('readonly'));
            selects.forEach(el => el.removeAttribute('disabled'));

            fotoInput.classList.remove('hidden');
            fotoHint.classList.add('hidden');

            inputs.forEach(el => {
                el.classList.remove('bg-slate-50');
                el.classList.add('bg-white');
            });

            btn.textContent = 'Simpan';
            btn.type = 'submit';

            editMode = true;
        } else {
            // SUBMIT FORM
            form.submit();
        }
    });

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = () => {
            document.getElementById('fotoPreview').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush