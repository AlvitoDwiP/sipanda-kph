@extends('layouts.master')
@php
    $routePrefix = $routePrefix ?? 'admin';
@endphp

@section('title', 'Penugasan Pegawai')

@section('content')

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif
@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

<x-ui.page-header title="Penugasan Pegawai" subtitle="Pantau dan kelola penugasan kerja bagi seluruh pegawai.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="primary" size="sm" leadingIcon="plus" :href="route($routePrefix . '.penugasan.create')">
            Tambah Penugasan
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<x-ui.card>
    <x-ui.table :headers="['No', 'Tugas', 'Tanggal', 'Deadline', 'Prioritas', 'Dibuat Oleh', 'Status', 'Progres', 'Kondisi', 'Template', 'Aksi']" :empty="$tugas->isEmpty()">
        @foreach ($tugas as $i => $item)
            @php
                $statusUnik = $item->penugasan->pluck('status')->unique()->values()->all();
                $rataProgres = (int) round($item->penugasan->avg('progres_persen') ?? 0);
                $isTerlambat = $item->penugasan->contains(fn($p) => $p->is_terlambat);
            @endphp
            <tr class="hover:bg-ui-primary-soft/30 transition-colors">
                <td class="px-4 py-3 text-xs text-ui-text-secondary">{{ $i + 1 }}</td>
                <td class="px-4 py-3 text-xs font-semibold text-ui-text-primary min-w-[150px]">{{ $item->judul }}</td>
                <td class="px-4 py-3 text-xs text-ui-text-secondary">{{ optional($item->tanggal_tugas)->format('d-m-Y') ?? '-' }}</td>
                <td class="px-4 py-3 text-xs text-ui-text-secondary">{{ \Carbon\Carbon::parse($item->deadline)->format('d-m-Y') }}</td>
                <td class="px-4 py-3 text-xs">
                    @if ($item->prioritas === 'rendah')
                        <x-ui.badge variant="success" size="sm">Rendah</x-ui.badge>
                    @elseif ($item->prioritas === 'sedang')
                        <x-ui.badge variant="warning" size="sm">Sedang</x-ui.badge>
                    @elseif ($item->prioritas === 'tinggi')
                        <x-ui.badge variant="danger" size="sm">Tinggi</x-ui.badge>
                    @else
                        <x-ui.badge variant="neutral" size="sm">-</x-ui.badge>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-ui-text-primary">{{ $item->user->name ?? '-' }}</td>
                <td class="px-4 py-3 text-xs text-ui-text-secondary capitalize">
                    {{ implode(', ', $statusUnik) ?: '-' }}
                </td>
                <td class="px-4 py-3 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-ui-text-primary">{{ $rataProgres }}%</span>
                        <div class="w-12 bg-ui-border rounded-full h-1.5 overflow-hidden hidden sm:block">
                            <div class="bg-ui-primary h-1.5 rounded-full" style="width: {{ $rataProgres }}%"></div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-xs">
                    @if($isTerlambat)
                        <x-ui.badge variant="danger" size="sm">Terlambat</x-ui.badge>
                    @else
                        <span class="text-ui-muted">-</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs">
                    @if ($item->template)
                        <x-ui.button variant="outline" size="xs" leadingIcon="file-text" :href="asset('storage/' . $item->template)" target="_blank">
                            Template
                        </x-ui.button>
                    @else
                        <span class="text-ui-muted">-</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-right whitespace-nowrap">
                    <div class="flex items-center justify-end gap-1.5">
                        <x-ui.button variant="outline" size="xs" :href="route($routePrefix . '.penugasan.show', $item->id)">
                            Detail
                        </x-ui.button>
                        <x-ui.button variant="outline" size="xs" :href="route($routePrefix . '.penugasan.edit', $item->id)">
                            Edit
                        </x-ui.button>
                        <x-ui.button variant="ghost" size="xs" class="text-ui-danger hover:bg-ui-danger-soft active:bg-ui-danger-soft" onclick="openDeleteModal({{ $item->id }}, '{{ $item->judul }}')">
                            Hapus
                        </x-ui.button>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-ui.table>
</x-ui.card>

{{-- MODAL DELETE --}}
<x-ui.modal name="delete-penugasan" title="Konfirmasi Hapus" maxWidth="sm">
    <form method="POST" id="formDelete">
        @csrf
        @method('DELETE')
        
        <div class="flex items-start gap-3.5">
            <div class="p-2.5 rounded-ui-lg bg-ui-danger-soft text-ui-danger shrink-0 border border-ui-danger/10">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div class="min-w-0">
                <h4 class="text-xs sm:text-sm font-bold text-ui-text-primary leading-tight">Yakin ingin menghapus penugasan?</h4>
                <p class="text-[11px] sm:text-xs text-ui-text-secondary mt-1 leading-normal">
                    Tugas <span id="deleteNama" class="font-semibold text-ui-text-primary"></span> beserta laporan progres dari seluruh pegawai yang ditugaskan akan dihapus secara permanen.
                </p>
            </div>
        </div>
        
        <x-slot name="footer">
            <x-ui.button variant="ghost" size="sm" type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'delete-penugasan' }))">
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
    function openDeleteModal(id, nama) {
        document.getElementById('deleteNama').innerText = nama;
        document.getElementById('formDelete').action =
            "{{ route($routePrefix . '.penugasan.destroy', ':id') }}".replace(':id', id);
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-penugasan' }));
    }
</script>
@endpush
