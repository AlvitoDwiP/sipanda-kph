@php
    $role = auth()->user()?->role ?? 'admin';
    
    // Dynamic sidebar badges
    $waitingUsersCount = 0;
    $pendingTasksCount = 0;
    
    try {
        if (auth()->check()) {
            if ($role === 'super_admin' || $role === 'admin') {
                // Pending user accounts waiting verification
                $waitingUsersCount = \App\Models\User::where('status_akun', 'waiting')->count();
                // Tasks waiting verification
                $pendingTasksCount = \App\Models\Penugasan::where('status', 'menunggu_verifikasi')->count();
            } elseif ($role === 'kph') {
                // KPH tasks waiting verification
                $pendingTasksCount = \App\Models\Penugasan::where('status', 'menunggu_verifikasi')
                    ->whereHas('pegawai', function($q) {
                        $q->where('unitkerja_id', auth()->user()->pegawai?->unitkerja_id);
                    })->count();
            } elseif ($role === 'pegawai') {
                // My active/incomplete tasks
                $pendingTasksCount = auth()->user()->pegawai?->penugasan()
                    ->whereIn('status', ['belum_mulai', 'proses', 'revisi'])
                    ->count() ?? 0;
            }
        }
    } catch (\Exception $e) {
        // Fallback silently if tables do not exist
    }
@endphp

<aside 
    id="sidebar"
    x-bind:class="[
        mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full',
        sidebarCollapsed ? 'lg:w-[72px]' : 'lg:w-64'
    ]"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-ui-primary text-white border-r border-ui-primary-hover/20
         transition-all duration-300 ease-in-out transform lg:translate-x-0 lg:static lg:inset-0 flex flex-col h-full shadow-ui-md select-none shrink-0"
