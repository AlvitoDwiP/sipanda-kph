<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Sistem Informasi Kepegawaian KPH Perhutani (SIPANDA-KPH)')">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="SIPANDA-KPH | @yield('title', 'Dashboard')">
    <meta property="og:description" content="@yield('meta_description', 'Sistem Informasi Kepegawaian KPH Perhutani (SIPANDA-KPH)')">
    <meta property="og:image" content="{{ asset('assets/images/avatar.png') }}">
    <style>html{visibility:hidden}</style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/avatar.png') }}">
    
    <title>@yield('title', 'Dashboard | Web Kepegawaian')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Scrollbar styles to match design system */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--ui-border);
            border-radius: var(--ui-radius-lg);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--ui-muted);
        }
        
        /* Modern Focus Rings */
        button:focus-visible,
        a:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 2px solid var(--ui-primary) !important;
            outline-offset: 2px;
        }

        /* Generic table overrides for modules without direct component usage */
        main table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        main th {
            font-weight: 700;
            color: var(--ui-text-secondary);
            border-bottom: 1px solid var(--ui-border);
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.05em;
            padding: 10px 14px;
            text-align: left;
        }
        main td {
            border-bottom: 1px solid var(--ui-border);
            color: var(--ui-text-primary);
            padding: 10px 14px;
            vertical-align: middle;
        }
        main tr:hover td {
            background-color: var(--ui-primary-soft);
        }
    </style>
</head>

<body class="antialiased bg-ui-bg text-ui-text-primary font-sans text-xs sm:text-sm">

    <div 
        id="app-layout"
        x-data="{ 
            sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true', 
            mobileSidebarOpen: false 
        }"
        x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebar_collapsed', val))"
        class="flex h-screen overflow-hidden w-full relative"
    >
        {{-- SIDEBAR --}}
        @include('layouts.partials.sidebar')
        
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div 
            id="overlay" 
            x-show="mobileSidebarOpen" 
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="mobileSidebarOpen = false" 
            class="fixed inset-0 z-40 bg-black/40 lg:hidden"
            style="display: none;"
        ></div>

        <!-- MAIN CONTENT AREA WRAPPER -->
        <div id="app-main" class="flex flex-col flex-1 w-full overflow-x-hidden overflow-y-auto min-w-0 custom-scrollbar">

            {{-- NAVBAR TOPBAR --}}
            @include('layouts.partials.header')
            
            <!-- MAIN CONTENT AREA -->
            <main id="app-content" class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 focus:outline-none">
                @yield('content')
                @stack('scripts')
            </main>

            {{-- FOOTER --}}
            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <x-ui.modal name="logout-confirm" title="Konfirmasi Keluar" maxWidth="sm">
        <div class="flex items-start gap-3.5">
            <div class="p-2.5 rounded-ui-lg bg-ui-danger-soft text-ui-danger shrink-0 border border-ui-danger/10">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div class="min-w-0">
                <h4 class="text-xs sm:text-sm font-bold text-ui-text-primary leading-tight">Yakin ingin keluar?</h4>
                <p class="text-[11px] sm:text-xs text-ui-text-secondary mt-1 leading-normal">Anda akan keluar dari sesi SIPANDA-KPH dan perlu masuk kembali untuk mengakses data Anda.</p>
            </div>
        </div>
        
        <x-slot name="footer">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <x-ui.button variant="ghost" size="sm" @click="$dispatch('close-modal', 'logout-confirm')">Batal</x-ui.button>
            <x-ui.button variant="danger" size="sm" onclick="document.getElementById('logout-form').submit()">Keluar</x-ui.button>
        </x-slot>
    </x-ui.modal>

    <!-- Global Toast Container Stack -->
    <div id="ui-toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-3 pointer-events-none max-w-sm w-full"></div>
    <x-ui.toast />

    <script>
        document.documentElement.style.visibility = 'visible';
        
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Trigger Lucide updates on content changes
        window.addEventListener('content-updated', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

</body>

</html>
