@php
    // Determine search route and placeholder based on current page context
    $currentRoute = Route::currentRouteName();
    $searchRoute = '#';
    $searchPlaceholder = 'Cari...';

    if (str_contains($currentRoute, 'admin.pegawai')) {
        $searchRoute = route('admin.pegawai.index');
        $searchPlaceholder = 'Cari pegawai...';
    } elseif (str_contains($currentRoute, 'kph.pegawai')) {
        $searchRoute = route('kph.pegawai.index');
        $searchPlaceholder = 'Cari pegawai...';
    } elseif (str_contains($currentRoute, 'notifications.')) {
        $searchRoute = route('notifications.index');
        $searchPlaceholder = 'Cari notifikasi...';
    } elseif (str_contains($currentRoute, 'admin.notifikasi')) {
        $searchRoute = route('admin.notifikasi.index');
        $searchPlaceholder = 'Cari notifikasi...';
    } elseif (str_contains($currentRoute, 'pegawai.notifikasi')) {
        $searchRoute = route('pegawai.notifikasi.index');
        $searchPlaceholder = 'Cari notifikasi...';
    } elseif (str_contains($currentRoute, 'admin.register')) {
        $searchRoute = route('admin.register.index');
        $searchPlaceholder = 'Cari user...';
    } elseif (str_contains($currentRoute, 'admin.penugasan')) {
        $searchRoute = route('admin.penugasan.index');
        $searchPlaceholder = 'Cari penugasan...';
    } elseif (str_contains($currentRoute, 'pegawai.tugas')) {
        $searchRoute = route('pegawai.tugas.index');
        $searchPlaceholder = 'Cari tugas...';
    } elseif (str_contains($currentRoute, 'pegawai.catatan_kegiatan')) {
        $searchRoute = route('pegawai.catatan_kegiatan.index');
        $searchPlaceholder = 'Cari catatan kegiatan...';
    } elseif (str_contains($currentRoute, 'pegawai.data_diri')) {
        $searchRoute = route('pegawai.data_diri.index');
        $searchPlaceholder = 'Cari data diri...';
    } elseif (str_contains($currentRoute, 'pegawai.data_kepegawaian')) {
        $searchRoute = route('pegawai.data_kepegawaian.index');
        $searchPlaceholder = 'Cari data kepegawaian...';
    } elseif (str_contains($currentRoute, 'admin.golongan')) {
        $searchRoute = route('admin.golongan.index');
        $searchPlaceholder = 'Cari golongan...';
    } elseif (str_contains($currentRoute, 'admin.jabatan')) {
        $searchRoute = route('admin.jabatan.index');
        $searchPlaceholder = 'Cari jabatan...';
    } elseif (str_contains($currentRoute, 'admin.unitkerja')) {
        $searchRoute = route('admin.unitkerja.index');
        $searchPlaceholder = 'Cari unit kerja...';
    } elseif (str_contains($currentRoute, 'kph.penugasan')) {
        $searchRoute = route('kph.penugasan.index');
        $searchPlaceholder = 'Cari penugasan...';
    } elseif (str_contains($currentRoute, 'kph.catatan_kegiatan')) {
        $searchRoute = route('kph.catatan_kegiatan.index');
        $searchPlaceholder = 'Cari catatan kegiatan...';
    } elseif (str_contains($currentRoute, 'admin.logs')) {
        $searchRoute = route('admin.logs.index');
        $searchPlaceholder = 'Cari log...';
    }

    // Safety checks for notifications
    $unreadNotificationsCount = 0;
    $recentNotifications = collect();
    try {
        if (auth()->check() && \Illuminate\Support\Facades\Schema::hasTable('notifications')) {
            $unreadNotificationsCount = auth()->user()->unreadNotifications()->count();
            $recentNotifications = auth()->user()->unreadNotifications()->take(5)->get();
            if ($recentNotifications->isEmpty()) {
                $recentNotifications = auth()->user()->notifications()->take(5)->get();
            }
        }
    } catch (\Exception $e) {
        // Fallback gracefully
    }
@endphp

