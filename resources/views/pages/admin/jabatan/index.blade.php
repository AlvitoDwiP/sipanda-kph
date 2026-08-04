@extends('layouts.master')

@section('title', 'Data Jabatan')

@section('content')

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif
@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

<x-ui.page-header title="Data Jabatan" subtitle="Manajemen jabatan struktural dan fungsional pegawai SIPANDA-KPH.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="primary" size="sm" leadingIcon="plus" onclick="openTambahModal()">
            Tambah Jabatan
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<x-ui.card>
    <x-ui.table :headers="['No', 'Nama Jabatan', 'Aksi']" :empty="$jabatan->isEmpty()">
        @foreach ($jabatan as $i => $item)
            <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                <td class="px-4 py-3 text-xs sm:text-sm text-ui-text-secondary">{{ $i + 1 }}</td>
                <td class="px-4 py-3 text-xs sm:text-sm font-semibold text-ui-text-primary">
                    {{ $item->nama_jabatan }}
                </td>
                <td class="px-4 py-3 text-xs sm:text-sm text-right">
                    <div class="flex items-center justify-end gap-2">
                        <x-ui.button variant="ghost" size="xs" leadingIcon="edit" onclick="openEditModal({{ $item->id }}, '{{ $item->nama_jabatan }}')">
                            Edit
                        </x-ui.button>
                        <x-ui.button variant="ghost" size="xs" leadingIcon="trash-2" class="text-ui-danger hover:bg-ui-danger-soft active:bg-ui-danger-soft" onclick="openDeleteModal({{ $item->id }}, '{{ $item->nama_jabatan }}')">
                            Hapus
                        </x-ui.button>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-ui.table>
</x-ui.card>

{{-- MODAL TAMBAH --}}
<x-ui.modal name="tambah-jabatan" :show="$errors->has('nama_jabatan') && !session('edit_id')" title="Tambah Jabatan" maxWidth="sm">
    <form method="POST" action="{{ route('admin.jabatan.store') }}" class="space-y-4">
        @csrf
        <x-ui.input 
            type="text"
            name="nama_jabatan"
            label="Nama Jabatan"
            placeholder="Contoh: Kepala Seksi, Staf Administrasi"
            value="{{ old('nama_jabatan') }}"
            :error="$errors->first('nama_jabatan')"
            required
        />
        
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'tambah-jabatan' }))">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="sm">
                Simpan
            </x-ui.button>
        </x-slot>
    </form>
</x-ui.modal>

{{-- MODAL EDIT --}}
<x-ui.modal name="edit-jabatan" :show="$errors->has('nama_jabatan') && session('edit_id')" title="Edit Jabatan" maxWidth="sm">
    <form method="POST" id="formEdit" class="space-y-4">
        @csrf
        @method('PUT')
        
        <x-ui.input 
            type="text"
            id="edit_nama"
            name="nama_jabatan"
            label="Nama Jabatan"
            placeholder="Contoh: Kepala Seksi, Staf Administrasi"
            value="{{ old('nama_jabatan') }}"
            :error="$errors->first('nama_jabatan')"
            required
        />
        
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'edit-jabatan' }))">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="sm">
                Perbarui
            </x-ui.button>
        </x-slot>
    </form>
</x-ui.modal>

{{-- MODAL DELETE --}}
<x-ui.modal name="delete-jabatan" title="Konfirmasi Hapus" maxWidth="sm">
    <form method="POST" id="formDelete">
        @csrf
        @method('DELETE')
        
        <div class="flex items-start gap-3.5">
            <div class="p-2.5 rounded-ui-lg bg-ui-danger-soft text-ui-danger shrink-0 border border-ui-danger/10">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div class="min-w-0">
                <h4 class="text-xs sm:text-sm font-bold text-ui-text-primary leading-tight">Yakin ingin menghapus jabatan?</h4>
                <p class="text-[11px] sm:text-xs text-ui-text-secondary mt-1 leading-normal">
                    Jabatan <span id="deleteNama" class="font-semibold text-ui-text-primary"></span> akan dihapus permanen. Data pegawai dengan jabatan ini akan terdampak.
                </p>
            </div>
        </div>
        
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'delete-jabatan' }))">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" variant="danger" size="sm">
                Ya, Hapus
            </x-ui.button>
        </x-slot>
    </form>
</x-ui.modal>

@endsection

@push('scripts')
<script>
    function openTambahModal() {
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'tambah-jabatan' }));
    }

    function openEditModal(id, nama) {
        document.getElementById('edit_nama').value = nama;
        document.getElementById('formEdit').action = `/admin/jabatan/${id}`;
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-jabatan' }));
    }

    function openDeleteModal(id, nama) {
        document.getElementById('deleteNama').innerText = nama;
        document.getElementById('formDelete').action = `/admin/jabatan/${id}`;
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-jabatan' }));
    }
</script>
@endpush
