@extends('layouts.master')

@section('title', 'Data Unit Kerja')

@section('content')

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif
@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

<x-ui.page-header title="Data Unit Kerja" subtitle="Manajemen wilayah / unit kerja operasional SIPANDA-KPH.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="primary" size="sm" leadingIcon="plus" onclick="openTambahModal()">
            Tambah Unit Kerja
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<x-ui.card>
    <x-ui.table :headers="['No', 'Nama Unit Kerja', 'Aksi']" :empty="$unitkerja->isEmpty()">
        @foreach ($unitkerja as $i => $item)
            <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                <td class="px-4 py-3 text-xs sm:text-sm text-ui-text-secondary">{{ $i + 1 }}</td>
                <td class="px-4 py-3 text-xs sm:text-sm font-semibold text-ui-text-primary">
                    {{ $item->nama_unitkerja }}
                </td>
                <td class="px-4 py-3 text-xs sm:text-sm text-right">
                    <div class="flex items-center justify-end gap-2">
                        <x-ui.button variant="ghost" size="xs" leadingIcon="edit" onclick="openEditModal({{ $item->id }}, '{{ $item->nama_unitkerja }}')">
                            Edit
                        </x-ui.button>
                        <x-ui.button variant="ghost" size="xs" leadingIcon="trash-2" class="text-ui-danger hover:bg-ui-danger-soft active:bg-ui-danger-soft" onclick="openDeleteModal({{ $item->id }}, '{{ $item->nama_unitkerja }}')">
                            Hapus
                        </x-ui.button>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-ui.table>
</x-ui.card>

{{-- MODAL TAMBAH --}}
<x-ui.modal name="tambah-unitkerja" :show="$errors->has('nama_unitkerja') && !session('edit_id')" title="Tambah Unit Kerja" maxWidth="sm">
    <form method="POST" action="{{ route('admin.unitkerja.store') }}" class="space-y-4">
        @csrf
        <x-ui.input 
            type="text"
            name="nama_unitkerja"
            label="Nama Unit Kerja"
            placeholder="Contoh: RPH, Bagian Umum"
            value="{{ old('nama_unitkerja') }}"
            :error="$errors->first('nama_unitkerja')"
            required
        />
        
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'tambah-unitkerja' }))">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="sm">
                Simpan
            </x-ui.button>
        </x-slot>
    </form>
</x-ui.modal>

{{-- MODAL EDIT --}}
<x-ui.modal name="edit-unitkerja" :show="$errors->has('nama_unitkerja') && session('edit_id')" title="Edit Unit Kerja" maxWidth="sm">
    <form method="POST" id="formEdit" class="space-y-4">
        @csrf
        @method('PUT')
        
        <x-ui.input 
            type="text"
            id="edit_nama"
            name="nama_unitkerja"
            label="Nama Unit Kerja"
            placeholder="Contoh: RPH, Bagian Umum"
            value="{{ old('nama_unitkerja') }}"
            :error="$errors->first('nama_unitkerja')"
            required
        />
        
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'edit-unitkerja' }))">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="sm">
                Perbarui
            </x-ui.button>
        </x-slot>
    </form>
</x-ui.modal>

{{-- MODAL DELETE --}}
<x-ui.modal name="delete-unitkerja" title="Konfirmasi Hapus" maxWidth="sm">
    <form method="POST" id="formDelete">
        @csrf
        @method('DELETE')
        
        <div class="flex items-start gap-3.5">
            <div class="p-2.5 rounded-ui-lg bg-ui-danger-soft text-ui-danger shrink-0 border border-ui-danger/10">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div class="min-w-0">
                <h4 class="text-xs sm:text-sm font-bold text-ui-text-primary leading-tight">Yakin ingin menghapus unit kerja?</h4>
                <p class="text-[11px] sm:text-xs text-ui-text-secondary mt-1 leading-normal">
                    Unit kerja <span id="deleteNama" class="font-semibold text-ui-text-primary"></span> akan dihapus permanen. Data pegawai dengan unit kerja ini akan terdampak.
                </p>
            </div>
        </div>
        
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'delete-unitkerja' }))">
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
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'tambah-unitkerja' }));
    }

    function openEditModal(id, nama) {
        document.getElementById('edit_nama').value = nama;
        document.getElementById('formEdit').action = `/admin/unitkerja/${id}`;
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-unitkerja' }));
    }

    function openDeleteModal(id, nama) {
        document.getElementById('deleteNama').innerText = nama;
        document.getElementById('formDelete').action = `/admin/unitkerja/${id}`;
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-unitkerja' }));
    }
</script>
@endpush
