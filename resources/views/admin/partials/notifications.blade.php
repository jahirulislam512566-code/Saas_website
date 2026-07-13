{{-- resources/views/admin/partials/notifications.blade.php --}}
<div x-data="notificationsComponent()" 
     x-init="init()"
     class="relative">
    
    <!-- Notification Bell -->
    <button @click="toggleNotifications()" 
            class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg p-2 transition-colors relative"
            aria-label="Notifications">
        <i class="fas fa-bell text-lg"></i>
        <span x-show="unreadCount > 0" 
              x-text="unreadCount > 9 ? '9+' : unreadCount"
              class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-semibold rounded-full flex items-center justify-center px-1">
        </span>
    </button>
    
    <!-- Dropdown -->
    <div x-show="isOpen" 
         @click.away="closeNotifications()"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 max-h-[calc(100vh-6rem)] flex flex-col">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-3 border-b border-gray-200 flex-shrink-0">
            <p class="text-sm font-semibold text-gray-900">
                Notifications
                <span x-show="unreadCount > 0" 
                      x-text="`(${unreadCount} unread)`"
                      class="text-xs font-normal text-gray-500 ml-1"></span>
            </p>
            <div class="flex items-center space-x-2">
                <button x-show="unreadCount > 0" 
                        @click="markAllAsRead()"
                        class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                    Mark all read
                </button>
                <button @click="closeNotifications()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
        
        <!-- List -->
        <div class="overflow-y-auto flex-1 divide-y divide-gray-100" x-ref="list">
            <template x-for="notification in notifications" :key="notification.id">
                <div class="px-3 py-2.5 hover:bg-gray-50 transition-colors" 
                     :class="{'bg-blue-50/50': !notification.read_at}">
                    <div class="flex items-start gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium" 
                               :class="notification.read_at ? 'text-gray-900' : 'text-blue-700'"
                               x-text="notification.title"></p>
                            <p class="text-sm text-gray-600 truncate" x-text="notification.message"></p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-gray-400" x-text="notification.time_ago"></span>
                                <span x-show="!notification.read_at" 
                                      class="text-xs text-blue-600 font-medium">● New</span>
                            </div>
                        </div>
                        <button x-show="!notification.read_at" 
                                @click="markAsRead(notification.id)"
                                class="text-xs text-primary-600 hover:text-primary-700 flex-shrink-0 mt-0.5">
                            Mark read
                        </button>
                    </div>
                </div>
            </template>
            
            <!-- Empty State -->
            <div x-show="notifications.length === 0" class="px-3 py-8 text-center">
                <i class="fas fa-bell-slash text-3xl text-gray-300 mb-2 block"></i>
                <p class="text-sm text-gray-500">No notifications yet</p>
                <p class="text-xs text-gray-400 mt-1">We'll notify you when something happens</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="p-2 border-t border-gray-200 flex-shrink-0">
            <a href="{{ route('admin.notifications.index') }}" 
               class="block text-center text-sm text-primary-600 hover:text-primary-700 font-medium py-1.5">
                View All Notifications
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('notificationsComponent', () => ({
        isOpen: false,
        notifications: [],
        unreadCount: 0,
        refreshInterval: null,
        
        init() {
            this.fetchNotifications();
            this.startAutoRefresh();
        },
        
        toggleNotifications() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.fetchNotifications();
            }
        },
        
        closeNotifications() {
            this.isOpen = false;
        },
        
        async fetchNotifications() {
            try {
                const response = await fetch('/admin/api/notifications', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    this.notifications = data.data;
                    this.unreadCount = data.unread_count || 0;
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        },
        
        async markAsRead(id) {
            try {
                const response = await fetch(`/admin/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();
                
                if (data.success) {
                    this.fetchNotifications();
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },
        
        async markAllAsRead() {
            try {
                const response = await fetch('/admin/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();
                
                if (data.success) {
                    this.fetchNotifications();
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        },
        
        startAutoRefresh() {
            this.refreshInterval = setInterval(() => {
                if (!this.isOpen) {
                    this.fetchNotifications();
                }
            }, 30000); // Refresh every 30 seconds
        },
        
        destroy() {
            if (this.refreshInterval) {
                clearInterval(this.refreshInterval);
            }
        }
    }));
});
</script>