@extends('layouts.master')

@section('title', 'Registrasi & Verifikasi')
@section('page-title', 'Registrasi & Verifikasi')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Edit User</h3>
        <a href="{{ route('admin.register.index') }}"
            class="text-sm text-slate-500 hover:text-slate-700">✕</a>
    </div>

    <!-- Form -->
    <form method="POST"
        action="{{ route('admin.register.update', $user->id) }}"
        class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- ================= USER ================= -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Nama Lengkap
                </label>
                <input type="text" name="name"
                    value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    NIP
                </label>
                <input type="text" name="nip"
                    value="{{ old('nip', $user->nip) }}" required
                    pattern="\d*"
                    oninput="this.value = this.value.replace(/\D/g,'')"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Email
                </label>
                <input type="email" name="email"
                    value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Password (kosongkan jika tidak diubah)
                </label>
                <input type="password" name="password"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Role
                </label>
                <select name="role" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                    <option value="pegawai" @selected(old('role', $user->role) === 'pegawai')>Pegawai</option>
                    <option value="kph" @selected(old('role', $user->role) === 'kph')>KPH</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Status Akun
                </label>
                <select name="status_akun" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="aktif" @selected($user->status_akun === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected($user->status_akun === 'nonaktif')>Nonaktif</option>
                </select>
            </div>

        </div>

        <!-- SEPARATOR (SAMA DENGAN CREATE) -->
        <hr class="my-6">

        <h4 class="font-semibold text-slate-700">
            Data Kepegawaian
        </h4>

        <!-- ================= PEGAWAI ================= -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Unit Kerja
                </label>
                <select name="unitkerja_id" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach ($unitkerja as $item)
                    <option value="{{ $item->id }}"
                        @selected(optional($pegawai)->unitkerja_id == $item->id)>
                        {{ $item->nama_unitkerja }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Golongan
                </label>
                <select name="golongan_id" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="">-- Pilih Golongan --</option>
                    @foreach ($golongan as $item)
                    <option value="{{ $item->id }}"
                        @selected(optional($pegawai)->golongan_id == $item->id)>
                        {{ $item->nama_golongan }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Jabatan
                </label>
                <select name="jabatan_id" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach ($jabatan as $item)
                    <option value="{{ $item->id }}"
                        @selected(optional($pegawai)->jabatan_id == $item->id)>
                        {{ $item->nama_jabatan }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Status Pegawai
                </label>
                <select name="status_pegawai" required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                    <option value="aktif"
                        @selected(optional($pegawai)->status_pegawai === 'aktif')>
                        Aktif
                    </option>
                    <option value="nonaktif"
                        @selected(optional($pegawai)->status_pegawai === 'nonaktif')>
                        Nonaktif
                    </option>
                </select>
            </div>

        </div>

        <!-- Catatan -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Catatan Verifikasi (Opsional)
            </label>
            <textarea name="catatan_verifikasi" rows="3"
                class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">{{ old('catatan_verifikasi', $user->catatan_verifikasi) }}</textarea>
        </div>

        <!-- Action -->
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.register.index') }}"
                class="px-4 py-2 text-sm rounded-lg border border-slate-300">
                Batal
            </a>
            <button type="submit"
                class="px-5 py-2 text-sm rounded-lg bg-green-800 hover:bg-green-900 text-white">
                Update
            </button>
        </div>

    </form>
</div>

@endsection