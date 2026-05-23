<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Travel Planner AI') — Wanderly</title>
    <meta name="description" content="@yield('description', 'Plan your perfect trip with AI-powered itinerary management.')">
    <link rel="icon" type="image/png" href="{{ asset('Wanderly.png') }}">

    <script>
        (function () {
            try {
                var storageKey = 'wanderly_theme';
                var savedTheme = localStorage.getItem(storageKey);
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                var theme = savedTheme || (prefersDark ? 'dark' : 'light');

                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.style.colorScheme = theme === 'dark' ? 'dark' : 'light';
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'light');
                document.documentElement.style.colorScheme = 'light';
            }
        })();
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Anton&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Vite JS --}}
    @vite(['resources/js/app.js'])

    @stack('styles')
</head>
<body class="{{ request()->routeIs('home') ? 'home-page' : '' }}">

    {{-- Navbar --}}
    @include('layouts.navbar')

    {{-- Toast Notifications --}}
    @include('components.toast')

    {{-- Main Content --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    @stack('scripts')
</body>
</html>

