{{-- resources/views/admin/layouts/sidebar.blade.php --}}
@props([
    'totalUsers' => 0,
    'activeSubscriptions' => 0,
    'draftPosts' => 0,
    'openTickets' => 0,
    'pendingOrders' => 0,
    'unreadNotifications' => 0,
])

<div x-data="sidebar()" 
     x-init="init()"
     :class="{
         'open': isOpen,
         'collapsed': isCollapsed,
         'hover-expand': isHovering
     }"
     class="sidebar"
     id="sidebar"
     @mouseenter="isHovering = true"
     @mouseleave="isHovering = false">
    
    <!-- Brand / Logo -->
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">
                <svg class="w-8 h-8 text-indigo-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    <path d="M12 22V12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="brand-text">
                <span class="brand-name">{{ config('app.name', 'SaaS') }}</span>
                <span class="brand-badge">Admin</span>
            </div>
        </a>
        
        <div class="sidebar-actions">
            <button @click="toggleCollapse()" class="sidebar-toggle-btn" title="Toggle Sidebar">
                <i class="fas fa-chevron-left" :class="{'rotate-180': isCollapsed}"></i>
            </button>
            <button @click="closeSidebar()" class="sidebar-close-btn lg:hidden">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
    <!-- Search -->
    <div class="sidebar-search" :class="{'hidden': isCollapsed && !isHovering}">
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" 
                   placeholder="Search..." 
                   class="search-input"
                   @keydown.enter="performSearch($event.target.value)">
            <kbd class="search-shortcut">Ctrl+K</kbd>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <div class="nav-section">
            <x-admin.nav-item :active="request()->routeIs('admin.dashboard')" 
                              href="{{ route('admin.dashboard') }}" 
                              icon="fa-gauge-high">
                Dashboard
                <span class="nav-item-indicator"></span>
            </x-admin.nav-item>
        </div>
        
        <!-- Management Section -->
        <div class="nav-section">
            <div class="nav-section-title">
                <span>Management</span>
                <span class="section-line"></span>
            </div>
            
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
        </div>
        
        <!-- Billing Section -->
        <div class="nav-section">
            <div class="nav-section-title">
                <span>Billing</span>
                <span class="section-line"></span>
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
                <x-admin.badge :count="$pendingOrders" color="yellow" class="badge-text ml-auto" />
            </x-admin.nav-item>
        </div>
        
        <!-- Content Section -->
        <div class="nav-section">
            <div class="nav-section-title">
                <span>Content</span>
                <span class="section-line"></span>
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
        </div>
        
        <!-- Analytics Section -->
        <div class="nav-section">
            <div class="nav-section-title">
                <span>Analytics</span>
                <span class="section-line"></span>
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
        
        <!-- Support Section -->
        <div class="nav-section">
            <div class="nav-section-title">
                <span>Support</span>
                <span class="section-line"></span>
            </div>
            
            <x-admin.nav-item :active="request()->routeIs('admin.tickets.*')" 
                              href="{{ route('admin.tickets.index') }}" 
                              icon="fa-headset">
                Tickets
                <x-admin.badge :count="$openTickets" color="red" class="badge-text ml-auto" />
            </x-admin.nav-item>
        </div>
        
        <!-- System Section -->
        <div class="nav-section">
            <div class="nav-section-title">
                <span>System</span>
                <span class="section-line"></span>
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
    <div class="sidebar-footer">
        <div class="user-profile" @click="toggleUserMenu()">
            <div class="user-avatar-wrapper">
                <x-admin.avatar :src="auth()->user()->avatar" :name="auth()->user()->name" size="sm" />
                <span class="user-status" :class="{'online': true}"></span>
            </div>
            <div class="user-info" :class="{'hidden': isCollapsed && !isHovering}">
                <p class="user-name">{{ auth()->user()->name }}</p>
                <p class="user-email">{{ auth()->user()->email }}</p>
            </div>
            <button class="user-menu-btn" :class="{'hidden': isCollapsed && !isHovering}">
                <i class="fas fa-chevron-up" :class="{'rotate-180': userMenuOpen}"></i>
            </button>
        </div>
        
        <!-- User Dropdown Menu -->
        <div x-show="userMenuOpen" 
             @click.away="userMenuOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="user-dropdown">
            
            <div class="dropdown-header">
                <x-admin.avatar :src="auth()->user()->avatar" :name="auth()->user()->name" size="md" />
                <div>
                    <p class="dropdown-user-name">{{ auth()->user()->name }}</p>
                    <p class="dropdown-user-email">{{ auth()->user()->email }}</p>
                </div>
            </div>
            
            <div class="dropdown-divider"></div>
            
            <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
                <span class="dropdown-shortcut">⌘P</span>
            </a>
            
            <a href="{{ route('admin.profile.password') }}" class="dropdown-item">
                <i class="fas fa-key"></i>
                <span>Change Password</span>
            </a>
            
            <a href="{{ route('admin.profile.notifications') }}" class="dropdown-item">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
                @if($unreadNotifications > 0)
                    <span class="dropdown-badge">{{ $unreadNotifications }}</span>
                @endif
            </a>
            
            <a href="{{ route('admin.profile.activity') }}" class="dropdown-item">
                <i class="fas fa-clock"></i>
                <span>My Activity</span>
            </a>
            
            <a href="{{ route('admin.profile.two-factor') }}" class="dropdown-item">
                <i class="fas fa-shield-alt"></i>
                <span>Two-Factor Auth</span>
            </a>
            
            <div class="dropdown-divider"></div>
            
            <a href="{{ route('admin.settings.index') }}" class="dropdown-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            
            <div class="dropdown-divider"></div>
            
            <a href="{{ route('admin.profile.delete-account') }}" class="dropdown-item text-red-600 hover:bg-red-50">
                <i class="fas fa-trash"></i>
                <span>Delete Account</span>
            </a>
            
            <div class="dropdown-divider"></div>
            
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="submit" class="dropdown-item w-full text-left">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                    <span class="dropdown-shortcut">⌘Q</span>
                </button>
            </form>
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

@push('styles')

<style>
/* ============================================
   SIDEBAR STYLES - Professional Dark Theme
   ============================================ */

.sidebar {
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 72px;
    --sidebar-bg: #0f172a;
    --sidebar-border: #1e293b;
    --sidebar-text: #94a3b8;
    --sidebar-text-hover: #e2e8f0;
    --sidebar-active-bg: rgba(99, 102, 241, 0.15);
    --sidebar-active-color: #818cf8;
    --sidebar-hover-bg: rgba(255, 255, 255, 0.05);
    
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: var(--sidebar-width);
    background: var(--sidebar-bg);
    border-right: 1px solid var(--sidebar-border);
    display: flex;
    flex-direction: column;
    z-index: 1000;
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.sidebar.collapsed {
    width: var(--sidebar-collapsed-width);
}

.sidebar.collapsed .sidebar-brand .brand-text,
.sidebar.collapsed .sidebar-search,
.sidebar.collapsed .nav-item-text,
.sidebar.collapsed .nav-item-badge,
.sidebar.collapsed .nav-section-title span:not(.section-line),
.sidebar.collapsed .user-info,
.sidebar.collapsed .user-menu-btn {
    display: none;
}

.sidebar.collapsed .sidebar-brand {
    justify-content: center;
    padding: 0;
}

.sidebar.collapsed .nav-item {
    justify-content: center;
    padding: 0.75rem;
}

.sidebar.collapsed .nav-item i {
    margin: 0;
}

.sidebar.collapsed .nav-item .nav-item-indicator {
    display: none;
}

.sidebar.collapsed .user-profile {
    justify-content: center;
}

.sidebar.collapsed .user-avatar-wrapper {
    margin: 0;
}

/* Hover Expand */
.sidebar.collapsed.hover-expand {
    width: var(--sidebar-width);
}

.sidebar.collapsed.hover-expand .sidebar-brand .brand-text,
.sidebar.collapsed.hover-expand .sidebar-search,
.sidebar.collapsed.hover-expand .nav-item-text,
.sidebar.collapsed.hover-expand .nav-item-badge,
.sidebar.collapsed.hover-expand .nav-section-title span:not(.section-line),
.sidebar.collapsed.hover-expand .user-info,
.sidebar.collapsed.hover-expand .user-menu-btn {
    display: flex;
}

.sidebar.collapsed.hover-expand .sidebar-brand {
    justify-content: flex-start;
    padding: 0 1rem;
}

.sidebar.collapsed.hover-expand .nav-item {
    justify-content: flex-start;
    padding: 0.625rem 1rem;
}

.sidebar.collapsed.hover-expand .nav-item i {
    margin-right: 0.75rem;
}

.sidebar.collapsed.hover-expand .user-profile {
    justify-content: flex-start;
}

/* ===== SCROLLBAR ===== */
.sidebar::-webkit-scrollbar {
    width: 3px;
}

.sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #475569;
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}

/* ===== SIDEBAR HEADER ===== */
.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 64px;
    padding: 0 1rem;
    border-bottom: 1px solid var(--sidebar-border);
    flex-shrink: 0;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    transition: opacity 0.2s;
}

.sidebar-brand:hover {
    opacity: 0.8;
}

.brand-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.brand-icon svg {
    width: 24px;
    height: 24px;
}

.brand-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.brand-name {
    font-size: 1.125rem;
    font-weight: 700;
    color: white;
    letter-spacing: -0.025em;
}

.brand-badge {
    font-size: 0.625rem;
    font-weight: 500;
    color: #818cf8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.sidebar-actions {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.sidebar-toggle-btn,
.sidebar-close-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    color: #64748b;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.sidebar-toggle-btn:hover,
.sidebar-close-btn:hover {
    background: var(--sidebar-hover-bg);
    color: white;
}

.sidebar-toggle-btn .rotate-180 {
    transform: rotate(180deg);
}

/* ===== SEARCH ===== */
.sidebar-search {
    padding: 0.75rem 1rem;
    flex-shrink: 0;
}

.search-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 0.75rem;
    color: #475569;
    font-size: 0.875rem;
}

.search-input {
    width: 100%;
    padding: 0.5rem 2.5rem 0.5rem 2.25rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--sidebar-border);
    border-radius: 8px;
    color: white;
    font-size: 0.875rem;
    transition: all 0.2s;
    outline: none;
}

.search-input::placeholder {
    color: #475569;
}

.search-input:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-shortcut {
    position: absolute;
    right: 0.75rem;
    font-size: 0.625rem;
    color: #475569;
    background: rgba(255, 255, 255, 0.05);
    padding: 0.125rem 0.5rem;
    border-radius: 4px;
    border: 1px solid var(--sidebar-border);
}

/* ===== NAVIGATION ===== */
.sidebar-nav {
    flex: 1;
    overflow-y: auto;
    padding: 0.5rem 0.75rem;
}

.nav-section {
    margin-bottom: 0.5rem;
}

.nav-section-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0.75rem 0.5rem 0.75rem;
    font-size: 0.625rem;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.075em;
}

.section-line {
    flex: 1;
    height: 1px;
    background: var(--sidebar-border);
}

/* ===== NAV ITEMS ===== */
.nav-item {
    display: flex;
    align-items: center;
    padding: 0.625rem 0.75rem;
    border-radius: 8px;
    color: var(--sidebar-text);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.15s;
    position: relative;
    gap: 0.75rem;
    cursor: pointer;
}

.nav-item:hover {
    background: var(--sidebar-hover-bg);
    color: var(--sidebar-text-hover);
}

.nav-item.active {
    background: var(--sidebar-active-bg);
    color: var(--sidebar-active-color);
}

.nav-item.active .nav-item-indicator {
    display: block;
}

