@extends('layouts.master')

@section('title', 'Data Kepegawaian')
@section('page-title', 'Data Kepegawaian')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm">
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

            <!-- DATA KEPEGAWAIAN -->
            <div class="bg-slate-50 rounded-lg p-6">

                <div class="flex justify-between items-center mb-4 pb-2 border-b">
                    <h4 class="font-semibold text-slate-700">Data Kepegawaian</h4>

                    <button id="btnEditKepegawaian"
                        type="button"
                        class="px-4 py-2 text-sm rounded-lg text-white bg-green-800 hover:bg-green-900">
                        Edit
                    </button>
                </div>

                <form id="formKepegawaian"
                    method="POST"
                    action="{{ route('pegawai.data_kepegawaian.update', $pegawai->id) }}">

                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Unit Kerja -->
                        <div>
                            <label class="text-xs text-slate-500 uppercase mb-1 block">Unit Kerja</label>

                            <!-- READ -->
                            <p class="text-slate-800 font-medium view-mode">
                                {{ $pegawai->unitkerja->nama_unitkerja ?? '-' }}
                            </p>

                            <!-- EDIT -->
                            <select name="unitkerja_id"
                                class="edit-mode hidden w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-sm">
                                @foreach ($unitkerjaList as $unit)
                                <option value="{{ $unit->id }}"
                                    @selected($pegawai->unitkerja_id == $unit->id)>
                                    {{ $unit->nama_unitkerja }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Golongan -->
                        <div>
                            <label class="text-xs text-slate-500 uppercase mb-1 block">Golongan</label>

                            <p class="text-slate-800 font-medium view-mode">
                                {{ $pegawai->golongan->nama_golongan ?? '-' }}
                            </p>

                            <select name="golongan_id"
                                class="edit-mode hidden w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-sm">
                                @foreach ($golonganList as $gol)
                                <option value="{{ $gol->id }}"
                                    @selected($pegawai->golongan_id == $gol->id)>
                                    {{ $gol->nama_golongan }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label class="text-xs text-slate-500 uppercase mb-1 block">Jabatan</label>

                            <p class="text-slate-800 font-medium view-mode">
                                {{ $pegawai->jabatan->nama_jabatan ?? '-' }}
                            </p>

                            <select name="jabatan_id"
                                class="edit-mode hidden w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-sm">
                                @foreach ($jabatanList as $jab)
                                <option value="{{ $jab->id }}"
                                    @selected($pegawai->jabatan_id == $jab->id)>
                                    {{ $jab->nama_jabatan }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Pegawai -->
                        <div>
                            <label class="text-xs text-slate-500 uppercase mb-1 block">Status Pegawai</label>

                            <p class="view-mode">
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

                            <select name="status_pegawai"
                                class="edit-mode hidden w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-sm">
                                <option value="aktif" @selected($pegawai->status_pegawai === 'aktif')>Aktif</option>
                                <option value="nonaktif" @selected($pegawai->status_pegawai === 'nonaktif')>Nonaktif</option>
                            </select>
                        </div>

                    </div>
                </form>
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

@push('scripts')
<script>
    const btn = document.getElementById('btnEditKepegawaian');
    const form = document.getElementById('formKepegawaian');

    const viewEls = document.querySelectorAll('.view-mode');
    const editEls = document.querySelectorAll('.edit-mode');

    let editMode = false;

    btn.addEventListener('click', () => {
        if (!editMode) {
            // KE MODE EDIT
            viewEls.forEach(el => el.classList.add('hidden'));
            editEls.forEach(el => el.classList.remove('hidden'));

            btn.textContent = 'Simpan';
            btn.type = 'submit';
            editMode = true;
        } else {
            form.submit();
        }
    });
</script>
@endpush