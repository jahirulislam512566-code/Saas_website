<!-- admin/partials/sidebar.blade.php -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-all duration-300 -translate-x-full lg:translate-x-0" 
       x-data="{ open: window.innerWidth >= 1024 }" 
       x-init="window.addEventListener('resize', () => open = window.innerWidth >= 1024)">
    
    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 h-16 border-b border-gray-200 dark:border-gray-800 flex-shrink-0">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-500/20">
            <i class="fas fa-rocket"></i>
        </div>
        <span class="font-bold text-lg text-gray-900 dark:text-white">{{ config('app.name', 'SaaS Admin') }}</span>
        <span class="ml-auto text-[10px] font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 px-2 py-0.5 rounded-full">v4</span>
    </div>
    
    <!-- Navigation - Scrollable -->
    <nav class="p-3 overflow-y-auto h-[calc(100vh-4rem)] scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700">
        <!-- Dashboard -->
        <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="fa-th-large">
            Dashboard
        </x-sidebar-link>

        <!-- Website Builder -->
        <x-sidebar-dropdown label="Website Builder" icon="fa-code" :open="request()->routeIs('admin.websites.*')">
            <x-sidebar-link href="{{ route('admin.websites.index') }}" :active="request()->routeIs('admin.websites.index')" icon="fa-globe" sub>
                Websites
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.templates.index') }}" :active="request()->routeIs('admin.templates.*')" icon="fa-layer-group" sub>
                Templates
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.pages.index') }}" :active="request()->routeIs('admin.pages.*')" icon="fa-file-alt" sub>
                Pages
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.sections.index') }}" :active="request()->routeIs('admin.sections.*')" icon="fa-columns" sub>
                Sections
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.components.index') }}" :active="request()->routeIs('admin.components.*')" icon="fa-puzzle-piece" sub>
                Components
            </x-sidebar-link>
        </x-sidebar-dropdown>

        <!-- CRM -->
        <x-sidebar-dropdown label="CRM" icon="fa-users" :open="request()->routeIs('admin.crm.*')">
            <x-sidebar-link href="{{ route('admin.customers.index') }}" :active="request()->routeIs('admin.customers.*')" icon="fa-user-friends" sub>
                Customers
                <span class="ml-auto text-xs bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-2 py-0.5 rounded-full">2.4k</span>
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.leads.index') }}" :active="request()->routeIs('admin.leads.*')" icon="fa-user-plus" sub>
                Leads
                <span class="ml-auto text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 px-2 py-0.5 rounded-full">48</span>
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.teams.index') }}" :active="request()->routeIs('admin.teams.*')" icon="fa-users-cog" sub>
                Teams
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.projects.index') }}" :active="request()->routeIs('admin.projects.*')" icon="fa-project-diagram" sub>
                Projects
            </x-sidebar-link>
        </x-sidebar-dropdown>

        <!-- Billing -->
        <x-sidebar-dropdown label="Billing" icon="fa-credit-card" :open="request()->routeIs('admin.billing.*')">
            <x-sidebar-link href="{{ route('admin.plans.index') }}" :active="request()->routeIs('admin.plans.*')" icon="fa-crown" sub>
                Plans
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.subscriptions.index') }}" :active="request()->routeIs('admin.subscriptions.*')" icon="fa-sync-alt" sub>
                Subscriptions
                <span class="ml-auto text-xs bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 px-2 py-0.5 rounded-full">Active</span>
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.invoices.index') }}" :active="request()->routeIs('admin.invoices.*')" icon="fa-file-invoice" sub>
                Invoices
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.payments.index') }}" :active="request()->routeIs('admin.payments.*')" icon="fa-hand-holding-usd" sub>
                Payments
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.coupons.index') }}" :active="request()->routeIs('admin.coupons.*')" icon="fa-ticket-alt" sub>
                Coupons
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.transactions.index') }}" :active="request()->routeIs('admin.transactions.*')" icon="fa-exchange-alt" sub>
                Transactions
            </x-sidebar-link>
        </x-sidebar-dropdown>

        <!-- Content -->
        <x-sidebar-dropdown label="Content" icon="fa-newspaper" :open="request()->routeIs('admin.content.*')">
            <x-sidebar-link href="{{ route('admin.blog.index') }}" :active="request()->routeIs('admin.blog.*')" icon="fa-blog" sub>
                Blog
                <span class="ml-auto text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded-full">12</span>
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.portfolio.index') }}" :active="request()->routeIs('admin.portfolio.*')" icon="fa-images" sub>
                Portfolio
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')" icon="fa-folder-open" sub>
                Categories
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.tags.index') }}" :active="request()->routeIs('admin.tags.*')" icon="fa-tags" sub>
                Tags
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.media.index') }}" :active="request()->routeIs('admin.media.*')" icon="fa-photo-video" sub>
                Media
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.seo.index') }}" :active="request()->routeIs('admin.seo.*')" icon="fa-search-plus" sub>
                SEO
            </x-sidebar-link>
        </x-sidebar-dropdown>

        <!-- Support -->
        <x-sidebar-dropdown label="Support" icon="fa-headset" :open="request()->routeIs('admin.support.*')">
            <x-sidebar-link href="{{ route('admin.tickets.index') }}" :active="request()->routeIs('admin.tickets.*')" icon="fa-ticket-alt" sub>
                Tickets
                <span class="ml-auto text-xs bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-2 py-0.5 rounded-full">7</span>
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.livechat.index') }}" :active="request()->routeIs('admin.livechat.*')" icon="fa-comment-dots" sub>
                Live Chat
                <span class="ml-auto text-xs bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 px-2 py-0.5 rounded-full flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Online
                </span>
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.notifications.index') }}" :active="request()->routeIs('admin.notifications.*')" icon="fa-bell" sub>
                Notifications
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.announcements.index') }}" :active="request()->routeIs('admin.announcements.*')" icon="fa-bullhorn" sub>
                Announcements
            </x-sidebar-link>
        </x-sidebar-dropdown>

        <!-- Analytics -->
        <x-sidebar-dropdown label="Analytics" icon="fa-chart-pie" :open="request()->routeIs('admin.analytics.*')">
            <x-sidebar-link href="{{ route('admin.analytics.dashboard') }}" :active="request()->routeIs('admin.analytics.dashboard')" icon="fa-chart-line" sub>
                Dashboard
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.analytics.reports') }}" :active="request()->routeIs('admin.analytics.reports')" icon="fa-file-alt" sub>
                Reports
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.analytics.logs') }}" :active="request()->routeIs('admin.analytics.logs')" icon="fa-history" sub>
                Activity Logs
            </x-sidebar-link>
        </x-sidebar-dropdown>

        <!-- AI -->
        <x-sidebar-dropdown label="AI Tools" icon="fa-robot" :open="request()->routeIs('admin.ai.*')" highlight>
            <x-sidebar-link href="{{ route('admin.ai.writer') }}" :active="request()->routeIs('admin.ai.writer')" icon="fa-pen-fancy" sub>
                AI Writer
                <span class="ml-auto text-xs bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 px-2 py-0.5 rounded-full">New</span>
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.ai.generator') }}" :active="request()->routeIs('admin.ai.generator')" icon="fa-wand-magic-sparkles" sub>
                Website Generator
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.ai.seo') }}" :active="request()->routeIs('admin.ai.seo')" icon="fa-magnifying-glass-chart" sub>
                SEO Generator
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.ai.image') }}" :active="request()->routeIs('admin.ai.image')" icon="fa-image" sub>
                Image Generator
            </x-sidebar-link>
        </x-sidebar-dropdown>

        <!-- Divider -->
        <div class="my-4 border-t border-gray-200 dark:border-gray-800"></div>

        <!-- System -->
        <x-sidebar-dropdown label="System" icon="fa-server" :open="request()->routeIs('admin.system.*')" default-open>
            <x-sidebar-link href="{{ route('admin.integrations.index') }}" :active="request()->routeIs('admin.integrations.*')" icon="fa-plug" sub>
                Integrations
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.domains.index') }}" :active="request()->routeIs('admin.domains.*')" icon="fa-globe-asia" sub>
                Domains
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.backups.index') }}" :active="request()->routeIs('admin.backups.*')" icon="fa-database" sub>
                Backups
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')" icon="fa-cog" sub>
                Settings
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.maintenance.index') }}" :active="request()->routeIs('admin.maintenance.*')" icon="fa-tools" sub>
                Maintenance
            </x-sidebar-link>
        </x-sidebar-dropdown>

        <!-- Bottom Actions -->
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800 space-y-1">
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200">
                <i class="fas fa-life-ring w-5 text-center text-gray-400"></i>
                <span>Help Center</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200">
                <i class="fas fa-question-circle w-5 text-center text-gray-400"></i>
                <span>Documentation</span>
                <span class="ml-auto text-[10px] font-medium text-gray-400 dark:text-gray-600">v4.0</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>

<!-- Mobile Overlay -->
<div x-data="{ open: window.innerWidth >= 1024 }" 
     x-show="open && window.innerWidth < 1024" 
     @click="open = false"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"
     x-transition:enter="transition-opacity ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
</div>

<!-- Sidebar Toggle Script -->
<script>
document.addEventListener('alpine:init', () => {
    document.addEventListener('toggle-sidebar', () => {
        const sidebar = document.querySelector('[x-data]');
        if (sidebar) {
            const open = sidebar.__x.getUnobservedData().open;
            sidebar.__x.setData('open', !open);
        }
    });
});
</script>

@push('styles')
<style>
.scrollbar-thin::-webkit-scrollbar {
    width: 4px;
}
.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 9999px;
}
.dark .scrollbar-thin::-webkit-scrollbar-thumb {
    background: #374151;
}
.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
.dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #4b5563;
}
</style>
@endpush