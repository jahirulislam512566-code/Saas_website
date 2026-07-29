{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Title --}}
    <title>{{ $title ?? 'AgencyPro - Digital Agency' }}</title>
    
    {{-- Meta Tags --}}
    <meta name="description" content="{{ $description ?? 'We build digital experiences that drive growth for forward-thinking brands.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'digital agency, web design, development, strategy, marketing' }}">
    <meta name="robots" content="{{ $robots ?? 'index, follow' }}">
    <meta name="author" content="AgencyPro">
    <meta name="theme-color" content="#6c5ce7">
    
    {{-- Canonical URL --}}
    @if(isset($canonical))
        <link rel="canonical" href="{{ $canonical }}">
    @else
        <link rel="canonical" href="{{ url()->current() }}">
    @endif
    
    {{-- Alternate Languages --}}
    @if(isset($alternates))
        @foreach($alternates as $lang => $url)
            <link rel="alternate" hreflang="{{ $lang }}" href="{{ $url }}">
        @endforeach
    @endif
    
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? 'AgencyPro - Digital Agency') }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($description ?? 'We build digital experiences that drive growth for forward-thinking brands.') }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/og-image.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="{{ $ogUrl ?? url()->current() }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:site_name" content="{{ $ogSiteName ?? 'AgencyPro' }}">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="{{ $twitterCard ?? 'summary_large_image' }}">
    <meta name="twitter:site" content="{{ $twitterSite ?? '@AgencyPro' }}">
    <meta name="twitter:creator" content="{{ $twitterCreator ?? '@AgencyPro' }}">
    <meta name="twitter:title" content="{{ $twitterTitle ?? ($title ?? 'AgencyPro - Digital Agency') }}">
    <meta name="twitter:description" content="{{ $twitterDescription ?? ($description ?? 'We build digital experiences that drive growth for forward-thinking brands.') }}">
    <meta name="twitter:image" content="{{ $twitterImage ?? asset('images/twitter-image.jpg') }}">
    
    {{-- Schema Markup --}}
    @yield('schema')
    
    {{-- Preconnect for Performance --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('images/safari-pinned-tab.svg') }}" color="#6c5ce7">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    {{-- Fonts --}}
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- Styles --}}
    @vite(['resources/css/app.css'])
    
    {{-- Additional Styles --}}
    @stack('styles')
    
    {{-- Livewire Styles --}}
    @livewireStyles
    
    {{-- Inline Critical CSS (Optional) --}}
    @if(isset($criticalCss))
        <style>{{ $criticalCss }}</style>
    @endif
</head>
<body class="font-sans antialiased bg-white text-[#0a0a0a] min-h-screen flex flex-col {{ $bodyClass ?? '' }}">
    {{-- Skip to content link for accessibility --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-[#6c5ce7] focus:text-white focus:rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6c5ce7]/50">
        {{ __('Skip to main content') }}
    </a>

    {{-- Preloader (Optional) --}}
    @if(isset($showPreloader) && $showPreloader)
        <div id="preloader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white transition-opacity duration-500">
            <div class="flex flex-col items-center gap-4">
                <div class="w-16 h-16 border-4 border-[#6c5ce7]/20 border-t-[#6c5ce7] rounded-full animate-spin"></div>
                <span class="text-sm font-medium text-[#4a4a4a]">Loading...</span>
            </div>
        </div>
    @endif

    {{-- Header --}}
    @if(!isset($hideHeader) || !$hideHeader)
        <x-layout.header />
        <x-layout.mobile-menu />
        <x-layout.search />
    @endif

    {{-- Main Content --}}
    <main id="main-content" class="flex-grow pt-[70px]">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    {{-- Footer --}}
    @if(!isset($hideFooter) || !$hideFooter)
        <x-layout.footer />
    @endif

    {{-- Cookie Consent Banner --}}
    @if(isset($showCookieConsent) && $showCookieConsent)
        @include('partials.cookie-consent')
    @endif

    {{-- Scroll to Top Button --}}
    @if(isset($showScrollTop) && $showScrollTop)
        <button id="scroll-top" class="fixed bottom-8 right-8 z-50 p-3 bg-[#6c5ce7] text-white rounded-full shadow-lg hover:bg-[#5a4bd1] transition-all duration-300 opacity-0 invisible hover:scale-110" aria-label="Scroll to top">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>
    @endif

    {{-- Scripts --}}
    @vite(['resources/js/app.js'])
    
    {{-- ❌ REMOVE THIS - Already loaded via Vite --}}
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script> --}}
    
    {{-- Livewire Scripts --}}
    @livewireScripts
    
    {{-- Additional Scripts --}}
    @stack('scripts')

    {{-- Inline Scripts --}}
    @if(isset($inlineScripts))
        <script>{!! $inlineScripts !!}</script>
    @endif

    {{-- Scroll to Top Script --}}
    @if(isset($showScrollTop) && $showScrollTop)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const scrollBtn = document.getElementById('scroll-top');
                if (!scrollBtn) return;
                
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 300) {
                        scrollBtn.classList.remove('opacity-0', 'invisible');
                        scrollBtn.classList.add('opacity-100', 'visible');
                    } else {
                        scrollBtn.classList.add('opacity-0', 'invisible');
                        scrollBtn.classList.remove('opacity-100', 'visible');
                    }
                });
                
                scrollBtn.addEventListener('click', function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
        </script>
    @endif
</body>
</html>