{{-- resources/views/components/layout/header.blade.php --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-black/5 h-[70px] flex items-center transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex items-center justify-between gap-8">
            {{-- Logo --}}
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline font-bold text-2xl text-[#0a0a0a]">
                    <span class="text-3xl text-[#6c5ce7]">⚡</span>
                    <span class="logo-text">Agency<span class="text-[#6c5ce7]">Pro</span></span>
                </a>
            </div>

            {{-- Navigation --}}
            <x-layout.navbar />

            {{-- Header Actions --}}
            <div class="flex items-center gap-3 ml-auto">
                {{-- Search --}}
                <button 
                    class="search-toggle p-2 text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors cursor-pointer bg-transparent border-none rounded-lg hover:bg-[#6c5ce7]/5" 
                    aria-label="Toggle search"
                    x-data
                    @click="$dispatch('open-search')"
                >
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                    </svg>
                </button>
                
                {{-- Mobile Menu Toggle --}}
                <button class="flex-col gap-1 p-2 bg-transparent border-none cursor-pointer lg:hidden mobile-menu-toggle" aria-label="Toggle mobile menu">
                    <span class="block w-6 h-0.5 bg-[#1a1a1a] transition-all duration-300 rounded"></span>
                    <span class="block w-6 h-0.5 bg-[#1a1a1a] transition-all duration-300 rounded"></span>
                    <span class="block w-6 h-0.5 bg-[#1a1a1a] transition-all duration-300 rounded"></span>
                </button>
            </div>
        </div>
    </div>
</header>