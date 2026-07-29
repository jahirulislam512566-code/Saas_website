{{-- resources/views/layouts/blank.blade.php --}}
{{-- Blank layout with no header, footer, or styling wrappers - for modals, embeds, or custom pages --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'AgencyPro' }}</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Styles --}}
    @vite(['resources/css/app.css'])
    
    {{-- Additional Styles --}}
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-[#0a0a0a]">
    {{-- No header, no footer, no wrapper --}}
    {{ $slot }}
    
    {{-- Scripts --}}
    @vite(['resources/js/app.js'])

    @stack('scripts')
</body>
</html>