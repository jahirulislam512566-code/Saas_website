{{-- resources/views/layouts/guest.blade.php --}}
{{-- Minimal layout for guest users (landing pages, marketing pages, etc.) --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'AgencyPro - Digital Agency' }}</title>
    <meta name="description" content="{{ $description ?? 'We build digital experiences that drive growth for forward-thinking brands.' }}">
    
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? 'AgencyPro - Digital Agency') }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($description ?? 'We build digital experiences that drive growth for forward-thinking brands.') }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/og-image.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="AgencyPro">
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@AgencyPro">
    <meta name="twitter:title" content="{{ $title ?? 'AgencyPro - Digital Agency' }}">
    <meta name="twitter:description" content="{{ $description ?? 'We build digital experiences that drive growth for forward-thinking brands.' }}">
    <meta name="twitter:image" content="{{ $twitterImage ?? asset('images/twitter-image.jpg') }}">
    
    @yield('schema')
    
    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- Styles --}}
    @vite(['resources/css/app.css'])
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-[#0a0a0a] min-h-screen flex flex-col">
    {{-- Simple Header for Guest Pages --}}
    <header class="py-4 border-b border-gray-100 bg-white/95 backdrop-blur-sm fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline font-bold text-2xl text-[#0a0a0a]">
                    <span class="text-3xl text-[#6c5ce7]">⚡</span>
                    <span>Agency<span class="text-[#6c5ce7]">Pro</span></span>
                </a>
                <div class="flex items-center gap-4">
                    @if(isset($actions))
                        {{ $actions }}
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-[#6c5ce7] rounded-lg hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30 no-underline">
                            Get Started
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content with padding for fixed header --}}
    <main class="flex-grow pt-[72px]">
        {{ $slot }}
    </main>

    {{-- Simple Footer --}}
    <footer class="bg-[#0a0a0a] text-white/60 py-8 mt-auto">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-sm">
                <p>&copy; {{ date('Y') }} AgencyPro. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="{{ route('pages.privacy') }}" class="hover:text-white transition-colors no-underline">Privacy</a>
                    <a href="{{ route('pages.terms') }}" class="hover:text-white transition-colors no-underline">Terms</a>
                    <a href="{{ route('pages.faq') }}" class="hover:text-white transition-colors no-underline">FAQ</a>
                </div>
            </div>
        </div>
    </footer>

    @vite(['resources/js/app.js'])
   
    @stack('scripts')
</body>
</html>