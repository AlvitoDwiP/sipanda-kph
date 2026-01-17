@extends('layouts.master')

@section('title', 'Registrasi & Verifikasi')
@section('page-title', 'Registrasi & Verifikasi')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Tambah User</h3>
        <a href="{{ route('admin.register.index') }}"
            class="text-sm text-slate-500 hover:text-slate-700">
            ✕
        </a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.register.store') }}" class="p-6 space-y-8">
        @csrf

        <div>
            <h4 class="font-semibold text-slate-700 mb-4">Data Akun</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm
                        focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- NIP -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" required pattern="\d*"
                        oninput="this.value = this.value.replace(/\D/g,'')"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm
                        focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <x-input-error :messages="$errors->get('nip')" class="mt-1" />
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm
                        focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm
                        focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                    <select name="role" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="pegawai">Pegawai</option>
                        <option value="kph">KPH</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-1" />
                </div>

                <!-- Status Akun -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status Akun</label>
                    <select name="status_akun" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                        <option value="nonaktif">Nonaktif</option>
                        <option value="aktif">Aktif</option>
                    </select>
                    <x-input-error :messages="$errors->get('status_akun')" class="mt-1" />
                </div>

            </div>
        </div>

        {{-- Divider --}}
        <div class="border-t border-slate-100"></div>

        <div>
            <h4 class="font-semibold text-slate-700 mb-4">Data Kepegawaian</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Unit Kerja -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Unit Kerja</label>
                    <select name="unitkerja_id" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                        <option value="">-- Pilih Unit Kerja --</option>
                        @foreach ($unitkerja as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_unitkerja }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Golongan -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Golongan</label>
                    <select name="golongan_id" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                        <option value="">-- Pilih Golongan --</option>
                        @foreach ($golongan as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_golongan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan</label>
                    <select name="jabatan_id" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach ($jabatan as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Pegawai -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status Pegawai</label>
                    <select name="status_pegawai" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Catatan Verifikasi (Opsional)
            </label>
            <textarea name="catatan_verifikasi" rows="3"
                class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm
                focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Catatan admin terkait verifikasi akun...">{{ old('catatan_verifikasi') }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.register.index') }}"
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