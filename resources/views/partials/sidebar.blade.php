<aside class="w-64 bg-gray-900 text-white flex-shrink-0 min-h-screen">
    <div class="h-full flex flex-col">
        <!-- Brand -->
        <div class="flex items-center justify-center h-16 border-b border-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-primary-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
                <span class="text-xl font-bold">{{ config('app.name') }}</span>
            </a>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <x-admin.nav-link href="{{ route('admin.dashboard') }}" icon="fa-gauge-high">
                Dashboard
            </x-admin.nav-link>
            
            <!-- Users -->
            <x-admin.nav-section title="Management">
                <x-admin.nav-link href="{{ route('admin.users.index') }}" icon="fa-users">
                    Users
                    <x-admin.badge count="{{ $totalUsers ?? 0 }}" />
                </x-admin.nav-link>
                
                <x-admin.nav-link href="{{ route('admin.roles.index') }}" icon="fa-user-tag">
                    Roles & Permissions
                </x-admin.nav-link>
            </x-admin.nav-section>
            
            <!-- Subscriptions -->
            <x-admin.nav-section title="Billing">
                <x-admin.nav-link href="{{ route('admin.plans.index') }}" icon="fa-crown">
                    Plans
                </x-admin.nav-link>
                
                <x-admin.nav-link href="{{ route('admin.subscriptions.index') }}" icon="fa-receipt">
                    Subscriptions
                    <x-admin.badge count="{{ $activeSubscriptions ?? 0 }}" variant="success" />
                </x-admin.nav-link>
            </x-admin.nav-section>
            
            <!-- Content -->
            <x-admin.nav-section title="Content">
                <x-admin.nav-link href="{{ route('admin.templates.index') }}" icon="fa-puzzle-piece">
                    Templates
                </x-admin.nav-link>
                
                <x-admin.nav-link href="{{ route('admin.media.index') }}" icon="fa-images">
                    Media
                </x-admin.nav-link>
            </x-admin.nav-section>
            
            <!-- Analytics -->
            <x-admin.nav-section title="Analytics">
                <x-admin.nav-link href="{{ route('admin.analytics.index') }}" icon="fa-chart-line">
                    Analytics
                </x-admin.nav-link>
                
                <x-admin.nav-link href="{{ route('admin.reports.index') }}" icon="fa-file-alt">
                    Reports
                </x-admin.nav-link>
            </x-admin.nav-section>
            
            <!-- Support -->
            <x-admin.nav-section title="Support">
                <x-admin.nav-link href="{{ route('admin.support.index') }}" icon="fa-headset">
                    Tickets
                    <x-admin.badge count="{{ $openTickets ?? 0 }}" variant="warning" />
                </x-admin.nav-link>
            </x-admin.nav-section>
            
            <!-- Settings -->
            <x-admin.nav-section title="System">
                <x-admin.nav-link href="{{ route('admin.settings.index') }}" icon="fa-gear">
                    Settings
                </x-admin.nav-link>
            </x-admin.nav-section>
        </nav>
        
        <!-- User Info -->
        <div class="border-t border-gray-800 p-4">
            <div class="flex items-center space-x-3">
                <x-avatar :src="auth()->user()->avatar" :name="auth()->user()->name" size="sm" />
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
                <button type="button" class="text-gray-400 hover:text-white">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </div>
    </div>
</aside>