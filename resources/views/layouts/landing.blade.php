<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SaaS') }} – Build Beautiful Websites</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

    <!-- Vite assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">
    <div class="flex flex-col min-h-screen">
        <!-- Navbar (inline in layout or as partial) -->
        @include('landing.sections.navbar') <!-- we can create a navbar partial if needed, but we'll embed it in layout for simplicity -->

        <!-- Main content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer (partial) -->
        @include('landing.sections.footer')
    </div>

    <!-- Alpine.js (if not bundled via Vite) -->
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
</body>
</html>