.nav-item i {
    width: 20px;
    text-align: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.nav-item-text {
    flex: 1;
    white-space: nowrap;
}

.nav-item-badge {
    flex-shrink: 0;
    margin-left: auto;
}

.nav-item-indicator {
    display: none;
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 20px;
    background: linear-gradient(180deg, #6366f1, #8b5cf6);
    border-radius: 3px;
}

/* ===== FOOTER / USER PROFILE ===== */
.sidebar-footer {
    border-top: 1px solid var(--sidebar-border);
    flex-shrink: 0;
    position: relative;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    cursor: pointer;
    transition: background 0.2s;
    border-radius: 0;
}

.user-profile:hover {
    background: var(--sidebar-hover-bg);
}

.user-avatar-wrapper {
    position: relative;
    flex-shrink: 0;
}

.user-status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid var(--sidebar-bg);
}

.user-status.online {
    background: #22c55e;
}

.user-status.away {
    background: #eab308;
}

.user-status.busy {
    background: #ef4444;
}

.user-info {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-email {
    font-size: 0.75rem;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-menu-btn {
    background: none;
    border: none;
    color: #64748b;
    cursor: pointer;
    padding: 0.25rem;
    transition: transform 0.2s;
}

.user-menu-btn .rotate-180 {
    transform: rotate(180deg);
}

/* ===== USER DROPDOWN ===== */
.user-dropdown {
    position: absolute;
    bottom: calc(100% + 0.5rem);
    left: 0.75rem;
    right: 0.75rem;
    background: #1e293b;
    border: 1px solid var(--sidebar-border);
    border-radius: 12px;
    padding: 0.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    z-index: 1050;
}

.dropdown-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem;
}

.dropdown-user-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
}

.dropdown-user-email {
    font-size: 0.75rem;
    color: #64748b;
}

.dropdown-divider {
    height: 1px;
    background: var(--sidebar-border);
    margin: 0.25rem 0.5rem;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    color: #cbd5e1;
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.15s;
    cursor: pointer;
    border: none;
    background: none;
    width: 100%;
}

.dropdown-item:hover {
    background: var(--sidebar-hover-bg);
    color: white;
}

.dropdown-item i {
    width: 18px;
    text-align: center;
    font-size: 0.875rem;
}

.dropdown-item .dropdown-shortcut {
    margin-left: auto;
    font-size: 0.625rem;
    color: #475569;
}

.dropdown-item .dropdown-badge {
    margin-left: auto;
    background: #ef4444;
    color: white;
    font-size: 0.625rem;
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
}

/* ===== OVERLAY ===== */
.sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

.sidebar-overlay.active {
    display: block;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .sidebar {
        transform: translateX(-100%);
        width: var(--sidebar-width) !important;
    }
    
    .sidebar.open {
        transform: translateX(0);
    }
    
    .sidebar-overlay.active {
        display: block;
    }
}
</style>


@endpush

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('sidebar', () => ({
        isOpen: window.innerWidth >= 1024,
        isCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' || false,
        isHovering: false,
        userMenuOpen: false,
        searchQuery: '',
        
        init() {
            // Watch for changes and persist state
            this.$watch('isOpen', (value) => {
                localStorage.setItem('sidebarOpen', JSON.stringify(value));
            });
            
            this.$watch('isCollapsed', (value) => {
                localStorage.setItem('sidebarCollapsed', JSON.stringify(value));
            });
            
            // Handle resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    this.isOpen = true;
                } else {
                    this.isOpen = false;
                }
            });
            
            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                // Ctrl+B to toggle sidebar
                if (e.ctrlKey && e.key === 'b') {
                    e.preventDefault();
                    if (window.innerWidth >= 1024) {
                        this.toggleCollapse();
                    } else {
                        this.toggleSidebar();
                    }
                }
                
                // ESC to close
                if (e.key === 'Escape' && this.isOpen && window.innerWidth < 1024) {
                    this.closeSidebar();
                }
                
                // Ctrl+K for search
                if (e.ctrlKey && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.querySelector('.search-input');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }
            });
        },
        
        toggleSidebar() {
            this.isOpen = !this.isOpen;
        },
        
        openSidebar() {
            this.isOpen = true;
        },
        
        closeSidebar() {
            this.isOpen = false;
        },
        
        toggleCollapse() {
            this.isCollapsed = !this.isCollapsed;
        },
        
        toggleUserMenu() {
            this.userMenuOpen = !this.userMenuOpen;
        },
        
        performSearch(query) {
            if (query.trim().length > 0) {
                window.location.href = `/admin/search?q=${encodeURIComponent(query)}`;
            }
        }
    }));
});
</script>
@endpush