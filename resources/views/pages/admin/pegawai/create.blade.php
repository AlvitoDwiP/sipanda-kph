@extends('layouts.master')

@section('title', 'Tambah Pegawai')

@section('content')

<x-ui.page-header title="Tambah Pegawai" subtitle="Tambahkan data kepegawaian baru ke sistem.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="ghost" size="sm" leadingIcon="arrow-left" :href="route('admin.pegawai.index')">
            Kembali
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<x-ui.card>
    <form method="POST" action="{{ route('admin.pegawai.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- User / Nama -->
            <div class="md:col-span-2">
                <x-ui.input 
                    type="select"
                    name="user_id"
                    label="Nama Pegawai (User)"
                    required
                    :error="$errors->first('user_id')"
                >
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                            {{ $user->name }} ({{ $user->username ?? $user->email }})
                        </option>
                    @endforeach
                </x-ui.input>
            </div>

            <!-- Unit Kerja -->
            <div>
                <x-ui.input 
                    type="select"
                    name="unitkerja_id"
                    label="Unit Kerja"
                    required
                    :error="$errors->first('unitkerja_id')"
                >
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach ($unitkerja as $item)
                        <option value="{{ $item->id }}" @selected(old('unitkerja_id') == $item->id)>
                            {{ $item->nama_unitkerja }}
                        </option>
                    @endforeach
                </x-ui.input>
            </div>

            <!-- Golongan -->
            <div>
                <x-ui.input 
                    type="select"
                    name="golongan_id"
                    label="Golongan"
                    required
                    :error="$errors->first('golongan_id')"
                >
                    <option value="">-- Pilih Golongan --</option>
                    @foreach ($golongan as $item)
                        <option value="{{ $item->id }}" @selected(old('golongan_id') == $item->id)>
                            {{ $item->nama_golongan }}
                        </option>
                    @endforeach
                </x-ui.input>
            </div>

            <!-- Jabatan -->
            <div>
                <x-ui.input 
                    type="select"
                    name="jabatan_id"
                    label="Jabatan"
                    required
                    :error="$errors->first('jabatan_id')"
                >
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach ($jabatan as $item)
                        <option value="{{ $item->id }}" @selected(old('jabatan_id') == $item->id)>
                            {{ $item->nama_jabatan }}
                        </option>
                    @endforeach
                </x-ui.input>
            </div>

            <!-- Status Pegawai -->
            <div>
                <x-ui.input 
                    type="select"
                    name="status_pegawai"
                    label="Status Pegawai"
                    required
                    :error="$errors->first('status_pegawai')"
                >
                    <option value="">-- Pilih Status --</option>
                    <option value="aktif" @selected(old('status_pegawai') == 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status_pegawai') == 'nonaktif')>Nonaktif</option>
                </x-ui.input>
            </div>
        </div>

        <!-- Action Buttons -->
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" :href="route('admin.pegawai.index')">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="sm" leadingIcon="save">
                Simpan
            </x-ui.button>
        </x-slot>
    </form>
</x-ui.card>

@endsection
