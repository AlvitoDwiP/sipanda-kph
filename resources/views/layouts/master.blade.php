<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>html{visibility:hidden}</style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        } elseif (str_contains($currentRoute, 'admin.rekap-pekerjaan')) {
            $searchRoute = route('admin.rekap-pekerjaan.index');
            $searchPlaceholder = 'Filter rekap...';
        } elseif (str_contains($currentRoute, 'kph.rekap-pekerjaan')) {
            $searchRoute = route('kph.rekap-pekerjaan.index');
            $searchPlaceholder = 'Filter rekap...';
        } elseif (str_contains($currentRoute, 'admin.logs')) {
            $searchRoute = route('admin.logs.index');
            $searchPlaceholder = 'Cari log...';
        }
    @endphp
    <title>@yield('title', 'Dashboard | Web Kepegawaian')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --sipanda-primary: #064E3B;
            --sipanda-primary-hover: #055040;
            --sipanda-accent: #6EE7A8;
            --sipanda-page: #F6F8F5;
            --sipanda-card: #FFFFFF;
            --sipanda-text: #1F2933;
            --sipanda-secondary: #667085;
            --sipanda-muted: #9CA3AF;
            --sipanda-border: #E0E8DF;
            --sipanda-input: #DDE7DC;
            --sipanda-soft: #F1F5F1;
            --sipanda-success-bg: #ECFDF5;
            --sipanda-success-text: #065F46;
            --sipanda-warning-bg: #FFFBEB;
            --sipanda-warning-text: #92400E;
            --sipanda-danger-bg: #FEF2F2;
            --sipanda-danger-text: #B91C1C;
            --sipanda-purple-bg: #F5F3FF;
            --sipanda-purple-text: #5B21B6;
            --sipanda-neutral-bg: #F3F4F6;
            --sipanda-neutral-text: #4B5563;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
            overflow: hidden;
            font-family: "Inter", sans-serif;
            font-size: 13px;
            color: var(--sipanda-text);
            background: var(--sipanda-page);
        }

        #app-layout {
            height: 100vh;
            min-height: 100vh;
            width: 100%;
            overflow: hidden;
            background: var(--sipanda-page);
        }

        #app-main {
            min-width: 0;
        }

        #app-content {
            min-width: 0;
            overflow-x: hidden;
            overflow-y: auto;
            background: var(--sipanda-page) !important;
            padding: 18px 22px !important;
        }

        #app-content > * {
            min-width: 0;
        }

        #sidebar {
            width: 256px !important;
            background: var(--sipanda-primary) !important;
            color: rgba(255, 255, 255, 0.88) !important;
            border-right: 1px solid rgba(0, 0, 0, 0.12) !important;
            box-shadow: none !important;
        }

        #sidebar .sidebar-brand {
            height: 52px;
            padding: 10px 14px;
            background: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        #sidebar .sidebar-logo {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: rgba(255, 255, 255, 0.14);
            object-fit: contain;
            padding: 2px;
        }

        #sidebar .sidebar-title {
            color: #FFFFFF;
            font-size: 13px;
            line-height: 1.1;
            font-weight: 700;
        }

        #sidebar .sidebar-subtitle,
        #sidebar nav p {
            color: rgba(255, 255, 255, 0.52) !important;
            font-size: 9.5px !important;
            font-weight: 700 !important;
            letter-spacing: .045em;
            text-transform: uppercase;
        }

        #sidebar nav {
            padding: 12px 10px !important;
            gap: 4px;
        }

        #sidebar nav a,
        #sidebar nav button {
            min-width: 0;
            padding: 7px 8px !important;
            border-radius: 6px !important;
            color: rgba(255, 255, 255, 0.74) !important;
            background: transparent !important;
            border-left: 3px solid transparent;
            font-size: 12.5px !important;
            line-height: 1.25;
            font-weight: 500 !important;
            gap: 7px;
        }

        #sidebar nav a:hover,
        #sidebar nav button:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #FFFFFF !important;
        }

        #sidebar nav a.bg-green-50,
        #sidebar nav button.bg-green-50 {
            background: rgba(255, 255, 255, 0.13) !important;
            color: #FFFFFF !important;
            border-left-color: var(--sipanda-accent);
        }

        #sidebar nav svg {
            width: 17px !important;
            height: 17px !important;
            flex: 0 0 17px;
            margin-right: 0 !important;
            color: currentColor;
        }

        #sidebar nav a span,
        #sidebar nav button span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #sidebar .sidebar-submenu {
            margin-left: 22px !important;
        }

        #sidebar .sidebar-submenu a {
            padding: 6px 8px !important;
            font-size: 12px !important;
        }

        #sidebar .sidebar-footer {
            padding: 10px 12px !important;
            background: rgba(0, 0, 0, 0.12) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: rgba(255, 255, 255, 0.72) !important;
        }

        #sidebar .sidebar-avatar {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            color: #FFFFFF;
            font-size: 11px;
            font-weight: 700;
        }

        header.app-topbar {
            height: 48px !important;
            padding: 0 20px !important;
            background: #FFFFFF !important;
            border-bottom: 1px solid var(--sipanda-input) !important;
            box-shadow: none !important;
            min-width: 0;
        }

        .topbar-search input {
            width: 240px !important;
            height: 32px !important;
            border: 1px solid var(--sipanda-input) !important;
            border-radius: 6px !important;
            background: #FFFFFF !important;
            padding: 0 10px !important;
            font-size: 12px !important;
            color: var(--sipanda-text) !important;
            box-shadow: none !important;
        }

        input:focus,
        select:focus,
        textarea:focus,
        button:focus-visible,
        a:focus-visible {
            outline: 2px solid rgba(6, 78, 59, .26) !important;
            outline-offset: 2px;
            border-color: var(--sipanda-primary) !important;
            box-shadow: none !important;
        }

        main h1,
        main h2 {
            color: var(--sipanda-primary) !important;
            font-size: 18px !important;
            line-height: 1.25 !important;
            font-weight: 700 !important;
            letter-spacing: 0 !important;
        }

        main h3,
        main h4 {
            color: var(--sipanda-text) !important;
            font-size: 12.5px !important;
            line-height: 1.35 !important;
            font-weight: 700 !important;
        }

        main p,
        main td,
        main th,
        main label,
        main li,
        main div {
            letter-spacing: 0;
        }

        main .bg-white,
        main .rounded-xl,
        main .rounded-lg {
            border-color: var(--sipanda-border) !important;
            box-shadow: none !important;
        }

        main .bg-white.border,
        main .bg-white.rounded-xl,
        main .bg-white.rounded-lg,
        main .rounded-lg.border,
        main .rounded-xl.border {
            background: var(--sipanda-card) !important;
            border: 1px solid var(--sipanda-border) !important;
            border-radius: 10px !important;
        }

        main .p-6 {
            padding: 14px !important;
        }

        main .p-4 {
            padding: 12px 14px !important;
        }

        main .mb-8,
        main .mb-6 {
            margin-bottom: 14px !important;
        }

        main .gap-6 {
            gap: 14px !important;
        }

        main .gap-4,
        main .gap-3 {
            gap: 12px !important;
        }

        main .text-2xl {
            font-size: 18px !important;
        }

        main .text-xl {
            font-size: 28px !important;
            line-height: 1.05 !important;
        }

        main .text-3xl {
            font-size: 28px !important;
        }

        main .text-sm {
            font-size: 12px !important;
        }

        main .text-xs {
            font-size: 10.5px !important;
        }

        input,
        select,
        textarea {
            border: 1px solid var(--sipanda-input) !important;
            border-radius: 6px !important;
            background: #FFFFFF !important;
            color: var(--sipanda-text) !important;
            font-size: 12px !important;
            min-width: 0;
        }

        input,
        select {
            min-height: 32px;
        }

        textarea {
            min-height: 86px;
        }

        button,
        a[class*="bg-green-"],
        a[class*="bg-emerald-"],
        a[class*="bg-indigo-"],
        a[class*="bg-slate-7"],
        button[class*="bg-green-"],
        button[class*="bg-emerald-"],
        button[class*="bg-indigo-"] {
            border-radius: 6px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
        }

        main a[class*="bg-green-"],
        main a[class*="bg-emerald-"],
        main a[class*="bg-indigo-"],
        main button[class*="bg-green-"],
        main button[class*="bg-emerald-"],
        main button[class*="bg-indigo-"] {
            background: var(--sipanda-primary) !important;
            color: #FFFFFF !important;
            min-height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        main a[class*="bg-green-"]:hover,
        main a[class*="bg-emerald-"]:hover,
        main a[class*="bg-indigo-"]:hover,
        main button[class*="bg-green-"]:hover,
        main button[class*="bg-emerald-"]:hover,
        main button[class*="bg-indigo-"]:hover {
            background: var(--sipanda-primary-hover) !important;
        }

        main table {
            width: 100%;
            min-width: 720px;
            border-collapse: collapse;
            color: var(--sipanda-text);
            font-size: 12px !important;
        }

        main .overflow-x-auto {
            max-width: 100%;
            overflow-x: auto;
        }

        main thead {
            background: var(--sipanda-soft) !important;
        }

        main th {
            padding: 9px 12px !important;
            color: var(--sipanda-secondary) !important;
            font-size: 10.5px !important;
            text-transform: uppercase;
            font-weight: 700 !important;
            white-space: nowrap;
        }

        main td {
            padding: 9px 12px !important;
            color: var(--sipanda-text);
            border-bottom: 1px solid #F7F9F7;
            vertical-align: middle;
        }

        main tr:hover {
            background: rgba(6, 78, 59, 0.035);
        }

        main span.rounded-full,
        main .rounded-full[class*="bg-"] {
            border-radius: 999px !important;
            padding: 2px 7px !important;
            font-size: 10.5px !important;
            font-weight: 600 !important;
        }

        main .bg-green-100,
        main .bg-emerald-100,
        main .bg-emerald-50 {
            background: var(--sipanda-success-bg) !important;
            color: var(--sipanda-success-text) !important;
        }

        main .bg-amber-100,
        main .bg-amber-50,
        main .bg-yellow-100 {
            background: var(--sipanda-warning-bg) !important;
            color: var(--sipanda-warning-text) !important;
        }

        main .bg-red-100,
        main .bg-red-50 {
            background: var(--sipanda-danger-bg) !important;
            color: var(--sipanda-danger-text) !important;
        }

        main .bg-purple-100,
        main .bg-violet-100,
        main .bg-indigo-100 {
            background: var(--sipanda-purple-bg) !important;
            color: var(--sipanda-purple-text) !important;
        }

        main .bg-slate-100,
        main .bg-gray-100,
        main .bg-slate-50,
        main .bg-gray-50 {
            background: var(--sipanda-neutral-bg) !important;
            color: var(--sipanda-neutral-text) !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .28);
            border-radius: 10px;
        }

        @media (max-width: 1023px) {
            #sidebar {
                position: fixed !important;
                inset: 0 auto 0 0 !important;
                transform: translateX(-100%);
            }

            #sidebar:not(.-translate-x-full) {
                transform: translateX(0);
            }

            header.app-topbar {
                padding: 0 12px !important;
            }

            #app-content {
                padding: 14px 16px !important;
            }
        }

        @media (max-width: 767px) {
            #app-content {
                padding: 12px !important;
            }

            .topbar-search {
                display: none !important;
            }

            main .grid {
                grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
            }

            main form.flex,
            main .flex.flex-wrap {
                align-items: stretch !important;
            }

            main form input,
            main form select,
            main form button,
            main form a {
                width: 100%;
                min-height: 38px;
            }

            main .text-xl,
            main .text-3xl {
                font-size: 24px !important;
            }
        }

        @media (max-width: 399px) {
            #app-content {
                padding: 10px !important;
            }

            main .text-xl,
            main .text-3xl {
                font-size: 22px !important;
            }
        }
    </style>
