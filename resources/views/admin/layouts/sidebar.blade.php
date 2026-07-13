{{-- resources/views/admin/layouts/sidebar.blade.php --}}
@props([
    'totalUsers' => 0,
    'activeSubscriptions' => 0,
    'draftPosts' => 0,
    'openTickets' => 0,
])

<div x-data="{
    isOpen: window.innerWidth >= 1024,
    isCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' || false,
    
    init() {
        this.$watch('isOpen', (value) => {
            localStorage.setItem('sidebarOpen', JSON.stringify(value));
        });
        
        this.$watch('isCollapsed', (value) => {
            localStorage.setItem('sidebarCollapsed', JSON.stringify(value));
        });
        
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                this.isOpen = true;
            } else {
                this.isOpen = false;
            }
        });
        
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                if (window.innerWidth >= 1024) {
                    this.toggleCollapse();
                } else {
                    this.toggleSidebar();
                }
            }
            if (e.key === 'Escape' && this.isOpen && window.innerWidth < 1024) {
                this.closeSidebar();
            }
        });
    },
    
    toggleSidebar() {
        this.isOpen = !this.isOpen;
    },
    
    closeSidebar() {
        this.isOpen = false;
    },
    
    toggleCollapse() {
        this.isCollapsed = !this.isCollapsed;
    }
}"
:class="{
    'open': isOpen,
    'collapsed': isCollapsed
}"
class="sidebar"
id="sidebar">
    
    <!-- Brand -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-800 flex-shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 hover:opacity-80 transition-opacity">
            <svg class="w-8 h-8 text-indigo-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
            <span class="sidebar-brand-text text-xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent whitespace-nowrap">
                {{ config('app.name', 'SaaS') }}
            </span>
        </a>
        <button @click="closeSidebar()" class="lg:hidden text-gray-400 hover:text-white transition-colors p-2 rounded-lg hover:bg-gray-800">
            <i class="fas fa-times text-xl"></i>
        </button>
        <button @click="toggleCollapse()" class="hidden lg:flex text-gray-400 hover:text-white transition-colors p-2 rounded-lg hover:bg-gray-800">
            <i class="fas fa-chevron-left" :class="{'rotate-180': isCollapsed}"></i>
        </button>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 overflow-y-auto overflow-x-hidden">
        <!-- Dashboard -->
        <x-admin.nav-item :active="request()->routeIs('admin.dashboard')" 
                          href="{{ route('admin.dashboard') }}" 
                          icon="fa-gauge-high">
            Dashboard
        </x-admin.nav-item>
        
        <!-- Management Section -->
        <div class="sidebar-section-title pt-6 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Management</p>
        </div>
        
        <x-admin.nav-item :active="request()->routeIs('admin.analyti.*')" 
                          href="{{ route('admin.analytics.dashboard') }}" 
                          icon="fa-users">
            Analytics
            <x-admin.badge :count="$totalUsers" class="badge-text ml-auto" />
            
        </x-admin.nav-item>
        <x-admin.nav-item :active="request()->routeIs('admin.users.*')" 
                          href="{{ route('admin.users.index') }}" 
                          icon="fa-users">
            Users
            <x-admin.badge :count="$totalUsers" class="badge-text ml-auto" />
        </x-admin.nav-item>
        
        <x-admin.nav-item :active="request()->routeIs('admin.roles.*')" 
                          href="{{ route('admin.roles.index') }}" 
                          icon="fa-user-tag">
            Roles & Permissions
        </x-admin.nav-item>
        
        <!-- Billing Section -->
        <div class="sidebar-section-title pt-6 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Billing</p>
        </div>
        
        <x-admin.nav-item :active="request()->routeIs('admin.plans.*')" 
                          href="{{ route('admin.plans.index') }}" 
                          icon="fa-crown">
            Plans
        </x-admin.nav-item>
        
        <x-admin.nav-item :active="request()->routeIs('admin.subscriptions.*')" 
                          href="{{ route('admin.subscriptions.index') }}" 
                          icon="fa-receipt">
            Subscriptions
            <x-admin.badge :count="$activeSubscriptions" color="green" class="badge-text ml-auto" />
        </x-admin.nav-item>
        
        <x-admin.nav-item :active="request()->routeIs('admin.payments.*')" 
                          href="{{ route('admin.payments.index') }}" 
                          icon="fa-credit-card">
            Payments
        </x-admin.nav-item>
        
        <!-- Content Section -->
        <div class="sidebar-section-title pt-6 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Content</p>
        </div>
        
        <x-admin.nav-item :active="request()->routeIs('admin.posts.*')" 
                          href="{{ route('admin.posts.index') }}" 
                          icon="fa-newspaper">
            Posts
            <x-admin.badge :count="$draftPosts" color="yellow" class="badge-text ml-auto" />
        </x-admin.nav-item>
        
        <x-admin.nav-item :active="request()->routeIs('admin.media.*')" 
                          href="{{ route('admin.media.index') }}" 
                          icon="fa-images">
            Media
        </x-admin.nav-item>
        
        <x-admin.nav-item :active="request()->routeIs('admin.categories.*')" 
                          href="{{ route('admin.categories.index') }}" 
                          icon="fa-tags">
            Categories
        </x-admin.nav-item>
        
        <!-- Support Section -->
        <div class="sidebar-section-title pt-6 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Support</p>
        </div>
        
        <x-admin.nav-item :active="request()->routeIs('admin.tickets.*')" 
                          href="{{ route('admin.tickets.index') }}" 
                          icon="fa-headset">
            Tickets
            <x-admin.badge :count="$openTickets" color="red" class="badge-text ml-auto" />
        </x-admin.nav-item>
        
        <!-- System Section -->
        <div class="sidebar-section-title pt-6 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">System</p>
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
    </nav>
    
    <!-- User Info -->
    <div class="sidebar-user-info border-t border-gray-800 p-4 flex-shrink-0">
        <div class="flex items-center space-x-3">
            <x-admin.avatar :src="auth()->user()->avatar" :name="auth()->user()->name" size="sm" />
            <div class="sidebar-user-info flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
            </div>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="text-gray-400 hover:text-white transition-colors p-2 rounded-lg hover:bg-gray-800">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                
                <!-- User Dropdown -->
                {{-- <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute bottom-full right-0 mb-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                    
                    <!-- Profile - Using correct route -->
                    <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i> My Profile
                    </a>
                    
                    <!-- Password - Using correct route -->
                    <a href="{{ route('admin.profile.password') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-key mr-2"></i> Change Password
                    </a>
                    
                    <!-- Notifications - Using correct route -->
                    <a href="{{ route('admin.profile.notifications') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-bell mr-2"></i> Notifications
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                {{ auth()->user()->unreadNotifications()->count() }}
                            </span>
                        @endif
                    </a>
                    
                    <!-- Activity - Using correct route -->
                    <a href="{{ route('admin.profile.activity') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-clock mr-2"></i> My Activity
                    </a>
                    
                    <!-- Two Factor - Using correct route -->
                    <a href="{{ route('admin.profile.two-factor') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-shield-alt mr-2"></i> Two-Factor Auth
                    </a>
                    
                    <hr class="my-1">
                    
                    <!-- Settings -->
                    <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>
                    
                    <hr class="my-1">
                    
                    <!-- Delete Account - Using correct route -->
                    <a href="{{ route('admin.profile.delete-account') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <i class="fas fa-trash mr-2"></i> Delete Account
                    </a>
                    
                    <hr class="my-1">
                    
                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div> --}}
            </div>
        </div>
    </div>
</div>

<!-- Sidebar Overlay -->
<div x-show="isOpen" 
     @click="closeSidebar()" 
     class="sidebar-overlay"
     :class="{'active': isOpen}"
     id="sidebar-overlay">
</div>