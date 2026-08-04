@extends('layouts.master')

@section('title', 'Registrasi & Verifikasi')

@section('content')
<div x-data="{ 
    deleteId: null, 
    deleteName: '',
    deleteAction: '',
    confirmDelete(id, name, action) {
        this.deleteId = id;
        this.deleteName = name;
        this.deleteAction = action;
        this.$dispatch('open-modal', 'delete-confirm');
    }
}" class="space-y-6">

    <!-- PAGE HEADER -->
    <x-ui.page-header title="Registrasi & Verifikasi Pengguna" subtitle="Kelola registrasi, hak akses, dan verifikasi akun pegawai.">
        <x-slot name="breadcrumbs">
            <x-ui.breadcrumb />
        </x-slot>
        <x-slot name="actions">
            <x-ui.button variant="primary" size="sm" leadingIcon="user-plus" :href="route('admin.register.create')">
                Tambah Akun
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- MAIN CARD & TABLE -->
    <x-ui.card title="Daftar Registrasi Pengguna" icon="users" class="overflow-hidden">
        <x-ui.table 
            :headers="['No', 'Nama Pengguna', 'NIP', 'Email', 'Role', 'Status Akun', 'Aksi']"
            :empty="count($user) === 0"
        >
            @foreach ($user as $i => $row)
                <tr class="border-b border-ui-border/50 hover:bg-ui-primary-soft/10">
                    <td class="px-4 py-3 text-ui-text-secondary text-center">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-semibold text-ui-text-primary">{{ $row->name }}</td>
                    <td class="px-4 py-3 text-ui-text-primary">{{ $row->nip ?? '-' }}</td>
                    <td class="px-4 py-3 text-ui-text-secondary select-all">{{ $row->email }}</td>
                    <td class="px-4 py-3 text-center">
                        <x-ui.badge variant="neutral" styleType="outline">{{ $row->role }}</x-ui.badge>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if ($row->status_akun === 'aktif')
                            <x-ui.badge variant="success" styleType="soft">Aktif</x-ui.badge>
                        @else
                            <x-ui.badge variant="danger" styleType="soft">Non-Aktif</x-ui.badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <x-ui.button variant="ghost" size="xs" leadingIcon="edit-2" :href="route('admin.register.edit', $row->id)">
                                Edit
                            </x-ui.button>
                            <x-ui.button 
                                type="button"
                                variant="ghost" 
                                size="xs" 
                                leadingIcon="trash-2"
                                class="text-ui-danger hover:text-red-700 hover:bg-red-50"
                                @click="confirmDelete({{ $row->id }}, '{{ addslashes($row->name) }}', '{{ route('admin.register.destroy', $row->id) }}')"
                            >
                                Hapus
                            </x-ui.button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

    <!-- DELETE CONFIRMATION MODAL -->
    <x-ui.modal name="delete-confirm" title="Konfirmasi Hapus Akun" maxWidth="sm">
        <div class="flex items-start gap-3.5">
            <div class="p-2.5 rounded-ui-lg bg-ui-danger-soft text-ui-danger shrink-0 border border-ui-danger/10">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div class="min-w-0">
                <h4 class="text-xs sm:text-sm font-bold text-ui-text-primary leading-tight">Yakin ingin menghapus?</h4>
                <p class="text-[11px] sm:text-xs text-ui-text-secondary mt-1 leading-normal">
                    Akun pengguna <span class="font-bold text-ui-text-primary" x-text="deleteName"></span> akan dihapus permanen dari sistem. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
        </div>
        
        <x-slot name="footer">
            <form :action="deleteAction" method="POST" class="inline flex gap-2">
                @csrf
                @method('DELETE')
                <x-ui.button type="button" variant="ghost" size="sm" @click="$dispatch('close-modal', 'delete-confirm')">
                    Batal
                </x-ui.button>
                <x-ui.button type="submit" variant="danger" size="sm">
                    Hapus
                </x-ui.button>
            </form>
        </x-slot>
    </x-ui.modal>

</div>
@endsection
