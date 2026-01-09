@extends('layouts.master')

@section('title', 'Registrasi & Verifikasi')
@section('page-title', 'Registrasi & Verifikasi')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Register & Verifikasi</h3>
        <a href="{{ route('admin.register.create') }}"
            class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
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
                        <th class="pb-3 text-left">NIP</th>
                        <th class="pb-3 text-left">Email</th>
                        <th class="pb-3 text-left">Role</th>
                        <th class="pb-3 text-left">Status</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($user as $i => $user)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4">{{ $i + 1 }}</td>
                        <td class="py-4 font-medium text-slate-800"> {{ $user->name }}</td>
                        <td class="py-4">{{ $user->nip }}</td>
                        <td class="py-4">{{ $user->email }}</td>
                        <td class="py-4">{{ $user->role }}</td>
                        <td class="py-4">
                            @if ($user->status_akun == 'aktif')
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Aktif</span>
                            @else
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="py-4 text-right">
                            <a href="{{ route('admin.register.edit', $user->id) }}"
                                class="text-slate-600 hover:text-green-600 font-medium transition">
                                Edit
                            </a>

                            <span class="mx-2 text-slate-300">|</span>

                            <button type="button"
                                onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')"
                                class="text-slate-600 hover:text-red-600 font-medium transition">
                                Hapus
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400">
                            Data log aktivitas belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>

<div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">
        <form method="POST" id="formDelete">
            @csrf
            @method('DELETE')

            <div class="px-6 py-4 border-b flex justify-between">
                <h3 class="font-semibold">Konfirmasi Hapus</h3>
                <button type="button" onclick="closeDeleteModal()">✕</button>
            </div>

            <div class="px-6 py-6 text-sm">
                Yakin ingin menghapus akun
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

@endsection

@push('scripts')
<script>
    function openDeleteModal(id, nama) {
        document.getElementById('deleteNama').innerText = nama;
        document.getElementById('formDelete').action = `/admin/register/${id}`;
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
@endpush   