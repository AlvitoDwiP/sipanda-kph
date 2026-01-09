@extends('layouts.master')

@section('title', 'Data Kepegawaian')
@section('page-title', 'Data Kepegawaian')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Data Kepegawaian</h3>
    </div>

    <div class="p-6">
        @if($pegawai)
        <div class="space-y-8">
            <!-- Foto Profil -->
            <div class="text-center">
                <div class="inline-block">
                    @if($pegawai->dataDiri && $pegawai->dataDiri->foto)
                    <img src="{{ asset('storage/' . $pegawai->dataDiri->foto) }}" alt="Foto Pegawai" class="w-40 h-40 object-cover rounded-full border-4 border-slate-200 mx-auto" />
                    @else
                    <div class="w-40 h-40 rounded-full border-4 border-slate-200 mx-auto bg-slate-100 flex items-center justify-center">
                        <span class="text-5xl text-slate-400">👤</span>
                    </div>
                    @endif
                </div>
                <h3 class="text-xl font-bold text-slate-800 mt-4">{{ $pegawai->user->name ?? 'Nama Pegawai' }}</h3>
                <p class="text-slate-600">{{ $pegawai->user->nip ?? 'NIP tidak tersedia' }}</p>
            </div>

            <!-- Data User -->
            <div class="bg-slate-50 rounded-lg p-6">
                <h4 class="font-semibold text-slate-700 mb-4 pb-2 border-b">Data User</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Nama</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">NIP</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->user->nip ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Email</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Role</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->user->role ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Status Akun</label>
                        <p class="font-medium text-slate-800">
                            @if ($pegawai->user->status_akun === 'aktif')
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                Aktif
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full">
                                Nonaktif
                            </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Data Kepegawaian -->
            <div class="bg-slate-50 rounded-lg p-6">
                <h4 class="font-semibold text-slate-700 mb-4 pb-2 border-b">Data Kepegawaian</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-slate-500 uppercase">ID Pegawai</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->id ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Unit Kerja</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->unitkerja->nama_unitkerja ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Golongan</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->golongan->nama_golongan ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Jabatan</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Status Pegawai</label>
                        <p>
                            @if ($pegawai->status_pegawai === 'aktif')
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                Aktif
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full">
                                Nonaktif
                            </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Tanggal Dibuat</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->created_at ? \Carbon\Carbon::parse($pegawai->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') : '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Tanggal Update</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->updated_at ? \Carbon\Carbon::parse($pegawai->updated_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Data Diri -->
            <div class="bg-slate-50 rounded-lg p-6">
                <h4 class="font-semibold text-slate-700 mb-4 pb-2 border-b">Data Diri</h4>
                @if($pegawai->dataDiri)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-slate-500 uppercase">No HP</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->dataDiri->no_hp ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Alamat</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->dataDiri->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Tempat Lahir</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->dataDiri->tempat_lahir ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Tanggal Lahir</label>
                        <p class="font-medium text-slate-800">
                            {{ $pegawai->dataDiri->tgl_lahir ? \Carbon\Carbon::parse($pegawai->dataDiri->tgl_lahir)->locale('id_ID')->isoFormat('D MMMM YYYY') : '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Jenis Kelamin</label>
                        <p class="font-medium text-slate-800">
                            {{ $pegawai->dataDiri->jenis_kelamin == 'L' ? 'Laki-laki' : ($pegawai->dataDiri->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Tanggal Dibuat</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->dataDiri->created_at ? \Carbon\Carbon::parse($pegawai->dataDiri->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') : '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 uppercase">Tanggal Update</label>
                        <p class="font-medium text-slate-800">{{ $pegawai->dataDiri->updated_at ? \Carbon\Carbon::parse($pegawai->dataDiri->updated_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') : '-' }}</p>
                    </div>
                </div>
                @else
                <p class="text-slate-500 italic">Data diri belum dilengkapi</p>
                @endif
            </div>
        </div>
        @else
        <div class="text-center py-8 text-slate-400">
            Data pegawai belum tersedia
        </div>
        @endif
    </div>
</div>



@endsection