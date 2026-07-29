{{-- resources/views/components/layout/navbar.blade.php --}}
@props(['active' => 'home'])

@php
    $navigation = [
        'home' => ['label' => 'Home', 'route' => 'home', 'icon' => '🏠'],
      //   'about' => ['label' => 'About', 'route' => 'pages.about', 'icon' => '👤'],
        'services' => [
            'label' => 'Services',
            'route' => 'services.index',
            'dropdown' => [
                'website-design' => ['label' => 'Website Design', 'route' => 'services.web-design', 'icon' => '🎨'],
                'web-development' => ['label' => 'Web Development', 'route' => 'services.web-development', 'icon' => '💻'],
                'ecommerce' => ['label' => 'E-Commerce', 'route' => 'services.ecommerce', 'icon' => '🛒'],
                'seo' => ['label' => 'SEO', 'route' => 'services.seo', 'icon' => '🔍'],
                'uiux-design' => ['label' => 'UI/UX Design', 'route' => 'services.uiux-design', 'icon' => '✨'],
                'maintenance' => ['label' => 'Maintenance', 'route' => 'services.maintenance', 'icon' => '🔧'],
                'hosting' => ['label' => 'Hosting', 'route' => 'services.hosting', 'icon' => '☁️'],
            ]
        ],
        'portfolio' => ['label' => 'Portfolio', 'route' => 'portfolio.index', 'icon' => '💼'],
        'pricing' => ['label' => 'Pricing', 'route' => 'pricing.index', 'icon' => '💰'],
        'blog' => ['label' => 'Blog', 'route' => 'blog.index', 'icon' => '📝'],
        'resources' => [
            'label' => 'Resources',
            'route' => '#',
            'icon' => '📚',
            'dropdown' => [
                'faq' => ['label' => 'FAQ', 'route' => 'pages.faq', 'icon' => '❓'],
                'documentation' => ['label' => 'Documentation', 'route' => 'pages.documentation', 'icon' => '📖'],
                'case-studies' => ['label' => 'Case Studies', 'route' => 'pages.case-studies', 'icon' => '📊'],
                'downloads' => ['label' => 'Downloads', 'route' => 'pages.downloads', 'icon' => '📥'],
            ]
        ],
      //   'careers' => ['label' => 'Careers', 'route' => 'pages.careers', 'icon' => '💼'],
        'contact' => [
            'label' => 'Contact',
            'route' => 'contact.index',
            'icon' => '📧',
            'dropdown' => [
                'contact-us' => ['label' => 'Contact Us', 'route' => 'contact.index', 'icon' => '📧'],
                'get-quote' => ['label' => 'Get a Quote', 'route' => 'contact.quote', 'icon' => '📄'],
                'book-meeting' => ['label' => 'Book a Meeting', 'route' => 'contact.book-meeting', 'icon' => '📅'],
            ]
        ],
    ];
    
    // Auth items (shown conditionally)
    $authItems = [
        'login' => ['label' => 'Login', 'route' => 'login'],
        'register' => ['label' => 'Register', 'route' => 'register'],
    ];
@endphp

<nav class="main-nav hidden lg:block" x-data="{ openDropdown: null }">
    <ul class="flex items-center gap-1 list-none m-0 p-0">
        {{-- Main Navigation Items --}}
        @foreach($navigation as $key => $item)
            @if(isset($item['dropdown']))
                {{-- Dropdown Item --}}
                <li class="relative" 
                    @mouseenter="openDropdown = '{{ $key }}'" 
                    @mouseleave="openDropdown = null"
                    x-data="{ dropdownOpen: false }"
                    x-init="$watch('openDropdown', value => dropdownOpen = value === '{{ $key }}')">
                    <button 
                        class="flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors rounded-lg hover:bg-[#6c5ce7]/5"
                        x-on:click="dropdownOpen = !dropdownOpen"
                        x-bind:aria-expanded="dropdownOpen"
                        aria-haspopup="true">
                        {{-- <span class="text-lg">{{ $item['icon'] }}</span> --}}
                        <span>{{ $item['label'] }}</span>
                        <svg class="w-4 h-4 transition-transform duration-200" 
                             x-bind:class="{ 'rotate-180': dropdownOpen }" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    {{-- Dropdown Menu --}}
                    <div x-show="dropdownOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute top-full left-0 mt-1 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-50"
                         x-cloak>
                        @foreach($item['dropdown'] as $subKey => $subItem)
                            <a href="{{ route($subItem['route']) }}" 
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:bg-[#6c5ce7]/5 hover:text-[#6c5ce7] transition-colors no-underline {{ active($subItem['route']) ? 'bg-[#6c5ce7]/10 text-[#6c5ce7]' : '' }}">
                                <span class="text-lg">{{ $subItem['icon'] }}</span>
                                {{ $subItem['label'] }}
                            </a>
                        @endforeach
                    </div>
                </li>
            @else
                {{-- Regular Link --}}
                <li>
                    <a href="{{ route($item['route']) }}" 
                       class="flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors rounded-lg hover:bg-[#6c5ce7]/5 {{ active($item['route']) ? 'text-[#6c5ce7] bg-[#6c5ce7]/10' : '' }}">
                        {{-- <span class="text-lg">{{ $item['icon'] }}</span> --}}
                        {{ $item['label'] }}
                    </a>
                </li>
            @endif
        @endforeach
          
        
        {{-- Auth Items --}}
        <li class="ml-2 border-l border-gray-200 pl-2 flex items-center gap-1">
            @auth
                {{-- User Dropdown --}}
                <div class="relative" 
                     @mouseenter="openDropdown = 'user'" 
                     @mouseleave="openDropdown = null"
                     x-data="{ dropdownOpen: false }"
                     x-init="$watch('openDropdown', value => dropdownOpen = value === 'user')">
                    <button class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors rounded-lg hover:bg-[#6c5ce7]/5">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#6c5ce7] to-[#a78bfa] flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" 
                             x-bind:class="{ 'rotate-180': dropdownOpen }" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="dropdownOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute right-0 top-full mt-1 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-50"
                         x-cloak>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:bg-[#6c5ce7]/5 hover:text-[#6c5ce7] transition-colors no-underline">
                            <span>👤</span> Profile
                        </a>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:bg-[#6c5ce7]/5 hover:text-[#6c5ce7] transition-colors no-underline">
                             Dashboard
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors bg-transparent border-none cursor-pointer text-left">
                                 Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                @foreach($authItems as $key => $item)
                    <a href="{{ route($item['route']) }}" 
                       class="flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium {{ $key === 'register' ? 'text-white bg-[#6c5ce7] hover:bg-[#5a4bd1] rounded-lg shadow-lg shadow-[#6c5ce7]/30' : 'text-[#4a4a4a] hover:text-[#6c5ce7]' }} transition-colors no-underline">
                        {{-- <span class="text-lg">{{ $item['icon'] }}</span> --}}
                        {{ $item['label'] }}
                    </a>
                @endforeach
            @endauth
        </li>
    </ul>
</nav>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush