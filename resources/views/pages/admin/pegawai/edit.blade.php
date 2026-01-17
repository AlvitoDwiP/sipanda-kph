@extends('layouts.master')

@section('title', 'Pegawai')
@section('page-title', 'Edit Pegawai')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Edit Pegawai</h3>
        <a href="{{ route('admin.pegawai.index') }}"
            class="text-sm text-slate-500 hover:text-slate-700">
            ✕
        </a>
    </div>

    <!-- Form -->
    <form method="POST"
          action="{{ route('admin.pegawai.update', $pegawai->id) }}"
          class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- User -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Nama Pegawai (User)
                </label>
                <select name="user_id" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                            @selected(old('user_id', $pegawai->user_id) == $user->id)>
                            {{ $user->name }} ({{ $user->username ?? $user->email }})
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
            </div>

            <!-- Unit Kerja -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Unit Kerja
                </label>
                <select name="unitkerja_id" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    @foreach ($unitkerja as $item)
                        <option value="{{ $item->id }}"
                            @selected(old('unitkerja_id', $pegawai->unitkerja_id) == $item->id)>
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
                    @foreach ($golongan as $item)
                        <option value="{{ $item->id }}"
                            @selected(old('golongan_id', $pegawai->golongan_id) == $item->id)>
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
                    @foreach ($jabatan as $item)
                        <option value="{{ $item->id }}"
                            @selected(old('jabatan_id', $pegawai->jabatan_id) == $item->id)>
                            {{ $item->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('jabatan_id')" class="mt-1" />
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Status Pegawai
                </label>
                <select name="status_pegawai" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="aktif"
                        @selected(old('status_pegawai', $pegawai->status_pegawai) == 'aktif')>
                        Aktif
                    </option>
                    <option value="nonaktif"
                        @selected(old('status_pegawai', $pegawai->status_pegawai) == 'nonaktif')>
                        Nonaktif
                    </option>
                </select>
                <x-input-error :messages="$errors->get('status_pegawai')" class="mt-1" />
            </div>

        </div>

        <!-- Action -->
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.pegawai.index') }}"
                class="px-4 py-2 text-sm rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                Batal
            </a>
            <button type="submit"
                class="px-5 py-2 text-sm rounded-lg bg-green-800 text-white hover:bg-green-900">
                Update
            </button>
        </div>

    </form>
</div>
@endsection