>
    <!-- Brand Header -->
    <div class="h-16 px-4 flex items-center justify-between border-b border-white/10 shrink-0">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-8 h-8 rounded-ui-md bg-white/15 text-white flex items-center justify-center shrink-0 border border-white/20 font-bold text-sm">
                S
            </div>
            <div class="min-w-0 flex flex-col leading-none" x-show="!sidebarCollapsed" x-transition:enter="transition-all duration-200" style="display: block;">
                <span class="text-xs font-bold tracking-wider text-white">SIPANDA-KPH</span>
                <span class="text-[9px] font-bold text-white/50 tracking-wider mt-0.5">PERHUTANI</span>
            </div>
        </div>

        <!-- Sidebar Actions: Toggle Collapse on Desktop, Close Drawer on Mobile -->
        <div class="flex items-center gap-1.5 shrink-0">
            <button 
                type="button" 
                @click="sidebarCollapsed = !sidebarCollapsed" 
                class="hidden lg:flex items-center justify-center p-1.5 rounded-ui-md hover:bg-white/10 text-white/60 hover:text-white transition-colors focus:outline-none"
                aria-label="Toggle collapse sidebar"
            >
                <i data-lucide="chevron-left" x-bind:class="sidebarCollapsed ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200"></i>
            </button>
            <button 
                type="button" 
                @click="mobileSidebarOpen = false" 
                class="lg:hidden flex items-center justify-center p-1.5 rounded-ui-md hover:bg-white/10 text-white/60 hover:text-white transition-colors focus:outline-none"
                aria-label="Tutup sidebar"
            >
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <!-- Navigation Area -->
    <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto custom-scrollbar leading-none">
        
        {{-- ========================================================
             ROLE: SUPER ADMIN & ADMIN
             ======================================================== --}}
        @if(in_array($role, ['super_admin', 'admin'], true))
            <div>
                <!-- Group Title -->
                <p class="px-3 text-[10px] font-bold tracking-widest text-white/40 uppercase mb-2 select-none" x-show="!sidebarCollapsed">
                    Menu Utama
                </p>
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                        </div>
                    </a>

                    <!-- Registrasi & Verifikasi -->
                    <a href="{{ route('admin.register.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('admin.register.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="user-plus" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Registrasi User</span>
                        </div>
                        @if($waitingUsersCount > 0)
                            <span x-show="!sidebarCollapsed" class="bg-ui-danger text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-full shrink-0 shadow-sm">
                                {{ $waitingUsersCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Data Kepegawaian -->
                    <a href="{{ route('admin.pegawai.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('admin.pegawai.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="users" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Kepegawaian</span>
                        </div>
                    </a>

                    <!-- Penugasan -->
                    <a href="{{ route('admin.penugasan.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('admin.penugasan.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="clipboard-list" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Penugasan</span>
                        </div>
                        @if($pendingTasksCount > 0)
                            <span x-show="!sidebarCollapsed" class="bg-ui-warning text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-full shrink-0 shadow-sm">
                                {{ $pendingTasksCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Catatan Kegiatan -->
                    <a href="{{ route('admin.catatan_kegiatan.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('admin.catatan_kegiatan.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="file-text" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Catatan Kegiatan</span>
                        </div>
                    </a>

                    <!-- Display Job Desk -->
                    <a href="{{ route('admin.display-jobdesk.manage') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('admin.display-jobdesk.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="monitor" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Display Job Desk</span>
                        </div>
                    </a>

                    <!-- Rekap Pekerjaan -->
                    <a href="{{ route('admin.rekap-pekerjaan.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('admin.rekap-pekerjaan.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Rekap Pekerjaan</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Reference Section -->
            <div>
                <p class="px-3 text-[10px] font-bold tracking-widest text-white/40 uppercase mb-2 select-none" x-show="!sidebarCollapsed">
                    Data Referensi
                </p>
                <div class="space-y-1" x-data="{ open: {{ request()->routeIs('admin.golongan.*', 'admin.jabatan.*', 'admin.unitkerja.*') ? 'true' : 'false' }} }">
                    <button 
                        type="button" 
                        @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open; }"
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150 text-white/70 hover:bg-white/5 hover:text-white focus:outline-none"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="database" class="w-4 h-4 shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Referensi</span>
                        </div>
                        <i data-lucide="chevron-down" x-show="!sidebarCollapsed" x-bind:class="open ? 'rotate-180' : ''" class="w-3.5 h-3.5 transition-transform duration-200 text-white/50 shrink-0"></i>
                    </button>

                    <!-- Dropdown Content -->
                    <div 
                        x-show="open && !sidebarCollapsed" 
                        x-transition:enter="transition-all ease-out duration-200"
                        x-transition:enter-start="max-h-0 opacity-0"
                        x-transition:enter-end="max-h-40 opacity-100"
                        class="pl-7 pr-1 py-1 space-y-1 border-l border-white/10 ml-5"
                        style="display: none;"
                    >
                        <a href="{{ route('admin.golongan.index') }}"
                           class="block px-3 py-2 rounded-ui-md text-[11px] font-semibold transition-all
                                  {{ request()->routeIs('admin.golongan.*') ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                            Golongan
                        </a>
                        <a href="{{ route('admin.jabatan.index') }}"
                           class="block px-3 py-2 rounded-ui-md text-[11px] font-semibold transition-all
                                  {{ request()->routeIs('admin.jabatan.*') ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                            Jabatan
                        </a>
                        <a href="{{ route('admin.unitkerja.index') }}"
                           class="block px-3 py-2 rounded-ui-md text-[11px] font-semibold transition-all
                                  {{ request()->routeIs('admin.unitkerja.*') ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                            Unit Kerja
                        </a>
                    </div>
                </div>
            </div>

            <!-- Activity Logs Section -->
            <div>
                <p class="px-3 text-[10px] font-bold tracking-widest text-white/40 uppercase mb-2 select-none" x-show="!sidebarCollapsed">
                    Sistem
                </p>
                <div class="space-y-1">
                    <a href="{{ route('admin.logs.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('admin.logs.index') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="history" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Log Aktivitas</span>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        {{-- ========================================================
             ROLE: KPH
             ======================================================== --}}
        @if($role === 'kph')
            <div>
                <p class="px-3 text-[10px] font-bold tracking-widest text-white/40 uppercase mb-2 select-none" x-show="!sidebarCollapsed">
                    Menu KPH
                </p>
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('kph.dashboard') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('kph.dashboard') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                        </div>
                    </a>

                    <!-- Data Kepegawaian -->
                    <a href="{{ route('kph.pegawai.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('kph.pegawai.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="users" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Data Pegawai</span>
                        </div>
                    </a>

                    <!-- Penugasan -->
                    <a href="{{ route('kph.penugasan.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('kph.penugasan.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="clipboard-list" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Penugasan</span>
                        </div>
                        @if($pendingTasksCount > 0)
                            <span x-show="!sidebarCollapsed" class="bg-ui-warning text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-full shrink-0 shadow-sm">
                                {{ $pendingTasksCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Catatan Kegiatan -->
                    <a href="{{ route('kph.catatan_kegiatan.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('kph.catatan_kegiatan.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="file-text" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Catatan Kegiatan</span>
                        </div>
                    </a>

                    <!-- Display Job Desk -->
                    <a href="{{ route('kph.display-jobdesk.manage') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('kph.display-jobdesk.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="monitor" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Display Job Desk</span>
                        </div>
                    </a>

                    <!-- Rekap Pekerjaan -->
                    <a href="{{ route('kph.rekap-pekerjaan.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('kph.rekap-pekerjaan.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Rekap Pekerjaan</span>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        {{-- ========================================================
             ROLE: OPERATOR DISPLAY
             ======================================================== --}}
        @if($role === 'operator_display')
            <div>
                <p class="px-3 text-[10px] font-bold tracking-widest text-white/40 uppercase mb-2 select-none" x-show="!sidebarCollapsed">
                    Operator Menu
                </p>
                <div class="space-y-1">
                    <a href="{{ route('operator-display.display-jobdesk.manage') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('operator-display.display-jobdesk.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="monitor" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Display Job Desk</span>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        {{-- ========================================================
             ROLE: PEGAWAI
             ======================================================== --}}
        @if($role === 'pegawai')
            <div>
                <p class="px-3 text-[10px] font-bold tracking-widest text-white/40 uppercase mb-2 select-none" x-show="!sidebarCollapsed">
                    Menu Pegawai
                </p>
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('pegawai.dashboard') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('pegawai.dashboard') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                        </div>
                    </a>

                    <!-- Data Diri -->
                    <a href="{{ route('pegawai.data_diri.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('pegawai.data_diri.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="user" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Data Diri Saya</span>
                        </div>
                    </a>

                    <!-- Data Kepegawaian Saya -->
                    <a href="{{ route('pegawai.data_kepegawaian.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('pegawai.data_kepegawaian.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="file-badge" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Kepegawaian Saya</span>
                        </div>
                    </a>

                    <!-- Tugas Saya -->
                    <a href="{{ route('pegawai.tugas.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('pegawai.tugas.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="list-todo" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Tugas Saya</span>
                        </div>
                        @if($pendingTasksCount > 0)
                            <span x-show="!sidebarCollapsed" class="bg-ui-warning text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-full shrink-0 shadow-sm">
                                {{ $pendingTasksCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Catatan Kegiatan -->
                    <a href="{{ route('pegawai.catatan_kegiatan.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-ui-md text-xs font-semibold tracking-wide transition-all group duration-150
                              {{ request()->routeIs('pegawai.catatan_kegiatan.*') ? 'bg-white/15 text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="file-text" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Catatan Kegiatan</span>
                        </div>
                    </a>
                </div>
            </div>
        @endif

    </nav>

    <!-- Sidebar Profile Summary footer -->
    <div class="p-4 border-t border-white/10 bg-black/10 shrink-0 select-none">
        <div class="flex items-center gap-3 min-w-0">
            <!-- Avatar -->
            <div class="w-9 h-9 rounded-full overflow-hidden border border-white/20 bg-white/10 text-white flex items-center justify-center text-xs font-bold shrink-0">
                @if (Auth::user()->avatar)
                    <img src="{{ asset('storage/'.Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
            </div>

            <!-- Profile Info labels -->
            <div class="min-w-0 text-left" x-show="!sidebarCollapsed" x-transition:enter="transition-all duration-200">
                <div class="truncate text-xs font-bold text-white leading-tight">{{ Auth::user()->name }}</div>
                <div class="truncate text-[10px] text-white/50 leading-none mt-0.5">{{ Auth::user()->email }}</div>
            </div>
        </div>
        
        <!-- App version label -->
        <div class="mt-3 flex justify-between items-center text-[9px] font-bold text-white/30 tracking-wide leading-none" x-show="!sidebarCollapsed" x-transition:enter="transition-all duration-200">
            <span>SIPANDA-KPH</span>
            <span>v1.2.0</span>
        </div>
    </div>
</aside>
