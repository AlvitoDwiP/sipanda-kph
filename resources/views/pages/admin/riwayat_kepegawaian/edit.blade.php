@extends('layouts.master')

@section('title', 'Riwayat Kepegawaian')
@section('page-title', 'Edit Riwayat Kepegawaian')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Edit Riwayat Kepegawaian</h3>
        <a href="{{ route('admin.riwayat_kepegawaian.index') }}"
            class="text-sm text-slate-500 hover:text-slate-700">
            ✕
        </a>
    </div>

    <!-- Form -->
    <form method="POST"
        action="{{ route('admin.riwayat_kepegawaian.update', $riwayat->id) }}"
        class="p-6 space-y-6">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Pegawai (Readonly) -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Pegawai
                </label>
                <input type="text" readonly
                    value="{{ $riwayat->user->name }} ({{ $riwayat->user->nip ?? $riwayat->user->email }})"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-100 text-sm">

            </div>

            <!-- Unit Kerja -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Unit Kerja
                </label>
                <select name="unitkerja_id" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach ($unitkerja as $item)
                    <option value="{{ $item->id }}"
                        @selected(old('unitkerja_id', $riwayat->unitkerja_id) == $item->id)>
                        {{ $item->nama_unitkerja }}
                    </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('unitkerja_id')" class="mt-1" />
            </div>

            <!-- Golongan -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Golongan
                </label>
                <select name="golongan_id" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="">-- Pilih Golongan --</option>
                    @foreach ($golongan as $item)
                    <option value="{{ $item->id }}"
                        @selected(old('golongan_id', $riwayat->golongan_id) == $item->id)>
                        {{ $item->nama_golongan }}
                    </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('golongan_id')" class="mt-1" />
            </div>

            <!-- Jabatan -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Jabatan
                </label>
                <select name="jabatan_id" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach ($jabatan as $item)
                    <option value="{{ $item->id }}"
                        @selected(old('jabatan_id', $riwayat->jabatan_id) == $item->id)>
                        {{ $item->nama_jabatan }}
                    </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('jabatan_id')" class="mt-1" />
            </div>

            <!-- Tanggal Mulai -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tanggal Mulai
                </label>
                <input type="date" name="tgl_mulai" required
                    value="{{ old('tgl_mulai', $riwayat->tgl_mulai) }}"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                <x-input-error :messages="$errors->get('tgl_mulai')" class="mt-1" />
            </div>

        </div>

        <!-- Action -->
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.riwayat_kepegawaian.index') }}"
                class="px-4 py-2 text-sm rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                Batal
            </a>
            <button type="submit"
                class="px-5 py-2 text-sm rounded-lg bg-green-600 text-white hover:bg-green-700">
                Update
            </button>
        </div>

    </form>
</div>
@endsection