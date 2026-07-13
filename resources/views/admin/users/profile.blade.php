{{-- resources/views/admin/users/profile.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $user->name . ' - Profile')

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
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Profile</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center space-x-6">
                <x-admin.avatar :src="$user->avatar" :name="$user->name" size="xl" />
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-500">{{ $user->email }}</p>
                    <div class="flex items-center space-x-2 mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                            {{ ucfirst($user->role ?? 'User') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Personal Information</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <dt class="text-sm text-gray-500">Full Name</dt>
                            <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <dt class="text-sm text-gray-500">Role</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($user->role ?? 'User') }}</dd>
                        </div>
                        <div class="flex justify-between py-1">
                            <dt class="text-sm text-gray-500">Status</dt>
                            <dd class="text-sm text-gray-900">{{ $user->is_active ? 'Active' : 'Inactive' }}</dd>
                        </div>
                    </dl>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Account Information</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <dt class="text-sm text-gray-500">Joined</dt>
                            <dd class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <dt class="text-sm text-gray-500">Last Updated</dt>
                            <dd class="text-sm text-gray-900">{{ $user->updated_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <dt class="text-sm text-gray-500">Total Posts</dt>
                            <dd class="text-sm text-gray-900">{{ $user->posts()->count() }}</dd>
                        </div>
                        <div class="flex justify-between py-1">
                            <dt class="text-sm text-gray-500">Subscriptions</dt>
                            <dd class="text-sm text-gray-900">{{ $user->subscriptions()->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection