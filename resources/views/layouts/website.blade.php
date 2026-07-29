<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'Buildora'))</title>
    <meta name="description" content="@yield('description', 'Build beautiful websites without coding. Professional website builder with hosting, SEO, and security.')">
    
    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og:title', config('app.name'))">
    <meta property="og:description" content="@yield('og:description', 'Build beautiful websites without coding.')">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter:title', config('app.name'))">
    <meta name="twitter:description" content="@yield('twitter:description', 'Build beautiful websites without coding.')">
    <meta name="twitter:image" content="{{ asset('images/twitter-image.jpg') }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    @stack('styles')
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased bg-white">
    <div id="app">
        {{-- Navbar --}}
        @include('website.components.navbar')
        
        {{-- Main Content --}}
        <main>
            @yield('content')
        </main>
        
        {{-- Footer --}}
        @include('website.components.footer')
    </div>
    
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @stack('scripts')
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
   
</body>
</html>