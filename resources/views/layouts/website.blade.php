{{-- resources/views/layouts/website.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'SaaS Platform'))</title>
    
    <!-- Meta Tags -->
    @yield('meta')
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-gray-900 min-h-full flex flex-col">
    
    <!-- Header -->
    <x-website.header />
    
    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <x-website.footer />
    
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>