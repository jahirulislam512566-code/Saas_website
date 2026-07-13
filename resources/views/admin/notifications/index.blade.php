{{-- resources/views/admin/notifications/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Notifications')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Notifications</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all notifications and alerts</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.notifications.announcements') }}" 
               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-bullhorn mr-2"></i> Announcements
            </a>
            <a href="{{ route('admin.notifications.email') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-envelope mr-2"></i> Email Templates
            </a>
            <button onclick="markAllAsRead()" 
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-check-double mr-2"></i> Mark All Read
            </button>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-bell"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Unread</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['unread'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Announcements</p>
                    <p class="text-xl font-bold text-purple-600">{{ $stats['announcements'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-bullhorn"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">System</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['system'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-server"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTERS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" placeholder="Search notifications..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Types</option>
                    <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                    <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>System</option>
                    <option value="user" {{ request('type') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="billing" {{ request('type') == 'billing' ? 'selected' : '' }}>Billing</option>
                    <option value="security" {{ request('type') == 'security' ? 'selected' : '' }}>Security</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Status</option>
                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.notifications.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- ===== NOTIFICATIONS LIST ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors group 
                    {{ $notification->read_at ? '' : 'bg-blue-50/30' }}">
                    <div class="flex items-start space-x-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                {{ $notification->type == 'announcement' ? 'bg-purple-100 text-purple-600' : 
                                   ($notification->type == 'system' ? 'bg-green-100 text-green-600' : 
                                   ($notification->type == 'security' ? 'bg-red-100 text-red-600' : 
                                   ($notification->type == 'billing' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600'))) }}">
                                <i class="fas {{ $notification->icon ?? 'fa-bell' }}"></i>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $notification->title }}
                                        @if(!$notification->read_at)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                                New
                                            </span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                    <div class="flex items-center space-x-3 mt-2">
                                        <span class="text-xs text-gray-500">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ ucfirst($notification->type) }}
                                        </span>
                                        @if($notification->read_at)
                                            <span class="text-xs text-gray-400">
                                                <i class="fas fa-check mr-1"></i>
                                                Read {{ $notification->read_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1 ml-4 flex-shrink-0">
                                    @if(!$notification->read_at)
                                        <button onclick="markAsRead('{{ $notification->id }}')" 
                                                class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                                title="Mark as Read">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                    @endif
                                    <button onclick="deleteNotification('{{ $notification->id }}')" 
                                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-bell-slash text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No notifications</p>
                    <p class="text-sm text-gray-500 mt-1">You're all caught up!</p>
                </div>
            @endforelse
        </div>
        
        <!-- ===== PAGINATION ===== -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            @if($notifications instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="text-sm text-gray-500">
                    Showing {{ $notifications->firstItem() ?? 0 }} to {{ $notifications->lastItem() ?? 0 }} of {{ $notifications->total() }} results
                </div>
                <div>
                    {{ $notifications->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-sm text-gray-500">
                    Showing {{ $notifications->count() }} results
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="mark-read-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="mark-all-read-form" method="POST" action="{{ route('admin.notifications.mark-all-read') }}" style="display: none;">
    @csrf
    @method('POST')
</form>

<form id="delete-notification-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function markAsRead(id) {
        const form = document.getElementById('mark-read-form');
        form.action = `/admin/notifications/${id}/mark-read`;
        form.submit();
    }

    function markAllAsRead() {
        if (confirm('Mark all notifications as read?')) {
            document.getElementById('mark-all-read-form').submit();
        }
    }

    function deleteNotification(id) {
        if (confirm('Delete this notification?')) {
            const form = document.getElementById('delete-notification-form');
            form.action = `/admin/notifications/${id}`;
            form.submit();
        }
    }
</script>
@endpush

@push('styles')
<style>
    .card-hover {
        transition: all 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
</style>
@endpush
@endsection