{{-- resources/views/components/website/account-nav.blade.php --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <nav class="flex flex-wrap gap-1 p-2">
        <a href="{{ route('account.dashboard') }}" 
           class="px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('account.dashboard') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
            <i class="fas fa-chart-pie mr-2"></i>Dashboard
        </a>
        <a href="{{ route('account.profile') }}" 
           class="px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('account.profile') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
            <i class="fas fa-user mr-2"></i>Profile
        </a>
        <a href="{{ route('account.subscriptions') }}" 
           class="px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('account.subscriptions') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
            <i class="fas fa-crown mr-2"></i>Subscriptions
        </a>
        <a href="{{ route('account.invoices') }}" 
           class="px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('account.invoices') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
            <i class="fas fa-file-invoice mr-2"></i>Invoices
        </a>
        <a href="{{ route('account.settings') }}" 
           class="px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('account.settings') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
            <i class="fas fa-cog mr-2"></i>Settings
        </a>
    </nav>
</div>