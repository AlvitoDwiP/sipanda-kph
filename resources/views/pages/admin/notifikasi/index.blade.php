@extends('layouts.master')

@section('title', 'Data Notifikasi')
@section('page-title', 'Data Notifikasi')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100">
        <h3 class="font-bold text-slate-800">Data Notifikasi (2 Hari Terakhir)</h3>
    </div>

    <!-- Content -->
    <div class="p-6 space-y-4">

        @php
            $notifications = collect();

            foreach ($userBaru as $user) {
                $notifications->push([
                    'id'          => $user->id,
                    'judul'       => $user->name ?? 'User Baru',
                    'deskripsi'   => 'Akun user baru menunggu aktivasi',
                    'tipe'        => 'User Baru',
                    'created_at'  => $user->created_at,
                    'type'        => 'user',
                    'status'      => $user->status_akun,
                    // arahkan ke halaman manajemen user admin
                    'link'        => route('admin.register.index'),
                ]);
            }

            foreach ($perubahanDataDiri as $dataDiri) {
                $notifications->push([
                    'id'          => $dataDiri->id,
                    'judul'       => 'Perubahan Data Diri',
                    'deskripsi'   => 'Pegawai melakukan perubahan data diri',
                    'tipe'        => 'Data Diri',
                    'created_at'  => $dataDiri->updated_at,
                    'type'        => 'data_diri',
                    'status'      => null,
                    // arahkan ke halaman verifikasi / data diri
                    'link'        => route('admin.riwayat_kepegawaian.index'),
                ]);
            }

            $notifications = $notifications->sortByDesc('created_at')->values();
        @endphp

        @forelse($notifications as $notification)
        <div class="flex items-start gap-4 p-4 border border-slate-100 rounded-lg hover:bg-slate-50 transition">

            <!-- Icon -->
            <div class="flex-shrink-0">
                @if($notification['type'] === 'tugas')
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12
                   a2 2 0 002 2h10
                   a2 2 0 002-2V7
                   a2 2 0 00-2-2h-2
                   M9 5a2 2 0 002 2h2
                   a2 2 0 002-2
                   M9 5a2 2 0 012-2h2
                   a2 2 0 012 2" />
                    </svg>
                </div>
                @else
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6
                   M7 4h5.586
                   a1 1 0 01.707.293
                   l4.414 4.414
                   a1 1 0 01.293.707V20
                   a2 2 0 01-2 2H7
                   a2 2 0 01-2-2V6
                   a2 2 0 012-2z" />
                    </svg>
                </div>
                @endif

            </div>

            <!-- Content -->
            <div class="flex-1">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold text-slate-800">
                            {{ $notification['judul'] }}
                        </h4>
                        <p class="text-sm text-slate-600 mt-1">
                            {{ $notification['deskripsi'] }}
                        </p>
                    </div>

                    <span class="text-xs text-slate-500 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                    </span>
                </div>

                <div class="mt-3 flex items-center justify-between">
                    <span class="text-xs font-medium px-3 py-1 rounded-full
                        {{ $notification['type'] === 'user'
                            ? 'bg-blue-100 text-blue-700'
                            : 'bg-amber-100 text-amber-700' }}">
                        {{ $notification['tipe'] }}
                    </span>

                    <a href="{{ $notification['link'] }}"
                       class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center text-slate-500 py-10">
            Tidak ada notifikasi dalam 2 hari terakhir
        </div>
        @endforelse

    </div>
</div>

@endsection
