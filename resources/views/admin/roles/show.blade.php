{{-- resources/views/admin/roles/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $role->display_name)

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.roles.index') }}" class="text-gray-500 hover:text-gray-700">Roles</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">{{ $role->display_name }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $role->display_name }}</h1>
            <div class="flex items-center space-x-3 mt-1">
                <span class="text-sm text-gray-500">Slug: {{ $role->name }}</span>
                <span class="text-gray-300">|</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $role->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.roles.permissions', $role) }}" 
               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-lock mr-2"></i> Manage Permissions
            </a>
            <a href="{{ route('admin.roles.edit', $role) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-pen mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.roles.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Users with this role</p>
            <p class="text-xl font-bold text-gray-900">{{ $role->users()->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Permissions</p>
            <p class="text-xl font-bold text-purple-600">{{ $role->permissions()->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Created</p>
            <p class="text-xl font-bold text-gray-900">{{ $role->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <!-- Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Description</h3>
                @if($role->description)
                    <p class="text-gray-600">{{ $role->description }}</p>
                @else
                    <p class="text-gray-400 italic">No description provided</p>
                @endif
            </div>

            <!-- Permissions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Permissions</h3>
                @if($role->permissions->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($role->permissions as $permission)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $permission->display_name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 italic">No permissions assigned</p>
                @endif
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Role Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-500">ID</dt>
                        <dd class="text-sm text-gray-900">#{{ $role->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Name</dt>
                        <dd class="text-sm text-gray-900">{{ $role->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Display Name</dt>
                        <dd class="text-sm text-gray-900">{{ $role->display_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Status</dt>
                        <dd class="text-sm text-gray-900">{{ $role->is_active ? 'Active' : 'Inactive' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Created</dt>
                        <dd class="text-sm text-gray-900">{{ $role->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $role->updated_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Users with this role -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Users ({{ $role->users()->count() }})</h3>
                @if($role->users()->count() > 0)
                    <ul class="space-y-2">
                        @foreach($role->users()->limit(5)->get() as $user)
                            <li>
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="flex items-center space-x-2 text-sm text-gray-700 hover:text-primary-600 transition-colors">
                                    <x-admin.avatar :src="$user->avatar" :name="$user->name" size="xs" />
                                    <span>{{ $user->name }}</span>
                                </a>
                            </li>
                        @endforeach
                        @if($role->users()->count() > 5)
                            <li class="text-xs text-gray-400">+ {{ $role->users()->count() - 5 }} more users</li>
                        @endif
                    </ul>
                @else
                    <p class="text-sm text-gray-400">No users assigned to this role</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection