<!-- admin/partials/top-nav.blade.php -->
<header class="sticky top-0 z-40 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-800">
    <div class="flex items-center justify-between px-4 h-16">
        <!-- Left side -->
        <div class="flex items-center gap-4">
            <!-- Mobile menu toggle -->
            <button @click="$dispatch('toggle-sidebar')" 
                    class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <i class="fas fa-bars text-gray-700 dark:text-gray-300 text-lg"></i>
            </button>
            
            <!-- Page Title -->
            <h1 class="text-lg font-semibold text-gray-900 dark:text-white hidden sm:block">
                @yield('page-title', 'Dashboard')
            </h1>
        </div>
        
        <!-- Right side -->
        <div class="flex items-center gap-3">
            <!-- Search -->
            @include('admin.partials.search')
            
            <!-- Divider -->
            <div class="w-px h-8 bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>
            
            <!-- Notifications -->
            @include('admin.partials.notifications')
            
            <!-- Quick Actions -->
            @include('admin.partials.quick-actions')
            
            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-8 h-8 rounded-full ring-2 ring-indigo-500/20">
                    <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ Auth::user()->name }}
                    </span>
                    <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400 hidden sm:block"></i>
                </button>
                
                <!-- Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-800 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-800">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="py-1">
                        {{-- <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <i class="fas fa-user-circle w-5 text-center text-gray-400"></i>
                            Profile
                        </a> --}}
                        {{-- <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <i class="fas fa-cog w-5 text-center text-gray-400"></i>
                            Settings
                        </a> --}}
                        <div class="border-t border-gray-200 dark:border-gray-800 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Breadcrumb -->
@if (isset($breadcrumbs))
    <div class="bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
        <div class="px-4 py-2">
            @include('admin.partials.breadcrumb', ['breadcrumbs' => $breadcrumbs])
        </div>
    </div>
@endif