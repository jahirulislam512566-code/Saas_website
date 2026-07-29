<!-- admin/users/show.blade.php -->
@extends('layout.app')

@section('title', 'User Profile')
@section('page-title', 'User Profile')
@section('page-subtitle', 'View user details and activity')

@section('content')
<div x-data="userProfile()" x-init="init()">
    <!-- Profile Header -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex flex-wrap items-center gap-6">
                <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4f46e5&color=fff&size=128' }}" 
                     alt="{{ $user->name }}" 
                     class="w-24 h-24 rounded-full border-4 border-indigo-500/20">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                     {{ $user->role === 'admin' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 
                                        ($user->role === 'manager' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' :
                                        ($user->role === 'support' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' :
                                        ($user->role === 'developer' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                        'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'))) }}">
                            <i class="fas fa-{{ $user->role === 'admin' ? 'crown' : 
                                        ($user->role === 'manager' ? 'user-tie' :
                                        ($user->role === 'support' ? 'headset' :
                                        ($user->role === 'developer' ? 'code' : 'edit'))) }}"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                     {{ $user->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                        ($user->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                            <i class="fas fa-{{ $user->status === 'active' ? 'check-circle' : 
                                        ($user->status === 'pending' ? 'clock' : 'times-circle') }}"></i>
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $user->phone }}</p>
                    @endif
                    @if($user->department)
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                            <i class="fas fa-building mr-1"></i> {{ ucfirst($user->department) }}
                        </p>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-all">
                        <i class="fas fa-edit mr-1.5"></i>Edit
                    </a>
                    <a href="{{ route('admin.users.permissions', $user) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                        <i class="fas fa-lock mr-1.5"></i>Permissions
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Stats -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Activity Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Logins</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.logins">0</p>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Actions</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.actions">0</p>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Projects</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.projects">0</p>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tickets</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.tickets">0</p>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Recent Activity</h4>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($activities as $activity)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center
                                             {{ $activity->type === 'login' ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : 
                                                ($activity->type === 'update' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' :
                                                ($activity->type === 'create' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400' :
                                                'bg-gray-100 text-gray-600 dark:bg-gray-900/30 dark:text-gray-400')) }}">
                                    <i class="fas fa-{{ $activity->type === 'login' ? 'sign-in-alt' : 
                                                ($activity->type === 'update' ? 'edit' : 
                                                ($activity->type === 'create' ? 'plus' : 'clock')) }}"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $activity->description }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                            <p>No recent activity</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Info -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Information</h4>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">User ID</span>
                            <span class="text-gray-900 dark:text-white font-mono">#{{ $user->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Joined</span>
                            <span class="text-gray-900 dark:text-white">{{ $user->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Last Login</span>
                            <span class="text-gray-900 dark:text-white">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : 'Never' }}</span>
                        </div>
                        @if($user->last_ip)
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Last IP</span>
                                <span class="text-gray-900 dark:text-white font-mono">{{ $user->last_ip }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Permissions Summary -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-gray-900 dark:text-white">Permissions</h4>
                        <a href="{{ route('admin.users.permissions', $user) }}" 
                           class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                            Manage
                        </a>
                    </div>
                    
                    <div class="space-y-2">
                        @foreach($permissions as $permission)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full
                                             {{ $permission->granted ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400' }}">
                                    {{ $permission->granted ? 'Granted' : 'Denied' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Actions</h4>
                    <div class="space-y-2">
                        @if($user->status === 'active')
                            <button @click="suspendUser()" 
                                    class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 rounded-lg transition-colors">
                                <i class="fas fa-pause"></i> Suspend User
                            </button>
                        @elseif($user->status === 'suspended')
                            <button @click="activateUser()" 
                                    class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                                <i class="fas fa-play"></i> Activate User
                            </button>
                        @endif
                        <button @click="sendResetLink()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-envelope"></i> Send Password Reset
                        </button>
                        <button @click="deleteUser()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                            <i class="fas fa-trash-alt"></i> Delete User
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function userProfile() {
    return {
        stats: @json($stats),
        userId: {{ $user->id }},
        
        suspendUser() {
            if (confirm('Are you sure you want to suspend this user?')) {
                this.updateStatus('suspended');
            }
        },
        
        activateUser() {
            if (confirm('Are you sure you want to activate this user?')) {
                this.updateStatus('active');
            }
        },
        
        updateStatus(status) {
            fetch(`{{ route('admin.users.status', $user) }}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error updating status:', error));
        },
        
        sendResetLink() {
            fetch(`{{ route('admin.users.reset-link', $user) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Password reset link sent successfully!');
                }
            })
            .catch(error => console.error('Error sending reset link:', error));
        },
        
        deleteUser() {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                fetch(`{{ route('admin.users.destroy', $user) }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route("admin.users.index") }}';
                    }
                })
                .catch(error => console.error('Error deleting user:', error));
            }
        }
    }
}
</script>
@endpush