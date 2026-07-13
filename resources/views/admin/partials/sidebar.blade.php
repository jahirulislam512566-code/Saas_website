{{-- resources/views/admin/partials/sidebar.blade.php --}}
@props([
    'totalUsers' => 0,
    'activeSubscriptions' => 0,
    'draftPosts' => 0,
    'openTickets' => 0,
    'unreadNotifications' => 0,
])

@php
    $user = auth()->user();
@endphp

<!-- Sidebar - NO OVERLAY HERE (moved to layout) -->
<div class="h-full flex flex-col bg-slate-900 border-r border-slate-800">
    
    <!-- Header -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-slate-800 shrink-0">
        <a href="{{ route('admin.dashboard.index') }}" class="flex items-center gap-3 transition-opacity hover:opacity-80">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/25 shrink-0">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    <path d="M12 22V12" stroke="white" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <div x-show="!isCollapsed || isHovering" x-cloak>
                <span class="block text-sm font-bold text-white tracking-tight">{{ config('app.name', 'SaaS') }}</span>
                <span class="block text-[10px] font-medium text-indigo-400 uppercase tracking-wider">Admin</span>
            </div>
        </a>
        
        <div class="flex items-center gap-1">
            <button @click="toggleCollapse()" 
                    class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors"
                    title="Toggle Sidebar">
                <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-180': isCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button @click="closeMobileSidebar()" class="lg:hidden flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Search -->
    <div x-show="!isCollapsed || isHovering" x-cloak class="px-4 py-3 shrink-0">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" 
                   placeholder="Search..." 
                   class="search-input w-full pl-9 pr-10 py-2 bg-white/5 border border-slate-800 rounded-lg text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                   @keydown.enter="performSearch($event.target.value)"
                   @focus="$event.target.select()"
                   @keydown.escape="$event.target.blur()"
                   aria-label="Search">
            <kbd class="absolute right-3 top-1/2 -translate-y-1/2 hidden sm:block text-[10px] font-medium text-slate-500 bg-white/5 px-1.5 py-0.5 rounded border border-slate-800">⌘K</kbd>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-6 overscroll-contain" role="navigation" aria-label="Main Navigation">
        <!-- Dashboard -->
        <div class="space-y-0.5">
            <x-admin.nav-item :active="request()->routeIs('admin.dashboard')" 
                              href="{{ route('admin.dashboard.index') }}" 
                              icon="fa-gauge-high">
                Dashboard
            </x-admin.nav-item>
        </div>
        
        <!-- Management -->
        <div class="space-y-0.5">
            <div x-show="!isCollapsed || isHovering" x-cloak class="flex items-center gap-2 px-3 py-1.5">
                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Management</span>
                <span class="flex-1 h-px bg-slate-800"></span>
            </div>
            
            <x-admin.nav-item :active="request()->routeIs('admin.users.*')" 
                              href="{{ route('admin.users.index') }}" 
                              icon="fa-users"
                              :badge="$totalUsers">
                Users
            </x-admin.nav-item>
            
            <x-admin.nav-item :active="request()->routeIs('admin.roles.*')" 
                              href="{{ route('admin.roles.index') }}" 
                              icon="fa-user-tag">
                Roles & Permissions
            </x-admin.nav-item>
        </div>
        
        <!-- Billing -->
        <div class="space-y-0.5">
            <div x-show="!isCollapsed || isHovering" x-cloak class="flex items-center gap-2 px-3 py-1.5">
                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Billing</span>
                <span class="flex-1 h-px bg-slate-800"></span>
            </div>
            
            {{-- <x-admin.nav-item :active="request()->routeIs('admin.billing.*')" 
                              href="{{ route('admin.billing.getways') }}" 
                              icon="fa-crown">
                Billing
            </x-admin.nav-item> --}}
            <x-admin.nav-item :active="request()->routeIs('admin.plans.*')" 
                              href="{{ route('admin.plans.index') }}" 
                              icon="fa-crown">
                Plans
            </x-admin.nav-item>
            
            <x-admin.nav-item :active="request()->routeIs('admin.subscriptions.*')" 
                              href="{{ route('admin.subscriptions.index') }}" 
                              icon="fa-receipt"
                              :badge="$activeSubscriptions"
                              badgeColor="green">
                Subscriptions
            </x-admin.nav-item>
            
            <x-admin.nav-item :active="request()->routeIs('admin.payments.*')" 
                              href="{{ route('admin.payments.index') }}" 
                              icon="fa-credit-card">
                Payments
            </x-admin.nav-item>
        </div>
        
        <!-- Content -->
        <div class="space-y-0.5">
            <div x-show="!isCollapsed || isHovering" x-cloak class="flex items-center gap-2 px-3 py-1.5">
                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Content</span>
                <span class="flex-1 h-px bg-slate-800"></span>
            </div>
            
            <x-admin.nav-item :active="request()->routeIs('admin.posts.*')" 
                              href="{{ route('admin.posts.index') }}" 
                              icon="fa-newspaper"
                              :badge="$draftPosts"
                              badgeColor="yellow">
                Posts
            </x-admin.nav-item>
            
            <x-admin.nav-item :active="request()->routeIs('admin.media.*')" 
                              href="{{ route('admin.media.library') }}" 
                              icon="fa-images">
                Media
            </x-admin.nav-item>
            
            <x-admin.nav-item :active="request()->routeIs('admin.categories.*')" 
                              href="{{ route('admin.categories.index') }}" 
                              icon="fa-tags">
                Categories
            </x-admin.nav-item>
        </div>
        
        <!-- Analytics -->
        <div class="space-y-0.5">
            <div x-show="!isCollapsed || isHovering" x-cloak class="flex items-center gap-2 px-3 py-1.5">
                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Analytics</span>
                <span class="flex-1 h-px bg-slate-800"></span>
            </div>
            
            <x-admin.nav-item :active="request()->routeIs('admin.analytics.*')" 
                              href="{{ route('admin.analytics.dashboard') }}" 
                              icon="fa-chart-line">
                Analytics
            </x-admin.nav-item>
            
            <x-admin.nav-item :active="request()->routeIs('admin.reports.*')" 
                              href="{{ route('admin.reports.index') }}" 
                              icon="fa-file-alt">
                Reports
            </x-admin.nav-item>
        </div>
        
        <!-- Support -->
        <div class="space-y-0.5">
            <div x-show="!isCollapsed || isHovering" x-cloak class="flex items-center gap-2 px-3 py-1.5">
                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Support</span>
                <span class="flex-1 h-px bg-slate-800"></span>
            </div>
            
            <x-admin.nav-item :active="request()->routeIs('admin.tickets.*')" 
                              href="{{ route('admin.tickets.index') }}" 
                              icon="fa-headset"
                              :badge="$openTickets"
                              badgeColor="red">
                Tickets
            </x-admin.nav-item>
        </div>
        
        <!-- System -->
        <div class="space-y-0.5">
            <div x-show="!isCollapsed || isHovering" x-cloak class="flex items-center gap-2 px-3 py-1.5">
                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">System</span>
                <span class="flex-1 h-px bg-slate-800"></span>
            </div>
            
            <x-admin.nav-item :active="request()->routeIs('admin.settings.*')" 
                              href="{{ route('admin.settings.index') }}" 
                              icon="fa-gear">
                Settings
            </x-admin.nav-item>
            
            <x-admin.nav-item :active="request()->routeIs('admin.activities.*')" 
                              href="{{ route('admin.activities.index') }}" 
                              icon="fa-clock-rotate-left">
                Activity Log
            </x-admin.nav-item>
        </div>
    </nav>
    
    <!-- User Profile -->
    <div class="shrink-0 border-t border-slate-800">
        <div @click="toggleUserMenu()" 
             class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-white/5 transition-colors"
             role="button"
             tabindex="0">
            <div class="relative shrink-0">
                <x-admin.avatar :src="$user->avatar" :name="$user->name" size="sm" />
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full border-2 border-slate-900 bg-emerald-500"></span>
            </div>
            <div x-show="!isCollapsed || isHovering" x-cloak class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ $user->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
            </div>
            <button x-show="!isCollapsed || isHovering" 
                    x-cloak 
                    class="text-slate-400 transition-transform duration-200" 
                    :class="{'rotate-180': userMenuOpen}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
            </button>
        </div>
        
        <!-- User Dropdown -->
        <div x-show="userMenuOpen" 
             @click.away="userMenuOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="absolute bottom-full left-3 right-3 mb-1 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl py-2 z-50">
            
            <div class="flex items-center gap-3 px-4 py-2">
                <x-admin.avatar :src="$user->avatar" :name="$user->name" size="md" />
                <div class="min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $user->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                </div>
            </div>
            
            <div class="h-px bg-slate-700 my-1"></div>
            
            <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-white/5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>My Profile</span>
                <kbd class="ml-auto hidden sm:block text-[10px] text-slate-500 bg-slate-700/50 px-1.5 py-0.5 rounded">⌘P</kbd>
            </a>
            
            <a href="{{ route('admin.profile.password') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-white/5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <span>Change Password</span>
            </a>
            
            <a href="{{ route('admin.profile.notifications') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-white/5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span>Notifications</span>
                @if($unreadNotifications > 0)
                    <span class="ml-auto flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-red-500 rounded-full">{{ $unreadNotifications }}</span>
                @endif
            </a>
            
            <div class="h-px bg-slate-700 my-1"></div>
            
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-white/5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Settings</span>
            </a>
            
            <div class="h-px bg-slate-700 my-1"></div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-colors text-left">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                    <kbd class="ml-auto hidden sm:block text-[10px] text-slate-500 bg-slate-700/50 px-1.5 py-0.5 rounded">⌘Q</kbd>
                </button>
            </form>
        </div>
    </div>
</div>