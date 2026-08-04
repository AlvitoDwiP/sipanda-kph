@props([
    'items' => null,
])

@php
    // If items are not passed explicitly, build them dynamically from path/route
    if (is_null($items)) {
        $currentRoute = Route::currentRouteName();
        $currentUrl = request()->path();
        $segments = explode('/', $currentUrl);
        $items = [];
        
        $role = auth()->user()?->role ?? 'admin';
        
        // Determine dashboard route based on user role
        $dashboardRoute = 'admin.dashboard';
        if ($role === 'kph') {
            $dashboardRoute = 'kph.dashboard';
        } elseif ($role === 'pegawai') {
            $dashboardRoute = 'pegawai.dashboard';
        } elseif ($role === 'operator_display') {
            $dashboardRoute = 'operator-display.display-jobdesk.manage';
        }
        
        // Add home/dashboard base
        if (Route::has($dashboardRoute)) {
            $items[] = [
                'label' => 'Dashboard',
                'url' => route($dashboardRoute),
                'icon' => 'home'
            ];
        } else {
            $items[] = [
                'label' => 'Dashboard',
                'url' => '#',
                'icon' => 'home'
            ];
        }
        
        $cumulativeSegments = [];
        foreach ($segments as $index => $segment) {
            // Skip the first segment if it matches role prefixes to avoid duplicate Dashboard elements
            if ($index === 0 && in_array($segment, ['admin', 'kph', 'pegawai', 'operator-display', 'operator_display'])) {
                continue;
            }
            
            // Skip ID and numeric URL segments
            if (is_numeric($segment) || strlen($segment) > 30) {
                continue;
            }
            
            $cumulativeSegments[] = $segment;
            $label = ucwords(str_replace(['-', '_'], ' ', $segment));
            
            // Custom label mappings for Indonesian translation and clarity
            $mappings = [
                'pegawai' => 'Data Kepegawaian',
                'penugasan' => 'Penugasan',
                'catatan-kegiatan' => 'Catatan Kegiatan',
                'catatan_kegiatan' => 'Catatan Kegiatan',
                'display-jobdesk' => 'Display Job Desk',
                'rekap-pekerjaan' => 'Rekap Pekerjaan',
                'register' => 'Registrasi & Verifikasi',
                'golongan' => 'Golongan',
                'jabatan' => 'Jabatan',
                'unitkerja' => 'Unit Kerja',
                'logs' => 'Log Aktivitas',
                'create' => 'Tambah',
                'edit' => 'Edit',
                'show' => 'Detail',
                'data-diri' => 'Data Diri',
                'data_diri' => 'Data Diri',
                'data-kepegawaian' => 'Kepegawaian Saya',
                'data_kepegawaian' => 'Kepegawaian Saya',
                'tugas' => 'Tugas Saya',
                'notifikasi' => 'Notifikasi',
                'notifications' => 'Notifikasi',
            ];
            
            if (isset($mappings[strtolower($segment)])) {
                $label = $mappings[strtolower($segment)];
            }
            
            // Determine logical URLs for middle path segments
            $url = null;
            if ($segment === 'pegawai') {
                $url = Route::has($role . '.pegawai.index') ? route($role . '.pegawai.index') : null;
            } elseif ($segment === 'penugasan') {
                $url = Route::has($role . '.penugasan.index') ? route($role . '.penugasan.index') : null;
            } elseif ($segment === 'catatan_kegiatan' || $segment === 'catatan-kegiatan') {
                $url = Route::has($role . '.catatan_kegiatan.index') ? route($role . '.catatan_kegiatan.index') : null;
            } elseif ($segment === 'golongan') {
                $url = Route::has('admin.golongan.index') ? route('admin.golongan.index') : null;
            } elseif ($segment === 'jabatan') {
                $url = Route::has('admin.jabatan.index') ? route('admin.jabatan.index') : null;
            } elseif ($segment === 'unitkerja') {
                $url = Route::has('admin.unitkerja.index') ? route('admin.unitkerja.index') : null;
            }
            
            $items[] = [
                'label' => $label,
                'url' => $url,
            ];
        }
        
        // Ensure the last element in the breadcrumbs is never clickable
        if (count($items) > 1) {
            $items[count($items) - 1]['url'] = null;
        }
    }
@endphp

<nav class="flex items-center gap-1 text-[11px] sm:text-xs text-ui-text-secondary select-none" aria-label="Breadcrumb">
    @foreach ($items as $index => $item)
        @if ($index > 0)
            <i data-lucide="chevron-right" class="w-3 h-3 text-ui-muted shrink-0 mx-0.5" aria-hidden="true"></i>
        @endif
        
        <div class="flex items-center gap-1 min-w-0">
            @if (isset($item['icon']))
                <i data-lucide="{{ $item['icon'] }}" class="w-3.5 h-3.5 text-ui-text-secondary shrink-0" aria-hidden="true"></i>
            @endif
            
            @if (isset($item['url']) && $item['url'])
                <a href="{{ $item['url'] }}" class="hover:text-ui-primary font-medium transition-colors duration-150 truncate">
                    {{ $item['label'] }}
                </a>
            @else
                <span class="font-semibold text-ui-primary truncate">
                    {{ $item['label'] }}
                </span>
            @endif
        </div>
    @endforeach
</nav>