</head>

<body class="antialiased">

    <div class="flex h-screen overflow-hidden" id="app-layout">


        {{-- SIDEBAR --}}
        @include('layouts.partials.sidebar')
        <!-- Overlay for mobile sidebar -->
        <div id="overlay" onclick="closeSidebar()" class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"></div>

        <!-- MAIN WRAPPER (HEADER + CONTENT + FOOTER) -->
        <div id="app-main" class="flex flex-col flex-1 w-full overflow-hidden">

            {{-- HEADER --}}
            @include('layouts.partials.header')
            <!-- ==========================================
                 MAIN CONTENT AREA
                 ========================================== -->
            <main id="app-content" class="flex-1">
                @yield('content')
                @stack('scripts')

            </main>

            {{-- FOOTER --}}
            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logout-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div id="modal-overlay" class="fixed inset-0 transition-opacity" onclick="hideLogoutModal()">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Logout Confirmation</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to logout? You'll need to sign in again to access your account.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                    <button type="button"
                        onclick="document.getElementById('logout-form').submit()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Logout
                    </button>
                    <button type="button"
                        onclick="hideLogoutModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to Handle Mobile Sidebar Toggle and Logout Modal -->
    <script>
        function toggleSidebar(forceOpen = null) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const trigger = document.querySelector('[aria-controls="sidebar"]');

            const shouldOpen = forceOpen === null ? sidebar.classList.contains('-translate-x-full') : forceOpen;
            sidebar.classList.toggle('-translate-x-full', !shouldOpen);
            overlay.classList.toggle('hidden', !shouldOpen);

            if (trigger) {
                trigger.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
            }
        }

        function closeSidebar() {
            toggleSidebar(false);
        }

        function toggleSidebarDropdown(id, arrowId = null) {
            const dropdown = document.getElementById(id);
            const arrow = arrowId ? document.getElementById(arrowId) : null;

            if (!dropdown) return;

            dropdown.classList.toggle('hidden');

            if (arrow) {
                arrow.classList.toggle('rotate-180');
            }
        }

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('dropdown-menu');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            const profile = document.getElementById('user-profile');
            const dropdown = document.getElementById('dropdown-menu');

            if (profile && dropdown && !profile.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

        document.querySelectorAll('#sidebar nav a').forEach((item) => {
            item.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });

        function openLogoutModal() {
            const modal = document.getElementById('logout-modal');
            modal.classList.remove('hidden');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function hideLogoutModal() {
            const modal = document.getElementById('logout-modal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    </script>


    <script>
        document.documentElement.style.visibility = 'visible';
    </script>

</body>

</html>
