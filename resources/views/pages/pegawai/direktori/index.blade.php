@extends('layouts.master')

@section('title', 'Direktori Pegawai')

@section('content')

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif

<x-ui.page-header title="Direktori Pegawai" subtitle="Daftar kontak dan penempatan seluruh pegawai SIPANDA-KPH.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
</x-ui.page-header>

<x-ui.card>
    <x-ui.table :headers="['No', 'Nama & NIP', 'Unit Kerja', 'Golongan', 'Jabatan', 'Status Pegawai', 'Aksi']" :empty="$pegawai->isEmpty()">
        @foreach ($pegawai as $i => $item)
            <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                <td class="px-4 py-3 text-xs text-ui-text-secondary">{{ $pegawai->firstItem() + $i }}</td>
                <td class="px-4 py-3 text-xs">
                    <div class="font-semibold text-ui-text-primary">
                        {{ $item->user->name ?? '-' }}
                    </div>
                    <div class="text-[10px] text-ui-text-secondary mt-0.5">
                        NIP: {{ $item->user->nip ?? '-' }}
                    </div>
                </td>
                <td class="px-4 py-3 text-xs text-ui-text-primary">
                    {{ $item->unitkerja->nama_unitkerja ?? '-' }}
                </td>
                <td class="px-4 py-3 text-xs text-ui-text-secondary">
                    {{ $item->golongan->nama_golongan ?? '-' }}
                </td>
                <td class="px-4 py-3 text-xs text-ui-text-primary">
                    {{ $item->jabatan->nama_jabatan ?? '-' }}
                </td>
                <td class="px-4 py-3 text-xs">
                    @if ($item->status_pegawai === 'aktif')
                        <x-ui.badge variant="success" size="sm">Aktif</x-ui.badge>
                    @else
                        <x-ui.badge variant="danger" size="sm">Nonaktif</x-ui.badge>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-right">
                    <x-ui.button variant="outline" size="xs" onclick="openDetailModal({{ $item->id }})">
                        Detail
                    </x-ui.button>
                </td>
            </tr>
        @endforeach
    </x-ui.table>
</x-ui.card>

<div class="mt-4">
    {{ $pegawai->links() }}
</div>

{{-- MODAL DETAIL --}}
<x-ui.modal name="detail-pegawai" title="Detail Pegawai" maxWidth="4xl">
    <div class="space-y-6">
        <!-- FOTO PROFIL -->
        <div class="flex flex-col items-center justify-center gap-2">
            <img id="detail_foto"
                src=""
                alt="Foto Pegawai"
                class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-full border border-ui-border shadow-ui-sm bg-ui-primary-soft/30" />
            <h4 id="profile_name" class="font-bold text-sm sm:text-base text-ui-text-primary"></h4>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <!-- SECTION DATA PRIBADI -->
            <div class="col-span-1 md:col-span-2">
                <h4 class="font-bold text-xs uppercase tracking-wider text-ui-primary border-b border-ui-border pb-1.5 mt-2">
                    Informasi Pribadi & Kontak
                </h4>
            </div>

            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">No. HP</span>
                <span id="detail_no_hp" class="font-medium text-ui-text-primary">-</span>
            </div>

            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Jenis Kelamin</span>
                <span id="detail_jenis_kelamin" class="font-medium text-ui-text-primary">-</span>
            </div>

            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Tempat Lahir</span>
                <span id="detail_tempat_lahir" class="font-medium text-ui-text-primary">-</span>
            </div>

            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Tanggal Lahir</span>
                <span id="detail_tgl_lahir" class="font-medium text-ui-text-primary">-</span>
            </div>

            <div class="flex flex-col gap-0.5 md:col-span-2">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Alamat Lengkap</span>
                <span id="detail_alamat" class="font-medium text-ui-text-primary leading-normal">-</span>
            </div>

            <!-- SECTION AKUN USER -->
            <div class="col-span-1 md:col-span-2">
                <h4 class="font-bold text-xs uppercase tracking-wider text-ui-primary border-b border-ui-border pb-1.5 mt-4">
                    Informasi Akun
                </h4>
            </div>

            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Nama Lengkap</span>
                <span id="detail_nama" class="font-medium text-ui-text-primary">-</span>
            </div>

            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">NIP / Nomor Identitas</span>
                <span id="detail_nip" class="font-medium text-ui-text-primary">-</span>
            </div>

            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Email</span>
                <span id="detail_email" class="font-medium text-ui-text-primary truncate">-</span>
            </div>

            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Hak Akses (Role)</span>
                <span id="detail_role" class="font-medium text-ui-text-primary uppercase">-</span>
            </div>

            <!-- SECTION KEPEGAWAIAN -->
            <div class="col-span-1 md:col-span-2">
                <h4 class="font-bold text-xs uppercase tracking-wider text-ui-primary border-b border-ui-border pb-1.5 mt-4">
                    Status & Jabatan
                </h4>
            </div>

            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Unit Kerja</span>
                <span id="detail_unitkerja" class="font-medium text-ui-text-primary">-</span>
            </div>

            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Golongan</span>
                <span id="detail_golongan" class="font-medium text-ui-text-primary">-</span>
            </div>

            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Jabatan</span>
                <span id="detail_jabatan" class="font-medium text-ui-text-primary">-</span>
            </div>

            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Status Keaktifan</span>
                <span id="detail_status_pegawai" class="font-medium text-ui-text-primary capitalize">-</span>
            </div>
        </div>
    </div>
    
    <x-slot name="footer">
        <x-ui.button variant="ghost" size="sm" type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'detail-pegawai' }))">
            Tutup
        </x-ui.button>
    </x-slot>
</x-ui.modal>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const baseUrl = "{{ url('pegawai/direktori') }}";
        const storageUrl = "{{ asset('storage') }}";

        /* ================= OPEN DETAIL ================= */
        window.openDetailModal = function(pegawaiId) {
            setLoading();
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'detail-pegawai' }));

            fetch(`${baseUrl}/${pegawaiId}`)
                .then(res => {
                    if (!res.ok) throw new Error('Request gagal');
                    return res.json();
                })
                .then(data => {
                    /* ================= USER ================= */
                    setText('detail_nama', data.user?.name);
                    setText('profile_name', data.user?.name);
                    setText('detail_nip', data.user?.nip);
                    setText('detail_email', data.user?.email);
                    setText('detail_role', data.user?.role);

                    /* ================= KEPEGAWAIAN ================= */
                    setText('detail_unitkerja', data.unitkerja?.nama_unitkerja);
                    setText('detail_golongan', data.golongan?.nama_golongan);
                    setText('detail_jabatan', data.jabatan?.nama_jabatan);
                    setText('detail_status_pegawai', data.status_pegawai);

                    /* ================= DATA DIRI ================= */
                    const diri = data.data_diri ?? {};

                    setText('detail_no_hp', diri.no_hp);
                    setText('detail_alamat', diri.alamat);
                    setText('detail_tempat_lahir', diri.tempat_lahir);
                    setText('detail_tgl_lahir', formatDate(diri.tgl_lahir));
                    setText('detail_jenis_kelamin', formatGender(diri.jenis_kelamin));

                    /* ================= FOTO PROFIL ================= */
                    const fotoEl = document.getElementById('detail_foto');
                    if (fotoEl) {
                        fotoEl.src = diri.foto ?
                            `${storageUrl}/${diri.foto}` :
                            "{{ asset('assets/images/avatar.png') }}";
                    }
                })
                .catch(err => {
                    console.error(err);
                    setLoading('Gagal memuat data');
                });
        };

        /* ================= HELPER ================= */
        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value ?? '-';
        }

        function setLoading(text = 'Memuat...') {
            const ids = [
                'detail_nama',
                'profile_name',
                'detail_nip',
                'detail_email',
                'detail_role',
                'detail_unitkerja',
                'detail_golongan',
                'detail_jabatan',
                'detail_status_pegawai',
                'detail_no_hp',
                'detail_alamat',
                'detail_tempat_lahir',
                'detail_tgl_lahir',
                'detail_jenis_kelamin'
            ];

            ids.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = text;
            });
        }

        function formatGender(val) {
            if (val === 'L') return 'Laki-laki';
            if (val === 'P') return 'Perempuan';
            return '-';
        }

        function formatDate(val) {
            if (!val) return '-';
            return new Date(val).toLocaleDateString('id-ID');
        }
    });
</script>
@endpush