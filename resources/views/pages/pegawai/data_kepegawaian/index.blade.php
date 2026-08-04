@extends('layouts.master')

@section('title', 'Data Kepegawaian')

@section('content')

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif
@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

<x-ui.page-header title="Kepegawaian Saya" subtitle="Lihat dan kelola detail status kepegawaian Anda.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        @if($pegawai)
            <x-ui.button id="btnEditKepegawaian" type="button" variant="primary" size="sm" leadingIcon="edit">
                Edit Data
            </x-ui.button>
        @endif
    </x-slot>
</x-ui.page-header>

@if($pegawai)
    <div class="space-y-6">
        <!-- HEADER PROFILE CARD -->
        <x-ui.card>
            <div class="flex flex-col items-center text-center p-4">
                <div class="inline-block relative">
                    @if($pegawai->dataDiri && $pegawai->dataDiri->foto)
                        <img src="{{ asset('storage/' . $pegawai->dataDiri->foto) }}" alt="Foto Pegawai" class="w-32 h-32 object-cover rounded-full border border-ui-border shadow-ui-sm bg-ui-primary-soft/30" />
                    @else
                        <div class="w-32 h-32 rounded-full border border-ui-border bg-ui-primary-soft text-ui-primary flex items-center justify-center shadow-ui-sm">
                            <i data-lucide="user" class="w-12 h-12"></i>
                        </div>
                    @endif
                </div>
                <h3 class="text-base sm:text-lg font-bold text-ui-text-primary mt-4">{{ $pegawai->user->name ?? 'Nama Pegawai' }}</h3>
                <p class="text-xs sm:text-sm text-ui-text-secondary mt-0.5">NIP: {{ $pegawai->user->nip ?? 'NIP tidak tersedia' }}</p>
            </div>
        </x-ui.card>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- DATA AKUN USER -->
            <x-ui.card title="Akun Pengguna" icon="shield-alert">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs sm:text-sm">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] sm:text-xs text-ui-text-secondary">Nama Lengkap</span>
                        <span class="font-medium text-ui-text-primary">{{ $pegawai->user->name ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] sm:text-xs text-ui-text-secondary">NIP / ID</span>
                        <span class="font-medium text-ui-text-primary">{{ $pegawai->user->nip ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:col-span-2">
                        <span class="text-[10px] sm:text-xs text-ui-text-secondary">Status Keaktifan Akun</span>
                        <span class="mt-1">
                            @if ($pegawai->user->status_akun === 'aktif')
                                <x-ui.badge variant="success" size="sm">Aktif</x-ui.badge>
                            @else
                                <x-ui.badge variant="danger" size="sm">Nonaktif</x-ui.badge>
                            @endif
                        </span>
                    </div>
                </div>
            </x-ui.card>

            <!-- DATA KEPEGAWAIAN FORM -->
            <x-ui.card title="Detail Jabatan & Penempatan" icon="briefcase">
                <form id="formKepegawaian" method="POST" action="{{ route('pegawai.data_kepegawaian.update', $pegawai->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs sm:text-sm">
                        <!-- Unit Kerja -->
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] sm:text-xs text-ui-text-secondary">Unit Kerja</span>
                            <div class="view-mode font-medium text-ui-text-primary">
                                {{ $pegawai->unitkerja->nama_unitkerja ?? '-' }}
                            </div>
                            <div class="edit-mode hidden mt-1">
                                <x-ui.input 
                                    type="select"
                                    name="unitkerja_id"
                                    required
                                >
                                    @foreach ($unitkerjaList as $unit)
                                        <option value="{{ $unit->id }}" @selected($pegawai->unitkerja_id == $unit->id)>
                                            {{ $unit->nama_unitkerja }}
                                        </option>
                                    @endforeach
                                </x-ui.input>
                            </div>
                        </div>

                        <!-- Golongan -->
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] sm:text-xs text-ui-text-secondary">Golongan</span>
                            <div class="view-mode font-medium text-ui-text-primary">
                                {{ $pegawai->golongan->nama_golongan ?? '-' }}
                            </div>
                            <div class="edit-mode hidden mt-1">
                                <x-ui.input 
                                    type="select"
                                    name="golongan_id"
                                    required
                                >
                                    @foreach ($golonganList as $gol)
                                        <option value="{{ $gol->id }}" @selected($pegawai->golongan_id == $gol->id)>
                                            {{ $gol->nama_golongan }}
                                        </option>
                                    @endforeach
                                </x-ui.input>
                            </div>
                        </div>

                        <!-- Jabatan -->
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] sm:text-xs text-ui-text-secondary">Jabatan</span>
                            <div class="view-mode font-medium text-ui-text-primary">
                                {{ $pegawai->jabatan->nama_jabatan ?? '-' }}
                            </div>
                            <div class="edit-mode hidden mt-1">
                                <x-ui.input 
                                    type="select"
                                    name="jabatan_id"
                                    required
                                >
                                    @foreach ($jabatanList as $jab)
                                        <option value="{{ $jab->id }}" @selected($pegawai->jabatan_id == $jab->id)>
                                            {{ $jab->nama_jabatan }}
                                        </option>
                                    @endforeach
                                </x-ui.input>
                            </div>
                        </div>

                        <!-- Status Pegawai -->
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] sm:text-xs text-ui-text-secondary">Status Pegawai</span>
                            <div class="view-mode mt-1">
                                @if ($pegawai->status_pegawai === 'aktif')
                                    <x-ui.badge variant="success" size="sm">Aktif</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger" size="sm">Nonaktif</x-ui.badge>
                                @endif
                            </div>
                            <div class="edit-mode hidden mt-1">
                                <x-ui.input 
                                    type="select"
                                    name="status_pegawai"
                                    required
                                >
                                    <option value="aktif" @selected($pegawai->status_pegawai === 'aktif')>Aktif</option>
                                    <option value="nonaktif" @selected($pegawai->status_pegawai === 'nonaktif')>Nonaktif</option>
                                </x-ui.input>
                            </div>
                        </div>
                    </div>
                </form>
            </x-ui.card>

            <!-- DATA PRIBADI (DARI DATA DIRI) -->
            <div class="lg:col-span-2">
                <x-ui.card title="Data Diri & Kontak" icon="user">
                    @if($pegawai->dataDiri)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 text-xs sm:text-sm">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] sm:text-xs text-ui-text-secondary">No. Handphone</span>
                                <span class="font-medium text-ui-text-primary">{{ $pegawai->dataDiri->no_hp ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Tempat Lahir</span>
                                <span class="font-medium text-ui-text-primary">{{ $pegawai->dataDiri->tempat_lahir ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Tanggal Lahir</span>
                                <span class="font-medium text-ui-text-primary">
                                    {{ $pegawai->dataDiri->tgl_lahir ? \Carbon\Carbon::parse($pegawai->dataDiri->tgl_lahir)->locale('id_ID')->isoFormat('D MMMM YYYY') : '-' }}
                                </span>
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Jenis Kelamin</span>
                                <span class="font-medium text-ui-text-primary">
                                    {{ $pegawai->dataDiri->jenis_kelamin == 'L' ? 'Laki-laki' : ($pegawai->dataDiri->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                                </span>
                            </div>
                            <div class="flex flex-col gap-0.5 sm:col-span-2">
                                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Alamat Lengkap</span>
                                <span class="font-medium text-ui-text-primary leading-normal">{{ $pegawai->dataDiri->alamat ?? '-' }}</span>
                            </div>
                        </div>
                    @else
                        <x-ui.empty-state 
                            icon="user-x" 
                            title="Data Diri Belum Dilengkapi" 
                            description="Silakan lengkapi informasi profil Anda di menu Profil Saya." 
                        />
                    @endif
                </x-ui.card>
            </div>
        </div>
    </div>
@else
    <x-ui.card>
        <x-ui.empty-state 
            icon="users" 
            title="Pegawai Tidak Ditemukan" 
            description="Data kepegawaian Anda belum terdaftar di sistem. Hubungi administrator." 
        />
    </x-ui.card>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('btnEditKepegawaian');
        if (!btn) return;
        
        const form = document.getElementById('formKepegawaian');
        const viewEls = document.querySelectorAll('.view-mode');
        const editEls = document.querySelectorAll('.edit-mode');

        let editMode = false;

        btn.addEventListener('click', () => {
            if (!editMode) {
                // TOGGLE TO EDIT MODE
                viewEls.forEach(el => el.classList.add('hidden'));
                editEls.forEach(el => el.classList.remove('hidden'));

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
</script>
@endpush