{{-- resources/views/admin/partials/topbar.blade.php --}}
@props([
    'unreadNotifications' => 0,
    'notifications' => [],
])

@php
    $unreadNotifications = $unreadNotifications ?? (auth()->check() ? auth()->user()->unreadNotifications()->count() : 0);
    $notifications = $notifications ?? (auth()->check() ? auth()->user()->notifications()->latest()->limit(5)->get() : collect());
@endphp

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
                <i class="fas fa-chevron-left text-sm" :class="{'rotate-180': sidebarOpen}"></i>
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
                       id="desktop-search"
                       @keydown.enter="performSearch($event.target.value)"
                       @focus="$event.target.select()"
                       @keydown.escape="$event.target.blur()">
                <kbd class="absolute right-2 text-xs text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200 hidden lg:inline-block">
                    ⌘K
                </kbd>
            </div>
            
            <!-- Quick Actions -->
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
                    <a href="{{ route('admin.categories.create') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-tag w-5 text-gray-400"></i>
                        <span class="ml-2">New Category</span>
                    </a>
                </div>
            </div>
            
            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        @mouseenter="if (window.innerWidth >= 1024) open = true"
                        @mouseleave="if (window.innerWidth >= 1024) open = false"
                        class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg p-2 transition-colors relative"
                        aria-label="Notifications">
                    <i class="fas fa-bell text-base md:text-lg"></i>
                    @if($unreadNotifications > 0)
                        <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-semibold rounded-full flex items-center justify-center px-1 notification-dot">
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
                    
                    <div class="flex items-center justify-between p-3 border-b border-gray-200 flex-shrink-0">
                        <p class="text-sm font-semibold text-gray-900">Notifications</p>
                        <div class="flex items-center space-x-2">
                            @if($unreadNotifications > 0)
                                <button onclick="markAllAsRead()" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                                    Mark all read
                                </button>
                            @endif
                            <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-y-auto flex-1 divide-y divide-gray-100">
                        @forelse($notifications as $notification)
                            <div class="px-3 py-2.5 hover:bg-gray-50 transition-colors {{ $notification->read_at ? '' : 'bg-blue-50/50' }}">
                                <div class="flex items-start gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 {{ $notification->read_at ? '' : 'text-blue-700' }}">
                                            {{ $notification->data['title'] ?? 'Notification' }}
                                        </p>
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                            @if(!$notification->read_at)
                                                <span class="text-xs text-blue-600 font-medium">● New</span>
                                            @endif
                                        </div>
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
                        @mouseenter="if (window.innerWidth >= 1024) open = true"
                        @mouseleave="if (window.innerWidth >= 1024) open = false"
                        class="flex items-center space-x-1 md:space-x-2 hover:bg-gray-100 rounded-lg px-1.5 md:px-3 py-1 transition-colors group"
                        aria-label="User Menu">
                    <x-admin.avatar :src="auth()->user()->avatar" :name="auth()->user()->name" size="sm" />
                    <span class="hidden md:inline text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">
                        {{ auth()->user()->name }}
                    </span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" 
                       :class="{'rotate-180': open}"></i>
                </button>
                
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 py-1">
                    
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    
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

<!-- ===== MOBILE SEARCH MODAL ===== -->
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
                   @keydown.enter="performSearch($event.target.value); open = false"
                   @keydown.escape="open = false"
                   x-init="$nextTick(() => $refs.mobileSearch.focus())">
            <button @click="open = false" class="text-gray-500 hover:text-gray-700 p-2">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-3 flex flex-wrap gap-2">
            <span class="text-xs text-gray-400">Quick search:</span>
            <button @click="window.location.href='/admin/search?q=users'" class="text-xs bg-gray-100 px-2 py-1 rounded hover:bg-gray-200 transition-colors">
                Users
            </button>
            <button @click="window.location.href='/admin/search?q=posts'" class="text-xs bg-gray-100 px-2 py-1 rounded hover:bg-gray-200 transition-colors">
                Posts
            </button>
            <button @click="window.location.href='/admin/search?q=plans'" class="text-xs bg-gray-100 px-2 py-1 rounded hover:bg-gray-200 transition-colors">
                Plans
            </button>
            <button @click="window.location.href='/admin/search?q=orders'" class="text-xs bg-gray-100 px-2 py-1 rounded hover:bg-gray-200 transition-colors">
                Orders
            </button>
        </div>
    </div>
</div>

<!-- ===== LOGOUT FORM ===== -->
<form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
    @csrf
</form>

@push('scripts')
<script>
    // ============================================
    // NOTIFICATION FUNCTIONS
    // ============================================
    function markAsRead(id) {
        fetch(`/admin/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        }).then(() => location.reload());
    }
    
    function markAllAsRead() {
        fetch('/admin/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        }).then(() => location.reload());
    }

    // ============================================
    // SEARCH FUNCTION
    // ============================================
    function performSearch(query) {
        if (query.trim().length > 0) {
            window.location.href = `/admin/search?q=${encodeURIComponent(query.trim())}`;
        }
    }

    // ============================================
    // KEYBOARD SHORTCUTS
    // ============================================
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + K for search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            if (window.innerWidth >= 768) {
                const searchInput = document.getElementById('desktop-search');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            } else {
                document.dispatchEvent(new CustomEvent('open-search'));
            }
        }
        
        // ESC to close mobile search
        if (e.key === 'Escape') {
            // Close mobile search if open
            document.querySelectorAll('[x-data]').forEach(el => {
                if (el.__x) {
                    const data = el.__x.$data;
                    if (data.open !== undefined) {
                        data.open = false;
                    }
                }
            });
        }
    });

    // ============================================
    // TOAST NOTIFICATIONS (for actions)
    // ============================================
    function showToast(message, type = 'success') {
        // Use global toast function from main layout
        if (window.showToast) {
            window.showToast(message, type);
        } else {
            console.log(`${type}: ${message}`);
        }
    }
</script>
@endpush

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    
    /* Notification badge animation */
    .notification-dot {
        animation: pulse-dot 2s infinite;
    }
    
    @keyframes pulse-dot {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    /* Line clamp for notification text */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Rotate animation */
    .rotate-180 {
        transform: rotate(180deg);
    }
    
    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
</style>
@endpush