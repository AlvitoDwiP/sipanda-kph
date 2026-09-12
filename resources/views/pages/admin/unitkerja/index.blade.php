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
        <x-ui.button variant="primary" size="sm" leadingIcon="plus" @click="$dispatch('open-modal', 'tambah-unitkerja')">
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
                        <x-ui.button variant="ghost" size="xs" leadingIcon="edit" @click="$dispatch('set-edit-unitkerja', { id: {{ $item->id }}, nama: '{{ $item->nama_unitkerja }}' }); $dispatch('open-modal', 'edit-unitkerja')">
                            Edit
                        </x-ui.button>
                        <x-ui.button variant="ghost" size="xs" leadingIcon="trash-2" class="text-ui-danger hover:bg-ui-danger-soft active:bg-ui-danger-soft" @click="$dispatch('set-delete-unitkerja', { id: {{ $item->id }}, nama: '{{ $item->nama_unitkerja }}' }); $dispatch('open-modal', 'delete-unitkerja')">
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
            <x-ui.button variant="ghost" size="sm" type="button" @click="$dispatch('close-modal', 'tambah-unitkerja')">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="sm" form="tambahForm">
                Simpan
            </x-ui.button>
        </x-slot>
    </form>
</x-ui.modal>
</div>

{{-- MODAL EDIT --}}
<x-ui.modal name="edit-unitkerja" :show="$errors->has('nama_unitkerja') && session('edit_id')" title="Edit Unit Kerja" maxWidth="sm">
    <form method="POST" :action="`/admin/unitkerja/${editId}`" class="space-y-4"
          x-data="{ editId: '{{ session('edit_id') }}', editNama: '{{ old('nama_unitkerja') }}' }"
          @set-edit-unitkerja.window="editId = $event.detail.id; editNama = $event.detail.nama;">
        @csrf
        @method('PUT')
        
        <x-ui.input 
            type="text"
            name="nama_unitkerja"
            label="Nama Unit Kerja"
            placeholder="Contoh: RPH, Bagian Umum"
            x-model="editNama"
            :error="$errors->first('nama_unitkerja')"
            required
        />
        
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" type="button" @click="$dispatch('close-modal', 'edit-unitkerja')">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="sm">
                Perbarui
            </x-ui.button>
        </x-slot>
    </form>
</x-ui.modal>

{{-- MODAL DELETE --}}
<div x-data="{ deleteId: '', deleteNama: '' }" @set-delete-unitkerja.window="deleteId = $event.detail.id; deleteNama = $event.detail.nama;">
<x-ui.modal name="delete-unitkerja" title="Konfirmasi Hapus" maxWidth="sm">
    <form id="deleteForm" method="POST" :action="`/admin/unitkerja/${deleteId}`">
        @csrf
        @method('DELETE')
        
        <div class="flex items-start gap-3.5">
            <div class="p-2.5 rounded-ui-lg bg-ui-danger-soft text-ui-danger shrink-0 border border-ui-danger/10">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div class="min-w-0">
                <h4 class="text-xs sm:text-sm font-bold text-ui-text-primary leading-tight">Yakin ingin menghapus unit kerja?</h4>
                <p class="text-[11px] sm:text-xs text-ui-text-secondary mt-1 leading-normal">
                    Unit kerja <span x-text="deleteNama" class="font-semibold text-ui-text-primary"></span> akan dihapus permanen. Data pegawai dengan unit kerja ini akan terdampak.
                </p>
            </div>
        </div>
        
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" type="button" @click="$dispatch('close-modal', 'delete-unitkerja')">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" variant="danger" size="sm" form="deleteForm">
                Ya, Hapus
            </x-ui.button>
        </x-slot>
    </form>
</x-ui.modal>

@endsection
