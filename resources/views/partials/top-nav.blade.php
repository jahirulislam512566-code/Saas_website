<header class="bg-white border-b border-gray-200 sticky top-0 z-40">
    <div class="flex items-center justify-between h-16 px-6">
        <!-- Left: Breadcrumb -->
        <div class="flex items-center space-x-4">
            <button type="button" class="lg:hidden text-gray-500 hover:text-gray-700">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    @yield('breadcrumb', '')
                </ol>
            </nav>
        </div>
        
        <!-- Right: Actions -->
        <div class="flex items-center space-x-3">
            <!-- Search -->
            <button type="button" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-search text-lg"></i>
            </button>
            
            <!-- Notifications -->
            <div class="relative">
                <button type="button" class="text-gray-500 hover:text-gray-700 relative">
                    <i class="fas fa-bell text-lg"></i>
                    @if($unreadNotifications ?? 0 > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                            {{ $unreadNotifications }}
                        </span>
                    @endif
                </button>
            </div>
            
            <!-- Quick Actions -->
            <div class="relative">
                <button type="button" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-plus-circle text-lg"></i>
                </button>
            </div>
        </div>
    </div>
</header>