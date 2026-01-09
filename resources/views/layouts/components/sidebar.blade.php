<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white text-slate-800 border-r border-slate-200
         transition-transform duration-300 transform -translate-x-full
         lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex flex-col h-full">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-6 bg-slate-50 border-b border-slate-200">
            <span class="text-xl font-bold tracking-wider text-slate-800">MY APP</span>
            <button onclick="toggleSidebar()" class="lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Sidebar Nav -->
        <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto custom-scrollbar">

            @if(auth()->user()->role === 'admin')
            <p class="px-2 pb-2 text-xs font-semibold text-slate-500 uppercase">Menu Utama</p>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('admin.dashboard')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>

            <!-- Registrasi & Verifikasi -->
            <a href="{{ route('admin.register.index') }}"
                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('admin.register.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Registrasi & Verifikasi
            </a>

            <!-- Data Kepegawaian -->
            <a href="{{ route('admin.pegawai.index') }}"
                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('admin.pegawai.index')) bg-green-50 text-green-600   @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Data Kepegawaian
            </a>

            <!-- Riwayat Kepegawaian -->
            <a href="{{ route('admin.riwayat_kepegawaian.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('admin.riwayat_kepegawaian.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Riwayat Kepegawaian
            </a>

            <!-- Penugasan -->
            <a href="{{ route('admin.penugasan.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('admin.penugasan.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Penugasan
            </a>

            <!-- Catatan Kegiatan -->
            <a href="{{ route('admin.catatan_kegiatan.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('admin.catatan_kegiatan.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Catatan Kegiatan
            </a>

            <!-- Data Referensi (Dropdown) -->
            <div class="relative">
                <button type="button" onclick="toggleSidebarDropdown('ref-dropdown', 'ref-arrow')"
                    class="w-full flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('admin.index.golongan*', 'admin.index.jabatan*', 'admin.index.unitkerja*')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span class="flex-1 text-left">Data Referensi</span>
                    <svg id="ref-arrow"
                        class="w-4 h-4 transition-transform @if(request()->routeIs('admin.index.golongan*', 'admin.index.jabatan*', 'admin.index.unitkerja*')) rotate-180 @endif"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="ref-dropdown"
                    class="mt-1 ml-8 space-y-1 @unless(request()->routeIs('admin.index.golongan*', 'admin.index.jabatan*', 'admin.index.unitkerja*')) hidden @endunless">

                    <a href="{{ route('admin.index.golongan') }}"
                        class="block px-4 py-2 text-sm rounded-lg transition 
                              @if(request()->routeIs('admin.index.golongan*')) bg-green-50 text-green-600 
                              @else text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                        Golongan
                    </a>

                    <a href="{{ route('admin.index.jabatan') }}"
                        class="block px-4 py-2 text-sm rounded-lg transition 
                              @if(request()->routeIs('admin.index.jabatan*')) bg-green-50 text-green-600 
                              @else text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                        Jabatan
                    </a>

                    <a href="{{ route('admin.index.unitkerja') }}"
                        class="block px-4 py-2 text-sm rounded-lg transition 
                              @if(request()->routeIs('admin.index.unitkerja*')) bg-green-50 text-green-600 
                              @else text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                        Unit Kerja
                    </a>
                </div>
            </div>

            <!-- Log Aktivitas -->
            <a href="{{ route('admin.logs.index') }}"
                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors 
                      @if(request()->routeIs('admin.logs.index')) bg-green-50 text-green-600 
                      @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Log Aktivitas
            </a>

            <a href="{{ route('admin.notifikasi.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('admin.notifikasi.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                Notifikasi
            </a>
            @endif

            @if(auth()->user()->role === 'kph')
            <p class="px-2 pb-2 text-xs font-semibold text-slate-500 uppercase">Menu Utama</p>

            <a href="{{ route('kph.dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('kph.dashboard')) bg-green-50 text-green-600   @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('kph.pegawai.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('kph.pegawai.index')) bg-green-50 text-green-600   @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Data Kepegawaian
            </a>

            <a href="{{route('kph.riwayat_kepegawaian.index')}}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('kph.riwayat_kepegawaian.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Riwayat Kepegawaian
            </a>

            <a href="{{ route('kph.penugasan.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('kph.penugasan.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Penugasan
            </a>

            <a href="{{ route('kph.catatan_kegiatan.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('kph.catatan_kegiatan.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">

                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Catatan Kegiatan
            </a>
            @endif

            @if(auth()->user()->role === 'pegawai')
            <p class="px-2 pb-2 text-xs font-semibold text-slate-500 uppercase">Menu Utama</p>

            <a href="{{ route('pegawai.dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('pegawai.dashboard')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('pegawai.data_diri.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('pegawai.data_diri.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Data Diri
            </a>

            <a href="{{ route('pegawai.data_kepegawaian.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('pegawai.data_kepegawaian.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Data Kepegawaian Saya
            </a>

            <a href="{{ route('pegawai.direktori.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('pegawai.direktori.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Direktori Pegawai
            </a>

            <a href="{{ route('pegawai.tugas.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('pegawai.tugas.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Tugas Saya
            </a>

            <a href="{{ route('pegawai.catatan_kegiatan.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('pegawai.catatan_kegiatan.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Catatan Kegiatan
            </a>

            <a href="{{ route('pegawai.notifikasi.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors @if(request()->routeIs('pegawai.notifikasi.index')) bg-green-50 text-green-600 @else text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 @endif">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                Notifikasi
            </a>
            @endif

        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 bg-slate-50 border-t border-slate-200 text-center text-[10px] text-slate-500">
            Versi 1.0.0
        </div>
    </div>
</aside>