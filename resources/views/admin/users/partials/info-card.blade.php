{{-- resources/views/admin/users/partials/info-card.blade.php --}}
@props(['user'])

<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center space-x-4">
        <x-admin.avatar :src="$user->avatar" :name="$user->name" size="lg" />
        <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
            <div class="flex items-center space-x-2 mt-1">
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
    
    <div class="mt-4 grid grid-cols-2 gap-4">
        <div class="bg-gray-50 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $user->posts()->count() }}</p>
            <p class="text-xs text-gray-500">Posts</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $user->subscriptions()->count() }}</p>
            <p class="text-xs text-gray-500">Subscriptions</p>
        </div>
    </div>
</div>