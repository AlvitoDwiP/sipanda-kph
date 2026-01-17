<header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200">
    <div class="flex items-center">
        <button onclick="toggleSidebar()"
            class="p-1 mr-4 text-gray-600 rounded-md lg:hidden hover:bg-gray-100 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <div class="flex items-center space-x-4">

        {{-- SEARCH --}}
        <div class="hidden md:block relative">
            @php
                // Determine search route and placeholder based on current page
                $currentRoute = Route::currentRouteName();
                $searchRoute = '#';
                $searchPlaceholder = 'Cari...';

                if (str_contains($currentRoute, 'admin.pegawai')) {
                    $searchRoute = route('admin.pegawai.index');
                    $searchPlaceholder = 'Cari pegawai...';
                } elseif (str_contains($currentRoute, 'kph.pegawai')) {
                    $searchRoute = route('kph.pegawai.index');
                    $searchPlaceholder = 'Cari pegawai...';
                } elseif (str_contains($currentRoute, 'pegawai.direktori')) {
                    $searchRoute = route('pegawai.direktori.index');
                    $searchPlaceholder = 'Cari pegawai...';
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
                } elseif (str_contains($currentRoute, 'admin.riwayat_kepegawaian')) {
                    $searchRoute = route('admin.riwayat_kepegawaian.index');
                    $searchPlaceholder = 'Cari riwayat kepegawaian...';
                } elseif (str_contains($currentRoute, 'admin.golongan')) {
                    $searchRoute = route('admin.index.golongan');
                    $searchPlaceholder = 'Cari golongan...';
                } elseif (str_contains($currentRoute, 'admin.jabatan')) {
                    $searchRoute = route('admin.index.jabatan');
                    $searchPlaceholder = 'Cari jabatan...';
                } elseif (str_contains($currentRoute, 'admin.unitkerja')) {
                    $searchRoute = route('admin.index.unitkerja');
                    $searchPlaceholder = 'Cari unit kerja...';
                } elseif (str_contains($currentRoute, 'kph.penugasan')) {
                    $searchRoute = route('kph.penugasan.index');
                    $searchPlaceholder = 'Cari penugasan...';
                } elseif (str_contains($currentRoute, 'kph.riwayat_kepegawaian')) {
                    $searchRoute = route('kph.riwayat_kepegawaian.index');
                    $searchPlaceholder = 'Cari riwayat kepegawaian...';
                } elseif (str_contains($currentRoute, 'kph.catatan_kegiatan')) {
                    $searchRoute = route('kph.catatan_kegiatan.index');
                    $searchPlaceholder = 'Cari catatan kegiatan...';
                } elseif (str_contains($currentRoute, 'admin.logs')) {
                    $searchRoute = route('admin.logs.index');
                    $searchPlaceholder = 'Cari log...';
                }
            @endphp
            <form action="{{ $searchRoute }}" method="GET">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="{{ $searchPlaceholder }}"
                    class="px-4 py-2.5 text-sm bg-gray-100 rounded-full focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none w-96">
            </form>
        </div>

        @if(auth()->user()->role === 'admin')
        {{-- NOTIFICATION ADMIN --}}
        <a href="{{ route('admin.notifikasi.index') }}"
            class="p-2 text-gray-400 hover:text-gray-600 border-r pr-4 inline-flex items-center">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                    a6.002 6.002 0 00-4-5.659V5
                    a2 2 0 10-4 0v.341
                    C7.67 6.165 6 8.388 6 11v3.159
                    c0 .538-.214 1.055-.595 1.436L4 17h5
                    m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>
        </a>
        @elseif(auth()->user()->role === 'pegawai')
        {{-- NOTIFICATION PEGAWAI --}}
        <a href="{{ route('pegawai.notifikasi.index') }}"
            class="p-2 text-gray-400 hover:text-gray-600 border-r pr-4 inline-flex items-center">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                    a6.002 6.002 0 00-4-5.659V5
                    a2 2 0 10-4 0v.341
                    C7.67 6.165 6 8.388 6 11v3.159
                    c0 .538-.214 1.055-.595 1.436L4 17h5
                    m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>
        </a>
        @endif

        {{-- USER DROPDOWN --}}
        <div class="relative" id="user-profile">
            <button type="button"
                onclick="toggleProfileDropdown()"
                class="flex items-center gap-2 hover:bg-gray-50 p-1 rounded-lg">

                <div class="hidden md:block text-right">
                    <p class="text-xs font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-500">{{ Auth::user()->email }}</p>
                </div>

                <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-300 bg-gray-100 flex items-center justify-center">
                    @if (Auth::user()->avatar)
                    <img src="{{ asset('storage/'.Auth::user()->avatar) }}">
                    @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>

            </button>

            {{-- DROPDOWN MENU --}}
            <div id="dropdown-menu"
                class="absolute right-0 mt-2 w-44 bg-white rounded-md shadow-lg border hidden z-50">

                <button type="button"
                    onclick="openLogoutModal()"
                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Logout
                </button>

            </div>
        </div>

    </div>
</header>