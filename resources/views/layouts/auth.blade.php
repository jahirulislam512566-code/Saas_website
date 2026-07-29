{{-- resources/views/layouts/auth.blade.php --}}
{{-- Authentication layout for login, register, password reset pages --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'AgencyPro - Authentication' }}</title>
    <meta name="description" content="Authentication for AgencyPro - Digital Agency">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    {{-- Styles --}}
    @vite(['resources/css/app.css'])
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gradient-to-br from-[#f8f7ff] to-white min-h-screen flex flex-col">
    {{-- Auth Header --}}
    <div class="flex-shrink-0 py-6 border-b border-gray-100 bg-white/50 backdrop-blur-sm">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline font-bold text-2xl text-[#0a0a0a]">
                    <i class="fas fa-bolt text-3xl text-[#6c5ce7]"></i>
                    <span>Agency<span class="text-[#6c5ce7]">Pro</span></span>
                </a>
            </div>
        </div>
    </div>

    {{-- Auth Content --}}
    <main class="flex-grow flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            {{-- Auth Card --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                {{-- Header --}}
                @if(isset($authHeader))
                    <div class="text-center mb-8">
                        @if(isset($icon))
                            <div class="text-4xl mb-3">{{ $icon }}</div>
                        @endif
                        <h2 class="text-2xl font-bold text-[#0a0a0a]">{{ $authHeader }}</h2>
                        @if(isset($authSubheader))
                            <p class="text-sm text-[#4a4a4a] mt-1">{{ $authSubheader }}</p>
                        @endif
                    </div>
                @endif

                {{-- FIX: Content - Check if section exists first --}}
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif

                {{-- Auth Footer --}}
                @if(isset($authFooter))
                    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                        {{ $authFooter }}
                    </div>
                @endif
            </div>

            {{-- Back to Home --}}
            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="text-sm text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Home
                </a>
            </div>
        </div>
    </main>

    {{-- Auth Footer --}}
    <div class="flex-shrink-0 py-4 text-center text-sm text-[#4a4a4a]">
        <p>&copy; {{ date('Y') }} AgencyPro. All rights reserved.</p>
    </div>

    @vite(['resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>