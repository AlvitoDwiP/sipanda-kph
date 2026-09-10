@extends('layouts.master')

@section('title', 'Registrasi & Verifikasi')

@section('content')
<div x-data="{ 
    deleteId: null, 
    deleteName: '',
    deleteAction: '',
    selected: [],
    selectAll: false,
    allIds: {{ json_encode($user->pluck('id')) }},
    toggleAll() {
        if (this.selectAll) {
            this.selected = [...this.allIds];
        } else {
            this.selected = [];
        }
    },
    confirmDelete(id, name, action) {
        this.deleteId = id;
        this.deleteName = name;
        this.deleteAction = action;
        this.$dispatch('open-modal', 'delete-confirm');
    }
}" x-init="$watch('selected', value => { selectAll = value.length === allIds.length && allIds.length > 0 })" class="space-y-6">

    <!-- PAGE HEADER -->
    <x-ui.page-header title="Registrasi & Verifikasi Pengguna" subtitle="Kelola registrasi, hak akses, dan verifikasi akun pegawai.">
        <x-slot name="breadcrumbs">
            <x-ui.breadcrumb />
        </x-slot>
        <x-slot name="actions">
            <form action="{{ route('admin.register.massDestroy') }}" method="POST" class="inline" onsubmit="if(selected.length === 0) return false; return confirm('Yakin ingin menghapus ' + selected.length + ' pengguna yang dipilih?')">
                @csrf
                @method('DELETE')
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <x-ui.button type="submit" variant="danger" size="sm" leadingIcon="trash-2" x-bind:disabled="selected.length === 0" x-bind:class="selected.length === 0 ? 'opacity-50 cursor-not-allowed' : ''">
                    Hapus Terpilih <span x-show="selected.length > 0" x-text="'(' + selected.length + ')'"></span>
                </x-ui.button>
            </form>
            <x-ui.button variant="primary" size="sm" leadingIcon="user-plus" :href="route('admin.register.create')">
                Tambah Akun
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- MAIN CARD & TABLE -->
    <x-ui.card title="Daftar Registrasi Pengguna" icon="users" class="overflow-hidden">
        <x-ui.table 
            :empty="count($user) === 0"
        >
            <x-slot name="thead">
                <th class="px-4 py-3 font-semibold text-ui-text-secondary uppercase tracking-wider text-[10px] sm:text-xs text-center w-12">
                    <input type="checkbox" x-model="selectAll" @change="toggleAll" class="rounded border-gray-300 text-ui-primary shadow-sm focus:ring-ui-primary" />
                </th>
                <th class="px-4 py-3 font-semibold text-ui-text-secondary uppercase tracking-wider text-[10px] sm:text-xs text-center">No</th>
                <th class="px-4 py-3 font-semibold text-ui-text-secondary uppercase tracking-wider text-[10px] sm:text-xs">Nama Pengguna</th>
                <th class="px-4 py-3 font-semibold text-ui-text-secondary uppercase tracking-wider text-[10px] sm:text-xs">NIP</th>
                <th class="px-4 py-3 font-semibold text-ui-text-secondary uppercase tracking-wider text-[10px] sm:text-xs">Email</th>
                <th class="px-4 py-3 font-semibold text-ui-text-secondary uppercase tracking-wider text-[10px] sm:text-xs text-center">Role</th>
                <th class="px-4 py-3 font-semibold text-ui-text-secondary uppercase tracking-wider text-[10px] sm:text-xs text-center">Status Akun</th>
                <th class="px-4 py-3 font-semibold text-ui-text-secondary uppercase tracking-wider text-[10px] sm:text-xs text-right">Aksi</th>
            </x-slot>

            @foreach ($user as $i => $row)
                <tr class="border-b border-ui-border/50 hover:bg-ui-primary-soft/10" :class="{'bg-ui-primary-soft/10': selected.includes({{ $row->id }})}">
                    <td class="px-4 py-3 text-center">
                        <input type="checkbox" value="{{ $row->id }}" x-model="selected" class="rounded border-gray-300 text-ui-primary shadow-sm focus:ring-ui-primary" />
                    </td>
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
