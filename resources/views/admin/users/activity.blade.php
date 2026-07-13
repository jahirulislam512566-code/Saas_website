{{-- resources/views/admin/users/activity.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $user->name . ' - Activity')

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
            <a href="{{ route('admin.users.show', $user) }}" class="text-gray-500 hover:text-gray-700">{{ $user->name }}</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Activity</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }} - Activity Log</h1>
            <p class="text-sm text-gray-500 mt-1">All activities performed by this user</p>
        </div>
        <a href="{{ route('admin.users.show', $user) }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Back to Profile
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="divide-y divide-gray-200">
            @forelse($activities as $activity)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-circle text-gray-400"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                            <div class="flex items-center space-x-3 mt-1">
                                <span class="text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    <i class="fas fa-location-dot mr-1"></i>
                                    {{ $activity->ip_address ?? 'N/A' }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    <i class="fas fa-tag mr-1"></i>
                                    {{ $activity->action }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-clock text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium">No activity found</p>
                    <p class="text-sm mt-1">This user hasn't performed any actions yet</p>
                </div>
            @endforelse
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection