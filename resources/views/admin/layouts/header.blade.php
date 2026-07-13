{{-- resources/views/admin/layouts/header.blade.php --}}
@props([
    'unreadNotifications' => 0,
    'notifications' => [],
])

<header class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
    <div class="flex items-center justify-between h-14 md:h-16 px-3 md:px-6">
        
        <!-- ===== LEFT SECTION ===== -->
        <div class="flex items-center min-w-0 flex-1">
            <!-- Mobile Menu Toggle -->
            <button @click="$dispatch('toggle-sidebar')" 
                    class="lg:hidden text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg p-2 transition-colors" 
                    aria-label="Toggle Sidebar">
                <i class="fas fa-bars text-lg md:text-xl"></i>
            </button>
            
            <!-- Desktop Toggle -->
            <button @click="$dispatch('toggle-sidebar')" 
                    class="hidden lg:block text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg p-2 transition-colors" 
                    aria-label="Toggle Sidebar">
                <i class="fas fa-chevron-left text-sm"></i>
            </button>
            
            <!-- Breadcrumb -->
            <nav class="hidden sm:flex ml-2 md:ml-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="text-gray-400 hover:text-gray-600 transition-colors" 
                           title="Dashboard">
                            <i class="fas fa-home text-sm md:text-base"></i>
                        </a>
                    </li>
                    @hasSection('breadcrumb')
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-300 text-xs mx-1 md:mx-2"></i>
                                @yield('breadcrumb')
                            </div>
                        </li>
                    @endif
                </ol>
            </nav>
            
            <!-- Page Title (Mobile) -->
            <div class="sm:hidden ml-2 truncate">
                <h1 class="text-sm font-semibold text-gray-800 truncate">@yield('title', 'Dashboard')</h1>
            </div>
        </div>
        
        <!-- ===== RIGHT SECTION ===== -->
        <div class="flex items-center space-x-1 md:space-x-3 flex-shrink-0">
            
            <!-- Search Button (Mobile) -->
            <button type="button" 
                    class="md:hidden text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg p-2 transition-colors"
                    @click="$dispatch('open-search')"
                    aria-label="Search">
                <i class="fas fa-search text-base"></i>
            </button>
            
            <!-- Search (Desktop) -->
            <div class="hidden md:flex items-center relative">
                <i class="fas fa-search absolute left-3 text-gray-400 text-sm"></i>
                <input type="text" 
                       placeholder="Search..." 
                       class="w-48 lg:w-64 pl-9 pr-3 py-1.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                       @keydown.enter="window.location.href = '/admin/search?q=' + encodeURIComponent($event.target.value)"
                       @focus="$event.target.select()">
                <kbd class="absolute right-2 text-xs text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200 hidden lg:inline-block">
                    ⌘K
                </kbd>
            </div>
            
            <!-- Quick Actions Dropdown -->
            <div class="relative hidden sm:block" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg p-2 transition-colors"
                        aria-label="Quick Actions">
                    <i class="fas fa-plus text-base md:text-lg"></i>
                </button>
                
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 py-1">
                    <div class="px-3 py-2 border-b border-gray-100">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Quick Actions</p>
                    </div>
                    <a href="{{ route('admin.posts.create') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-file-alt w-5 text-gray-400"></i>
                        <span class="ml-2">New Post</span>
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-user-plus w-5 text-gray-400"></i>
                        <span class="ml-2">New User</span>
                    </a>
                    <a href="{{ route('admin.plans.create') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-crown w-5 text-gray-400"></i>
                        <span class="ml-2">New Plan</span>
                    </a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-upload w-5 text-gray-400"></i>
                        <span class="ml-2">Import Data</span>
                    </a>
                </div>
            </div>
            
            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg p-2 transition-colors relative"
                        aria-label="Notifications">
                    <i class="fas fa-bell text-base md:text-lg"></i>
                    @if($unreadNotifications > 0)
                        <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-semibold rounded-full flex items-center justify-center px-1">
                            {{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}
                        </span>
                    @endif
                </button>
                
                <!-- Notifications Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 max-h-[calc(100vh-6rem)] flex flex-col">
                    
                    <!-- Header -->
                    <div class="flex items-center justify-between p-3 border-b border-gray-200 flex-shrink-0">
                        <p class="text-sm font-semibold text-gray-900">Notifications</p>
                        @if($unreadNotifications > 0)
                            <button onclick="markAllAsRead()" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                                Mark all as read
                            </button>
                        @endif
                    </div>
                    
                    <!-- Notification List -->
                    <div class="overflow-y-auto flex-1">
                        @forelse($notifications as $notification)
                            <div class="px-3 py-2.5 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0 {{ $notification->read_at ? '' : 'bg-blue-50/50 hover:bg-blue-50' }}">
                                <div class="flex items-start gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 {{ $notification->read_at ? '' : 'text-blue-700' }}">
                                            {{ $notification->data['title'] ?? 'Notification' }}
                                        </p>
                                        <p class="text-sm text-gray-600 truncate">{{ $notification->data['message'] ?? '' }}</p>
                                        <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if(!$notification->read_at)
                                        <button onclick="markAsRead('{{ $notification->id }}')" 
                                                class="text-xs text-primary-600 hover:text-primary-700 flex-shrink-0 mt-0.5">
                                            Mark read
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="px-3 py-8 text-center">
                                <i class="fas fa-bell-slash text-3xl text-gray-300 mb-2 block"></i>
                                <p class="text-sm text-gray-500">No notifications yet</p>
                                <p class="text-xs text-gray-400 mt-1">We'll notify you when something happens</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Footer -->
                    <div class="p-2 border-t border-gray-200 flex-shrink-0">
                        <a href="{{ route('admin.notifications.index') }}" 
                           class="block text-center text-sm text-primary-600 hover:text-primary-700 font-medium py-1">
                            View All Notifications
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center space-x-1 md:space-x-2 hover:bg-gray-100 rounded-lg px-1.5 md:px-3 py-1 transition-colors group"
                        aria-label="User Menu">
                    <x-admin.avatar :src="auth()->user()->avatar" :name="auth()->user()->name" size="sm" />
                    <span class="hidden md:inline text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">
                        {{ auth()->user()->name }}
                    </span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" 
                       :class="{'rotate-180': open}"></i>
                </button>
                
                <!-- User Dropdown Menu -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 py-1">
                    
                    <!-- User Info -->
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    
                    <!-- Menu Items -->
                    <a href="{{ route('admin.profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-user w-5 text-gray-400"></i>
                        <span class="ml-2">My Profile</span>
                    </a>
                    <a href="{{ route('admin.profile.password') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-key w-5 text-gray-400"></i>
                        <span class="ml-2">Change Password</span>
                    </a>
                    <a href="{{ route('admin.profile.notifications') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-bell w-5 text-gray-400"></i>
                        <span class="ml-2">Notifications</span>
                        @if($unreadNotifications > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                {{ $unreadNotifications }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-cog w-5 text-gray-400"></i>
                        <span class="ml-2">Settings</span>
                    </a>
                    
                    <hr class="my-1">
                    
                    <a href="{{ route('admin.profile.two-factor') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-shield-alt w-5 text-gray-400"></i>
                        <span class="ml-2">Two-Factor Auth</span>
                    </a>
                    
                    <hr class="my-1">
                    
                    <button onclick="document.getElementById('logout-form').submit()" 
                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="ml-2">Logout</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Search Modal -->
<div x-data="{ open: false }" 
     @open-search.window="open = true"
     class="fixed inset-0 z-50 md:hidden" 
     x-show="open"
     x-cloak>
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>
    <div class="absolute top-0 left-0 right-0 bg-white p-4 shadow-lg">
        <div class="flex items-center gap-3">
            <i class="fas fa-search text-gray-400"></i>
            <input type="text" 
                   placeholder="Search..." 
                   class="flex-1 outline-none text-base"
                   x-ref="mobileSearch"
                   @keydown.enter="window.location.href = '/admin/search?q=' + encodeURIComponent($event.target.value); open = false"
                   x-init="$nextTick(() => $refs.mobileSearch.focus())">
            <button @click="open = false" class="text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<!-- Logout Form -->
<form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
    @csrf
</form>

@push('scripts')
<script>
    // Mark a single notification as read
    function markAsRead(id) {
        fetch(`/admin/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        }).then(() => {
            location.reload();
        });
    }
    
    // Mark all notifications as read
    function markAllAsRead() {
        fetch('/admin/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        }).then(() => {
            location.reload();
        });
    }
    
    // Keyboard shortcut for search
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            // Check if we're on desktop
            if (window.innerWidth >= 768) {
                const searchInput = document.querySelector('header input[type="text"]');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            } else {
                // Mobile - open search modal
                document.dispatchEvent(new CustomEvent('open-search'));
            }
        }
    });
</script>
@endpush

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    
    /* Mobile notification badge animation */
    .notification-dot {
        animation: pulse-dot 2s infinite;
    }
    
    @keyframes pulse-dot {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }
    
    /* Smooth dropdown transitions */
    .dropdown-enter-active,
    .dropdown-leave-active {
        transition: all 0.2s ease;
    }
    .dropdown-enter-from,
    .dropdown-leave-to {
        opacity: 0;
        transform: scale(0.95) translateY(-5px);
    }
</style>
@endpush