@extends('layouts.master')

@section('title', 'Data Golongan')

@section('content')

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif
@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

<x-ui.page-header title="Data Golongan" subtitle="Manajemen golongan pegawai untuk penentuan jenjang karir.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="primary" size="sm" leadingIcon="plus" onclick="openTambahModal()">
            Tambah Golongan
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<x-ui.card>
    <x-ui.table :headers="['No', 'Nama Golongan', 'Aksi']" :empty="$golongan->isEmpty()">
        @foreach ($golongan as $i => $item)
            <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                <td class="px-4 py-3 text-xs sm:text-sm text-ui-text-secondary">{{ $i + 1 }}</td>
                <td class="px-4 py-3 text-xs sm:text-sm font-semibold text-ui-text-primary">
                    {{ $item->nama_golongan }}
                </td>
                <td class="px-4 py-3 text-xs sm:text-sm text-right">
                    <div class="flex items-center justify-end gap-2">
                        <x-ui.button variant="ghost" size="xs" leadingIcon="edit" onclick="openEditModal({{ $item->id }}, '{{ $item->nama_golongan }}')">
                            Edit
                        </x-ui.button>
                        <x-ui.button variant="ghost" size="xs" leadingIcon="trash-2" class="text-ui-danger hover:bg-ui-danger-soft active:bg-ui-danger-soft" onclick="openDeleteModal({{ $item->id }}, '{{ $item->nama_golongan }}')">
                            Hapus
                        </x-ui.button>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-ui.table>
</x-ui.card>

{{-- MODAL TAMBAH --}}
<x-ui.modal name="tambah-golongan" :show="$errors->has('nama_golongan') && !session('edit_id')" title="Tambah Golongan" maxWidth="sm">
    <form method="POST" action="{{ route('admin.golongan.store') }}" class="space-y-4">
        @csrf
        <x-ui.input 
            type="text"
            name="nama_golongan"
            label="Nama Golongan"
            placeholder="Contoh: IV/a, III/b"
            value="{{ old('nama_golongan') }}"
            :error="$errors->first('nama_golongan')"
            required
        />
        
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'tambah-golongan' }))">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="sm">
                Simpan
            </x-ui.button>
        </x-slot>
    </form>
</x-ui.modal>

{{-- MODAL EDIT --}}
<x-ui.modal name="edit-golongan" :show="$errors->has('nama_golongan') && session('edit_id')" title="Edit Golongan" maxWidth="sm">
    <form method="POST" id="formEdit" class="space-y-4">
        @csrf
        @method('PUT')
        
        <x-ui.input 
            type="text"
            id="edit_nama"
            name="nama_golongan"
            label="Nama Golongan"
            placeholder="Contoh: IV/a, III/b"
            value="{{ old('nama_golongan') }}"
            :error="$errors->first('nama_golongan')"
            required
        />
        
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'edit-golongan' }))">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="sm">
                Perbarui
            </x-ui.button>
        </x-slot>
    </form>
</x-ui.modal>

{{-- MODAL DELETE --}}
<x-ui.modal name="delete-golongan" title="Konfirmasi Hapus" maxWidth="sm">
    <form method="POST" id="formDelete">
        @csrf
        @method('DELETE')
        
        <div class="flex items-start gap-3.5">
            <div class="p-2.5 rounded-ui-lg bg-ui-danger-soft text-ui-danger shrink-0 border border-ui-danger/10">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div class="min-w-0">
                <h4 class="text-xs sm:text-sm font-bold text-ui-text-primary leading-tight">Yakin ingin menghapus golongan?</h4>
                <p class="text-[11px] sm:text-xs text-ui-text-secondary mt-1 leading-normal">
                    Golongan <span id="deleteNama" class="font-semibold text-ui-text-primary"></span> akan dihapus permanen. Data pegawai dengan golongan ini akan terdampak.
                </p>
            </div>
        </div>
        
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'delete-golongan' }))">
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
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'tambah-golongan' }));
    }

    function openEditModal(id, nama) {
        document.getElementById('edit_nama').value = nama;
        document.getElementById('formEdit').action = `/admin/golongan/${id}`;
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-golongan' }));
    }

    function openDeleteModal(id, nama) {
        document.getElementById('deleteNama').innerText = nama;
        document.getElementById('formDelete').action = `/admin/golongan/${id}`;
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-golongan' }));
    }
</script>
@endpush
