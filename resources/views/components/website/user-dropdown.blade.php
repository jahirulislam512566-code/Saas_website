{{-- resources/views/components/website/user-dropdown.blade.php --}}
<div x-data="{ open: false }" @click.away="open = false" class="relative">
    <button @click="open = !open" 
            class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition">
        <span class="w-8 h-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white text-sm font-semibold shadow-md">
            {{ auth()->user()->initials ?? 'U' }}
        </span>
        <span class="hidden md:inline">{{ auth()->user()->name }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
        </div>
        
        <x-website.dropdown-link href="{{ route('account.dashboard') }}" icon="fa-chart-pie">
            Dashboard
        </x-website.dropdown-link>
        <x-website.dropdown-link href="{{ route('account.profile') }}" icon="fa-user">
            Profile
        </x-website.dropdown-link>
        <x-website.dropdown-link href="{{ route('account.subscriptions') }}" icon="fa-crown">
            Subscriptions
        </x-website.dropdown-link>
        <x-website.dropdown-link href="{{ route('account.invoices') }}" icon="fa-file-invoice">
            Invoices
        </x-website.dropdown-link>
        <x-website.dropdown-link href="{{ route('account.settings') }}" icon="fa-cog">
            Settings
        </x-website.dropdown-link>
        
        <div class="border-t border-gray-100 mt-1 pt-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-website.dropdown-link href="{{ route('logout') }}" 
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    icon="fa-sign-out-alt" class="text-red-600 hover:bg-red-50">
                    Logout
                </x-website.dropdown-link>
            </form>
        </div>
    </div>
</div>