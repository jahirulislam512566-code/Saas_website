{{-- resources/views/admin/partials/top-nav.blade.php --}}
@props([
    'unreadNotifications' => 0,
    'notifications' => [],
])

<nav class="flex items-center justify-between px-4 h-16 lg:px-6" aria-label="Top Navigation">
    <div class="flex items-center gap-3">
        <!-- Mobile Menu Button -->
        <button @click="$store.sidebar?.toggleSidebar?.()" 
                class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-slate-100 transition-colors text-slate-600"
                aria-label="Toggle sidebar menu"
                type="button">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        
        <!-- Page Title -->
        <h1 class="text-lg font-semibold text-slate-800 truncate">
            @yield('page-title', 'Dashboard')
        </h1>
    </div>
    
    <div class="flex items-center gap-2">
        <!-- Search Button (Mobile) -->
        <button class="lg:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors text-slate-600"
                @click="$store.sidebar?.performSearch?.('')"
                aria-label="Search">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>
        
        <!-- Notifications -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    class="relative p-2 rounded-lg hover:bg-slate-100 transition-colors text-slate-600"
                    aria-label="Notifications">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($unreadNotifications > 0)
                    <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                        {{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}
                    </span>
                @endif
            </button>
            
            <!-- Notification Dropdown -->
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50 max-h-96 overflow-y-auto"
                 style="display: none;">
                
                <div class="px-4 py-2 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-800">Notifications</h3>
                </div>
                
                @if($unreadNotifications > 0)
                    @foreach($notifications as $notification)
                        <a href="{{ $notification->route ?? '#' }}" 
                           class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition-colors {{ $notification->read ? '' : 'bg-indigo-50/50' }}">
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                    <i class="fas {{ $notification->icon ?? 'fa-bell' }} text-xs"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-slate-800">{{ $notification->message }}</p>
                                <p class="text-xs text-slate-500">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$notification->read)
                                <span class="flex-shrink-0 w-1.5 h-1.5 rounded-full bg-indigo-500 mt-2"></span>
                            @endif
                        </a>
                    @endforeach
                    
                    <div class="px-4 py-2 border-t border-slate-100">
                        <a href="{{ route('admin.profile.notifications') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                            View all notifications
                        </a>
                    </div>
                @else
                    <div class="px-4 py-8 text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-bell-slash text-xl"></i>
                        </div>
                        <p class="text-sm text-slate-600">No notifications</p>
                        <p class="text-xs text-slate-400 mt-1">You're all caught up!</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- User Menu -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    class="flex items-center gap-2 p-1 rounded-lg hover:bg-slate-100 transition-colors"
                    aria-label="User menu">
                <x-admin.avatar :src="auth()->user()->avatar" :name="auth()->user()->name" size="sm" />
                <span class="hidden md:block text-sm font-medium text-slate-700">{{ auth()->user()->name }}</span>
                <svg class="hidden md:block w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            
            <!-- User Dropdown -->
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50"
                 style="display: none;">
                
                <div class="px-4 py-3 border-b border-slate-100">
                    <p class="text-sm font-medium text-slate-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                </div>
                
                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Profile</span>
                </a>
                
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Settings</span>
                </a>
                
                <div class="border-t border-slate-100 my-1"></div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>