<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SIPANDA-KPH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Portal Login Sistem Informasi Kepegawaian KPH Perhutani')">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="SIPANDA-KPH | @yield('title', 'Login')">
    <meta property="og:description" content="@yield('meta_description', 'Portal Login Sistem Informasi Kepegawaian KPH Perhutani')">
    <meta property="og:image" content="{{ asset('assets/images/avatar.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/avatar.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sipanda-primary: #064E3B;
            --sipanda-primary-hover: #055040;
            --sipanda-page: #F6F8F5;
            --sipanda-text: #1F2933;
            --sipanda-secondary: #667085;
            --sipanda-border: #E0E8DF;
            --sipanda-input: #DDE7DC;

            /* Design System variables */
            --ui-primary: #064E3B;
            --ui-primary-hover: #055040;
            --ui-primary-soft: #F1F5F1;
            --ui-bg: #F6F8F5;
            --ui-surface: #FFFFFF;
            --ui-border: #E0E8DF;
            --ui-text-primary: #1F2933;
            --ui-text-secondary: #667085;
            --ui-success: #059669;
            --ui-success-soft: #ECFDF5;
            --ui-warning: #D97706;
            --ui-warning-soft: #FFFBEB;
            --ui-danger: #DC2626;
            --ui-danger-soft: #FEF2F2;
            --ui-info: #2563EB;
            --ui-info-soft: #EFF6FF;
            --ui-muted: #9CA3AF;

            --ui-space-4: 0.25rem;
            --ui-space-8: 0.5rem;
            --ui-space-12: 0.75rem;
            --ui-space-16: 1rem;
            --ui-space-20: 1.25rem;
            --ui-space-24: 1.5rem;
            --ui-space-32: 2rem;
            --ui-space-40: 2.5rem;
            --ui-space-48: 3rem;
            --ui-space-64: 4rem;

            --ui-radius-sm: 0.125rem;
            --ui-radius-md: 0.375rem;
            --ui-radius-lg: 0.5rem;
            --ui-radius-xl: 0.75rem;

            --ui-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --ui-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --ui-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--sipanda-page) !important;
            color: var(--sipanda-text);
            font-size: 13px;
            overflow-x: hidden;
        }

        .bg-white {
            border: 1px solid var(--sipanda-border) !important;
            border-radius: 10px !important;
            box-shadow: none !important;
        }

        .rounded-2xl,
        .rounded-xl {
            border-radius: 10px !important;
        }

        h1 {
            color: var(--sipanda-primary) !important;
            font-size: 18px !important;
            line-height: 1.25;
        }

        p,
        label,
        a {
            font-size: 12px !important;
        }

        input {
            min-height: 36px;
            border: 1px solid var(--sipanda-input) !important;
            border-radius: 6px !important;
            background: #FFFFFF !important;
            color: var(--sipanda-text);
            font-size: 12px !important;
            box-shadow: none !important;
        }

        input:focus,
        button:focus-visible,
        a:focus-visible {
            outline: 2px solid rgba(6, 78, 59, .26) !important;
            outline-offset: 2px;
            border-color: var(--sipanda-primary) !important;
            box-shadow: none !important;
        }

        button,
        .bg-green-800 {
            min-height: 36px;
            border-radius: 6px !important;
            background: var(--sipanda-primary) !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            box-shadow: none !important;
        }

        button:hover,
        .bg-green-800:hover {
            background: var(--sipanda-primary-hover) !important;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4">

    {{ $slot }}

    <!-- Toast container and component -->
    <div id="ui-toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-3 pointer-events-none max-w-sm w-full"></div>
    <x-ui.toast />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>
