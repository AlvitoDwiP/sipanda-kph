@extends('layouts.master')

@section('title', 'Data Pegawai')
@section('page-title', 'Data Pegawai')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Data Pegawai</h3>
        <a href="{{ route('admin.pegawai.create') }}"
            class="px-4 py-2 text-sm text-white bg-green-800 hover:bg-green-900 rounded-lg transition">
            Tambah Data
        </a>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 uppercase text-xs">
                        <th class="pb-3 text-left">No</th>
                        <th class="pb-3 text-left">Nama</th>
                        <th class="pb-3 text-left">Unit Kerja</th>
                        <th class="pb-3 text-left">Golongan</th>
                        <th class="pb-3 text-left">Jabatan</th>
                        <th class="pb-3 text-left">Status Pegawai</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($pegawai as $i => $item)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="py-4">{{ $i + 1 }}</td>

                        <td class="py-4">
                            <div class="font-medium text-slate-800">
                                {{ $item->user->name ?? '-' }}
                            </div>
                            <div class="text-xs text-slate-500">
                                NIP: {{ $item->user->nip ?? '-' }}
                            </div>
                        </td>

                        <td class="py-4">
                            {{ $item->unitkerja->nama_unitkerja ?? '-' }}
                        </td>

                        <td class="py-4">
                            {{ $item->golongan->nama_golongan ?? '-' }}
                        </td>

                        <td class="py-4">
                            {{ $item->jabatan->nama_jabatan ?? '-' }}
                        </td>

                        <td class="py-4">
                            @if ($item->status_pegawai === 'aktif')
                            <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                Aktif
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full">
                                Nonaktif
                            </span>
                            @endif
                        </td>

                        <td class="py-4 text-right whitespace-nowrap">
                            <button type="button"
                                onclick="openDetailModal({{ $item->id }})"
                                class="text-slate-600 hover:text-blue-700 font-medium transition">
                                Detail
                            </button>

                            <span class="mx-2 text-slate-300">|</span>

                            <a href="{{ route('admin.pegawai.edit', $item->id) }}"
                                class="text-slate-600 hover:text-green-800 font-medium transition">
                                Edit
                            </a>

                            <span class="mx-2 text-slate-300">|</span>

                            <button type="button"
                                onclick="openDeleteModal({{ $item->id }}, '{{ $item->user->name ?? 'Pegawai ini' }}')"
                                class="text-slate-600 hover:text-red-600 font-medium transition">
                                Hapus
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            Data pegawai belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DELETE -->
<div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">
        <form method="POST" id="formDelete">
            @csrf
            @method('DELETE')

            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="font-semibold">Konfirmasi Hapus</h3>
                <button type="button" onclick="closeDeleteModal()">✕</button>
            </div>

            <div class="px-6 py-6 text-sm">
                Yakin ingin menghapus data pegawai
                <strong id="deleteNama"></strong>?
            </div>

            <div class="px-6 py-4 border-t flex justify-end gap-3">
                <button type="button"
                    onclick="closeDeleteModal()"
                    class="px-4 py-2 border rounded-lg">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DETAIL -->
<div id="modalDetail"
    class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-500 bg-opacity-50">

    <div class="bg-white rounded-xl shadow-xl w-11/12 max-w-5xl max-h-[95vh] flex flex-col overflow-y-auto">

        <!-- HEADER -->
        <div class="relative px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 text-center">
                Detail Pegawai
            </h3>

            <button type="button"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
                onclick="closeDetailModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- BODY -->
        <div class="p-6">

            <!-- FOTO PROFIL -->
            <div class="flex justify-center mb-6">
                <img id="detail_foto"
                    src=""
                    alt="Foto Pegawai"
                    class="w-40 h-40 object-cover rounded-full border-2 border-slate-200" />
            </div>

            <!-- GRID DATA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-slate-700">

                <!-- DATA DIRI -->
                <div class="col-span-2 mt-4">
                    <h4 class="font-semibold text-slate-700 mb-3 pb-2 border-b">
                        Data Diri
                    </h4>
                </div>

                <div>
                    <strong>No HP:</strong>
                    <p id="detail_no_hp" class="mt-1 text-slate-800">-</p>
                </div>

                <div>
                    <strong>Jenis Kelamin:</strong>
                    <p id="detail_jenis_kelamin" class="mt-1 text-slate-800">-</p>
                </div>

                <div>
                    <strong>Tempat Lahir:</strong>
                    <p id="detail_tempat_lahir" class="mt-1 text-slate-800">-</p>
                </div>

                <div>
                    <strong>Tanggal Lahir:</strong>
                    <p id="detail_tgl_lahir" class="mt-1 text-slate-800">-</p>
                </div>

                <!-- ALAMAT -->
                <div>
                    <strong>Alamat:</strong>
                    <p id="detail_alamat" class="mt-1 text-slate-800">-</p>
                </div>

                <!-- KARTU IDENTITAS -->
                <div>
                    <strong>Kartu Identitas:</strong>
                    <p id="detail_kartu_identitas" class="mt-1"></p>
                </div>

                <!-- DATA USER -->
                <div class="col-span-2">
                    <h4 class="font-semibold text-slate-700 mb-3 pb-2 border-b">
                        Data User
                    </h4>
                </div>

                <div>
                    <strong>Nama:</strong>
                    <p id="detail_nama" class="mt-1 text-slate-800">-</p>
                </div>

                <div>
                    <strong>NIP:</strong>
                    <p id="detail_nip" class="mt-1 text-slate-800">-</p>
                </div>

                <div>
                    <strong>Email:</strong>
                    <p id="detail_email" class="mt-1 text-slate-800">-</p>
                </div>

                <div>
                    <strong>Role:</strong>
                    <p id="detail_role" class="mt-1 text-slate-800">-</p>
                </div>

                <!-- DATA KEPEGAWAIAN -->
                <div class="col-span-2 mt-4">
                    <h4 class="font-semibold text-slate-700 mb-3 pb-2 border-b">
                        Data Kepegawaian
                    </h4>
                </div>

                <div>
                    <strong>Unit Kerja:</strong>
                    <p id="detail_unitkerja" class="mt-1 text-slate-800">-</p>
                </div>

                <div>
                    <strong>Golongan:</strong>
                    <p id="detail_golongan" class="mt-1 text-slate-800">-</p>
                </div>

                <div>
                    <strong>Jabatan:</strong>
                    <p id="detail_jabatan" class="mt-1 text-slate-800">-</p>
                </div>

                <div>
                    <strong>Status Pegawai:</strong>
                    <p id="detail_status_pegawai" class="mt-1 text-slate-800">-</p>
                </div>

            </div>
        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
            <button type="button"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition"
                onclick="closeDetailModal()">
                Tutup
            </button>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    function openDeleteModal(id, nama) {
        document.getElementById('deleteNama').innerText = nama;
        document.getElementById('formDelete').action = `/admin/pegawai/${id}`;
        modalToggle('modalDelete', true);
    }

    function closeDeleteModal() {
        modalToggle('modalDelete', false);
    }

    function modalToggle(id, show) {
        const modal = document.getElementById(id);
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const modal = document.getElementById('modalDetail');
        const baseUrl = "{{ url('/admin/pegawai') }}";
        const storageUrl = "{{ asset('storage') }}";

        /* ================= OPEN DETAIL ================= */
        window.openDetailModal = function(pegawaiId) {
            if (!modal) return;

            modal.classList.remove('hidden');
            setLoading();

            fetch(`${baseUrl}/${pegawaiId}/detail`)
                .then(res => {
                    if (!res.ok) throw new Error('Request gagal');
                    return res.json();
                })
                .then(data => {

                    /* ================= USER ================= */
                    setText('detail_nama', data.user?.name);
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
                            "{{ asset('images/avatar.png') }}";
                    }

                    /* ================= KARTU IDENTITAS (DOWNLOAD ONLY) ================= */
                    const kartuWrapper = document.getElementById('detail_kartu_identitas');

                    if (kartuWrapper) {
                        if (diri.kartu_identitas) {
                            kartuWrapper.innerHTML = `
                            <a href="${storageUrl}/${diri.kartu_identitas}"
                               download
                               class="inline-flex items-center gap-2 px-3 py-2 text-sm
                                      bg-green-800 text-white rounded-md hover:bg-green-900 transition">
                                Download Kartu Identitas
                            </a>
                        `;
                        } else {
                            kartuWrapper.textContent = '-';
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    setLoading('Gagal memuat data');
                });
        };

        /* ================= CLOSE MODAL ================= */
        window.closeDetailModal = function() {
            modal?.classList.add('hidden');
        };

        modal?.addEventListener('click', function(e) {
            if (e.target === modal) closeDetailModal();
        });

        /* ================= HELPER ================= */
        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value ?? '-';
        }

        function setLoading(text = 'Memuat...') {
            const ids = [
                'detail_nama',
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
                'detail_jenis_kelamin',
                'detail_kartu_identitas'
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