{{-- resources/views/admin/users/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $user->name)

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-700">Users</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">{{ $user->name }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <x-admin.avatar :src="$user->avatar" :name="$user->name" size="lg" />
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-sm text-gray-500">{{ $user->email }}</span>
                    <span class="text-gray-300">|</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="text-xs text-gray-400">ID: #{{ $user->id }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-pen mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Posts</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total_posts'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Subscriptions</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['total_subscriptions'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Active Subscriptions</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['active_subscriptions'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Activities</p>
            <p class="text-xl font-bold text-purple-600">{{ $stats['total_activities'] ?? 0 }}</p>
        </div>
    </div>

    <!-- User Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">Recent Activity</h3>
                    <a href="{{ route('admin.users.activity', $user) }}" class="text-xs text-primary-600 hover:text-primary-700">
                        View All
                    </a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($user->activities as $activity)
                        <div class="px-6 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $activity->action }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">No recent activity</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">User Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-500">Full Name</dt>
                        <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Role</dt>
                        <dd class="text-sm text-gray-900">{{ ucfirst($user->role ?? 'User') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Joined</dt>
                        <dd class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $user->updated_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection