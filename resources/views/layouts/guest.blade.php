<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SIPANDA-KPH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/avatar.png') }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

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

</body>

</html>
