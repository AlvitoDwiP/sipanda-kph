@extends('layouts.master')

@section('title', 'Tambah Akun Pengguna')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <x-ui.page-header title="Tambah Akun Pengguna" subtitle="Daftarkan pengguna baru beserta data kepegawaiannya.">
        <x-slot name="breadcrumbs">
            <x-ui.breadcrumb />
        </x-slot>
        <x-slot name="actions">
            <x-ui.button variant="ghost" size="sm" leadingIcon="arrow-left" :href="route('admin.register.index')">
                Kembali
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- FORM CARD -->
    <x-ui.card>
        <form method="POST" action="{{ route('admin.register.store') }}" class="space-y-6">
            @csrf

            <!-- SECTION: DATA AKUN -->
            <div>
                <x-ui.section-header title="Informasi Kredensial Akun" subtitle="Rincian informasi login dan hak akses pengguna." class="mb-4" />
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-ui.input 
                        type="text" 
                        name="name" 
                        label="Nama Lengkap" 
                        placeholder="Nama lengkap tanpa gelar..." 
                        value="{{ old('name') }}" 
                        required 
                        :error="$errors->first('name')"
                    />

                    <x-ui.input 
                        type="text" 
                        name="nip" 
                        label="Nomor Induk Pegawai (NIP)" 
                        placeholder="18 digit angka NIP..." 
                        value="{{ old('nip') }}" 
                        required 
                        oninput="this.value = this.value.replace(/\D/g,'')"
                        :error="$errors->first('nip')"
                    />

                    <x-ui.input 
                        type="email" 
                        name="email" 
                        label="Alamat Email Resmi" 
                        placeholder="nama@perhutani.co.id..." 
                        value="{{ old('email') }}" 
                        required 
                        :error="$errors->first('email')"
                    />

                    <x-ui.input 
                        type="password" 
                        name="password" 
                        label="Kata Sandi (Password)" 
                        placeholder="Minimal 8 karakter..." 
                        required 
                        :error="$errors->first('password')"
                    />

                    <x-ui.input 
                        type="select" 
                        name="role" 
                        label="Peran Akun (Role)" 
                        value="{{ old('role') }}" 
                        required 
                        :error="$errors->first('role')"
                    >
                        <option value="">-- Pilih Peran --</option>
                        <option value="admin">Admin</option>
                        <option value="pegawai">Pegawai</option>
                        <option value="kph">KPH</option>
                    </x-ui.input>

                    <x-ui.input 
                        type="select" 
                        name="status_akun" 
                        label="Status Akun Pengguna" 
                        value="{{ old('status_akun', 'aktif') }}" 
                        required 
                        :error="$errors->first('status_akun')"
                    >
                        <option value="nonaktif">Nonaktif</option>
                        <option value="aktif">Aktif</option>
                    </x-ui.input>
                </div>
            </div>

            <!-- DIVIDER -->
            <div class="border-t border-ui-border/50 my-6"></div>

            <!-- SECTION: DATA KEPEGABAIAN -->
            <div>
                <x-ui.section-header title="Data Kepegawaian & Instansi" subtitle="Rincian penempatan, pangkat, dan kedudukan dinas." class="mb-4" />
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-ui.input 
                        type="select" 
                        name="unitkerja_id" 
                        label="Unit Kerja / Bagian" 
                        value="{{ old('unitkerja_id') }}" 
                        required 
                        :error="$errors->first('unitkerja_id')"
                    >
                        <option value="">-- Pilih Unit Kerja --</option>
                        @foreach ($unitkerja as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_unitkerja }}</option>
                        @endforeach
                    </x-ui.input>

                    <x-ui.input 
                        type="select" 
                        name="golongan_id" 
                        label="Golongan / Pangkat" 
                        value="{{ old('golongan_id') }}" 
                        required 
                        :error="$errors->first('golongan_id')"
                    >
                        <option value="">-- Pilih Golongan --</option>
                        @foreach ($golongan as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_golongan }}</option>
                        @endforeach
                    </x-ui.input>

                    <x-ui.input 
                        type="select" 
                        name="jabatan_id" 
                        label="Jabatan Dinas" 
                        value="{{ old('jabatan_id') }}" 
                        required 
                        :error="$errors->first('jabatan_id')"
                    >
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach ($jabatan as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_jabatan }}</option>
                        @endforeach
                    </x-ui.input>

                    <x-ui.input 
                        type="select" 
                        name="status_pegawai" 
                        label="Status Kepegawaian" 
                        value="{{ old('status_pegawai', 'aktif') }}" 
                        required 
                        :error="$errors->first('status_pegawai')"
                    >
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </x-ui.input>
                </div>
            </div>

            <!-- DIVIDER -->
            <div class="border-t border-ui-border/50 my-6"></div>

            <!-- SECTION: LAINNYA -->
            <div>
                <x-ui.input 
                    type="textarea" 
                    name="catatan_verifikasi" 
                    label="Catatan Verifikasi Admin (Opsional)" 
                    placeholder="Tuliskan catatan khusus atau rujukan terkait pembuatan akun jika diperlukan..."
                    value="{{ old('catatan_verifikasi') }}" 
                    :error="$errors->first('catatan_verifikasi')"
                />
            </div>

            <!-- FOOTER ACTIONS -->
            <x-slot name="footer">
                <x-ui.button variant="ghost" size="sm" :href="route('admin.register.index')">
                    Batal
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" size="sm" leadingIcon="save">
                    Simpan Akun Pengguna
                </x-ui.button>
            </x-slot>
        </form>
    </x-ui.card>
</div>
@endsection