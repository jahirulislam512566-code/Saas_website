{{-- resources/views/admin/partials/user-menu.blade.php --}}
<div x-data="userMenuComponent()" 
     x-init="init()"
     class="relative">
    
    <!-- User Button -->
    <button @click="toggleMenu()" 
            class="flex items-center space-x-1 md:space-x-2 hover:bg-gray-100 rounded-lg px-1.5 md:px-3 py-1 transition-colors group"
            aria-label="User Menu">
        <x-admin.avatar :src="auth()->user()->avatar" :name="auth()->user()->name" size="sm" />
        <span class="hidden md:inline text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">
            {{ auth()->user()->name }}
        </span>
        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" 
           :class="{'rotate-180': isOpen}"></i>
    </button>
    
    <!-- Dropdown -->
    <div x-show="isOpen" 
         @click.away="closeMenu()"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 py-1">
        
        <!-- User Info -->
        <div class="px-4 py-3 border-b border-gray-100">
            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
            <div class="mt-1 flex items-center">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1"></span>
                    Online
                </span>
            </div>
        </div>
        
        <!-- Menu Items -->
        <a href="{{ route('admin.profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-user w-5 text-gray-400"></i>
            <span class="ml-2">My Profile</span>
            <span class="ml-auto text-xs text-gray-400">⌘P</span>
        </a>
        
        <a href="{{ route('admin.profile.password') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-key w-5 text-gray-400"></i>
            <span class="ml-2">Change Password</span>
        </a>
        
        <a href="{{ route('admin.profile.notifications') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-bell w-5 text-gray-400"></i>
            <span class="ml-2">Notifications</span>
            @if($unreadNotifications ?? 0 > 0)
                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                    {{ $unreadNotifications ?? 0 }}
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
        
        <a href="{{ route('admin.profile.activity') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-clock w-5 text-gray-400"></i>
            <span class="ml-2">My Activity</span>
        </a>
        
        <hr class="my-1">
        
        <a href="{{ route('admin.profile.delete-account') }}" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
            <i class="fas fa-trash w-5 text-gray-400"></i>
            <span class="ml-2">Delete Account</span>
        </a>
        
        <hr class="my-1">
        
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-sign-out-alt w-5 text-gray-400"></i>
                <span class="ml-2">Logout</span>
                <span class="ml-auto text-xs text-gray-400">⌘Q</span>
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('userMenuComponent', () => ({
        isOpen: false,
        
        init() {
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    window.location.href = '{{ route("admin.profile.edit") }}';
                }
                if ((e.ctrlKey || e.metaKey) && e.key === 'q') {
                    e.preventDefault();
                    document.getElementById('logout-form').submit();
                }
            });
        },
        
        toggleMenu() {
            this.isOpen = !this.isOpen;
        },
        
        closeMenu() {
            this.isOpen = false;
        }
    }));
});
</script>