<header class="app-topbar flex items-center justify-between px-4 sm:px-6 h-16 border-b border-ui-border bg-ui-surface sticky top-0 z-30 shrink-0 select-none">
    <!-- Left Section: Mobile Trigger and Page Title/Breadcrumbs -->
    <div class="flex items-center gap-3.5 min-w-0">
        <button 
            type="button"
            @click="mobileSidebarOpen = !mobileSidebarOpen"
            aria-label="Buka sidebar"
            class="p-2 text-ui-text-secondary rounded-ui-md lg:hidden hover:bg-ui-primary-soft hover:text-ui-primary focus:outline-none transition-colors"
        >
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        
        <!-- Desktop Breadcrumbs (Hidden on Mobile) -->
        <div class="hidden md:block min-w-0">
            <x-ui.breadcrumb />
        </div>
        
        <!-- Mobile Simple Brand Title (Hidden on Desktop) -->
        <div class="md:hidden truncate text-sm font-bold text-ui-primary">
            SIPANDA
        </div>
    </div>

    <!-- Right Section: Actions, Search, Notifications, Profile -->
    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
        
        {{-- SEARCH FORM (Hidden on small screens) --}}
        @if ($searchRoute !== '#')
            <div class="hidden sm:block relative">
                <form action="{{ $searchRoute }}" method="GET" class="relative flex items-center">
                    <span class="absolute left-3 text-ui-text-secondary pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="{{ $searchPlaceholder }}"
                        class="pl-9 pr-4 py-1.5 w-48 sm:w-56 md:w-64 text-xs rounded-ui-md border border-ui-border bg-ui-bg text-ui-text-primary placeholder-ui-muted focus:border-ui-primary focus:bg-ui-surface transition-all outline-none"
                    >
                </form>
            </div>
        @endif

        {{-- NOTIFICATION DROPDOWN --}}
        <div class="relative shrink-0" x-data="{ open: false }" @click.outside="open = false">
            <button 
                type="button"
                @click="open = !open"
                aria-label="Notifikasi"
                class="p-2 text-ui-text-secondary rounded-ui-md hover:bg-ui-primary-soft hover:text-ui-primary focus:outline-none transition-colors relative"
            >
                <i data-lucide="bell" class="w-5 h-5"></i>
                @if($unreadNotificationsCount > 0)
                    <span class="absolute top-1.5 right-1.5 min-w-[16px] h-4 px-1 rounded-full bg-ui-danger text-white text-[9px] font-bold flex items-center justify-center border-2 border-ui-surface">
                        {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                    </span>
                @endif
            </button>

            {{-- NOTIFICATION DROPDOWN MENU --}}
            <div 
                x-show="open"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-72 sm:w-80 bg-ui-surface rounded-ui-lg shadow-ui-lg border border-ui-border overflow-hidden z-50 origin-top-right text-xs"
                style="display: none;"
            >
                <!-- Header -->
                <div class="px-4 py-3 bg-ui-primary-soft/30 border-b border-ui-border flex items-center justify-between">
                    <span class="font-bold text-ui-primary flex items-center gap-1.5">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                        Notifikasi
                    </span>
                    @if($unreadNotificationsCount > 0 && Route::has('notifications.read-all'))
                        <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-[10px] font-bold text-ui-primary hover:text-ui-primary-hover hover:underline transition-colors focus:outline-none">
                                Tandai semua dibaca
                            </button>
                        </form>
                    @endif
                </div>

                <!-- List Container -->
                <div class="max-h-64 overflow-y-auto divide-y divide-ui-border/50 custom-scrollbar">
                    @forelse ($recentNotifications as $notification)
                        @php
                            $msg = $notification->data['message'] ?? $notification->data['judul'] ?? 'Notifikasi Baru';
                            $isUnread = is_null($notification->read_at);
                            $iconName = 'info';
                            $iconColor = 'text-ui-info bg-ui-info-soft border-ui-info/10';
                            
                            if (str_contains(strtolower($msg), 'revisi') || str_contains(strtolower($msg), 'perlu')) {
                                $iconName = 'alert-triangle';
                                $iconColor = 'text-ui-warning bg-ui-warning-soft border-ui-warning/10';
                            } elseif (str_contains(strtolower($msg), 'ditolak') || str_contains(strtolower($msg), 'batal') || str_contains(strtolower($msg), 'terlambat')) {
                                $iconName = 'x-circle';
                                $iconColor = 'text-ui-danger bg-ui-danger-soft border-ui-danger/10';
                            } elseif (str_contains(strtolower($msg), 'disetujui') || str_contains(strtolower($msg), 'selesai') || str_contains(strtolower($msg), 'berhasil')) {
                                $iconName = 'check-circle';
                                $iconColor = 'text-ui-success bg-ui-success-soft border-ui-success/10';
                            }
                        @endphp
                        
                        <div class="p-3.5 hover:bg-ui-primary-soft/40 transition-colors flex items-start gap-3 relative {{ $isUnread ? 'bg-ui-primary-soft/10' : '' }}">
                            @if ($isUnread)
                                <span class="absolute top-4 right-3.5 w-1.5 h-1.5 rounded-full bg-ui-primary"></span>
                            @endif
                            
                            <div class="w-8 h-8 rounded-ui-md flex items-center justify-center shrink-0 border {{ $iconColor }}">
                                <i data-lucide="{{ $iconName }}" class="w-4.5 h-4.5"></i>
                            </div>
                            
                            <div class="flex-1 min-w-0 pr-2">
                                <p class="text-ui-text-primary leading-normal text-[11.5px] font-medium break-words">
                                    {{ $msg }}
                                </p>
                                <span class="text-[10px] text-ui-muted mt-1 inline-block">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 px-4 text-center">
                            <div class="w-10 h-10 rounded-full bg-ui-primary-soft flex items-center justify-center mx-auto mb-2.5 text-ui-text-secondary">
                                <i data-lucide="bell-off" class="w-5 h-5 text-ui-muted"></i>
                            </div>
                            <p class="text-ui-text-secondary font-medium">Tidak ada notifikasi baru</p>
                            <p class="text-[10px] text-ui-muted mt-0.5">Semua pemberitahuan Anda akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Footer Link -->
                <div class="bg-ui-primary-soft/10 border-t border-ui-border text-center">
                    <a 
                        href="{{ route('notifications.index') }}" 
                        class="block py-2.5 text-[11px] font-bold text-ui-primary hover:bg-ui-primary-soft/50 transition-colors"
                    >
                        Lihat Semua Notifikasi
                    </a>
                </div>
            </div>
        </div>

        {{-- USER PROFILE DROPDOWN --}}
        <div class="relative shrink-0" x-data="{ open: false }" @click.outside="open = false">
            <button 
                type="button"
                @click="open = !open"
                class="flex items-center gap-2 hover:bg-ui-primary-soft p-1.5 rounded-ui-lg focus:outline-none transition-colors border border-transparent"
            >
                <div class="hidden sm:block text-right">
                    <p class="text-[11px] font-bold text-ui-text-primary leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-[9.5px] text-ui-text-secondary leading-none mt-0.5">{{ Auth::user()->email }}</p>
                </div>

                <div class="w-8.5 h-8.5 rounded-full overflow-hidden border border-ui-border bg-ui-primary-soft text-ui-primary flex items-center justify-center text-xs font-bold shrink-0 shadow-sm transition-transform active:scale-95">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('storage/'.Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
            </button>

            {{-- PROFILE MENU DROPDOWN --}}
            <div 
                x-show="open"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-56 bg-ui-surface rounded-ui-lg shadow-ui-lg border border-ui-border overflow-hidden z-50 origin-top-right text-xs divide-y divide-ui-border/50 text-ui-text-primary"
                style="display: none;"
            >
                <!-- Profile Header -->
                <div class="p-4 flex items-center gap-3 bg-ui-primary-soft/10">
                    <div class="w-10 h-10 rounded-full overflow-hidden border border-ui-border bg-ui-primary-soft text-ui-primary flex items-center justify-center text-sm font-bold shrink-0">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset('storage/'.Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-ui-text-primary truncate leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-ui-text-secondary truncate mt-0.5 leading-none">{{ Auth::user()->email }}</p>
                        <div class="mt-1.5">
                            <x-ui.badge variant="success" styleType="soft" size="sm">
                                {{ strtoupper(str_replace('_', ' ', Auth::user()->role)) }}
                            </x-ui.badge>
                        </div>
                    </div>
                </div>

                <!-- Menu Links -->
                <div class="py-1">
                    <a 
                        href="{{ route('profile.edit') }}" 
                        class="flex items-center gap-2.5 px-4 py-2 hover:bg-ui-primary-soft hover:text-ui-primary transition-colors text-ui-text-primary font-medium"
                    >
                        <i data-lucide="settings" class="w-4 h-4 text-ui-text-secondary"></i>
                        <span>Pengaturan Akun</span>
                    </a>
                </div>

                <!-- Logout Trigger -->
                <div class="py-1">
                    <button 
                        type="button"
                        @click="$dispatch('open-modal', 'logout-confirm')"
                        class="w-full flex items-center gap-2.5 px-4 py-2 hover:bg-ui-danger-soft hover:text-ui-danger text-ui-danger transition-colors font-medium text-left"
                    >
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Keluar Sesi</span>
                    </button>
                </div>
            </div>
        </div>

    </div>
</header>
