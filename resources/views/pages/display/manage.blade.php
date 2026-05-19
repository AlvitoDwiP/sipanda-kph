@extends('layouts.master')

@section('title', $pageTitle)

@section('content')
<div class="bg-white rounded-xl shadow p-6 border border-slate-200 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Display Job Desk Harian</h1>
        <p class="text-sm text-slate-600 mt-1">Fitur ini digunakan untuk menampilkan job desk harian pegawai melalui TV setelah QR pegawai discan.</p>
        <p class="text-sm text-amber-700 mt-2 font-medium">Fitur ini bukan absensi.</p>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid md:grid-cols-3 gap-4">
        <div class="rounded-lg border border-slate-200 p-4">
            <p class="text-xs uppercase tracking-wide text-slate-500">Status Aktivitas</p>
            @if($summary['last_scan_at'])
                <p class="mt-2 text-lg font-semibold text-emerald-700">Aktif</p>
                <p class="text-sm text-slate-600">Scan terakhir {{ $summary['last_scan_at']->diffForHumans() }}</p>
            @else
                <p class="mt-2 text-lg font-semibold text-slate-700">Belum ada aktivitas scan hari ini</p>
            @endif
        </div>
        <div class="rounded-lg border border-slate-200 p-4">
            <p class="text-xs uppercase tracking-wide text-slate-500">Status Scanner</p>
            <p class="mt-2 text-lg font-semibold text-slate-700">Siap jika halaman TV terbuka</p>
            <p class="text-sm text-slate-600">Scanner USB bekerja seperti keyboard dan butuh input fokus.</p>
        </div>
        <div class="rounded-lg border border-slate-200 p-4">
            <p class="text-xs uppercase tracking-wide text-slate-500">Total Scan Hari Ini</p>
            <p class="mt-2 text-3xl font-bold text-slate-800">{{ $summary['total'] }}</p>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ $tvRoute }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 text-sm font-medium">Buka Mode TV</a>
        <a href="{{ $tvRoute }}" target="_blank" rel="noopener" class="px-4 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-800 text-sm font-medium">Buka di Tab Baru</a>
        <form action="{{ $resetRoute }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-4 py-2 rounded-lg bg-amber-600 text-white hover:bg-amber-700 text-sm font-medium">Reset Tampilan</button>
        </form>
    </div>

    <div class="rounded-lg border border-slate-200 p-4">
        <h2 class="font-semibold text-slate-800 mb-4">Pengaturan Display</h2>
        <form method="POST" action="{{ $settingUpdateRoute }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Durasi tampil hasil scan (detik)</label>
                <input type="number" name="display_duration_seconds" min="5" max="60" value="{{ old('display_duration_seconds', $setting->display_duration_seconds) }}" class="w-full md:w-60 rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('display_duration_seconds')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="show_employee_photo" value="1" @checked(old('show_employee_photo', $setting->show_employee_photo)) class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                Tampilkan foto pegawai di TV
            </label>
            <div>
                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 text-sm font-medium">Simpan Pengaturan</button>
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-slate-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h2 class="font-semibold text-slate-800">Riwayat Scan Hari Ini</h2>
            <span class="text-xs text-slate-500">Token QR tidak ditampilkan mentah</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="text-left px-4 py-2">Waktu</th>
                        <th class="text-left px-4 py-2">Pegawai</th>
                        <th class="text-left px-4 py-2">Status</th>
                        <th class="text-left px-4 py-2">Jumlah Tugas</th>
                        <th class="text-left px-4 py-2">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($logs as $log)
                    <tr class="border-t border-slate-200">
                        <td class="px-4 py-2">{{ optional($log->scanned_at)->format('H:i:s') }}</td>
                        <td class="px-4 py-2">{{ $log->pegawai->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs 
                                @class([
                                    'bg-emerald-100 text-emerald-700' => $log->status === 'success',
                                    'bg-amber-100 text-amber-700' => $log->status === 'empty_task',
                                    'bg-red-100 text-red-700' => in_array($log->status, ['invalid_token','invalid_format','inactive_employee','error','rate_limited']),
                                    'bg-slate-100 text-slate-700' => !in_array($log->status, ['success','empty_task','invalid_token','invalid_format','inactive_employee','error','rate_limited']),
                                ])">
                                {{ str_replace('_', ' ', $log->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $log->task_count }}</td>
                        <td class="px-4 py-2">{{ $log->message }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">Belum ada scan hari ini.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 p-4 bg-slate-50 text-sm text-slate-700">
        <p class="font-semibold mb-2">Panduan Penggunaan</p>
        <ol class="list-decimal list-inside space-y-1">
            <li>Hubungkan scanner USB ke PC/laptop.</li>
            <li>Buka Mode TV dan aktifkan fullscreen browser.</li>
            <li>Pastikan input scan tetap fokus.</li>
            <li>Pegawai scan QR masing-masing.</li>
            <li>Display akan reset otomatis setelah durasi yang ditentukan.</li>
        </ol>
    </div>
</div>
@endsection
