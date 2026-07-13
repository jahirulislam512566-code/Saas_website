@extends('admin.layouts.admin')

@section('title', 'Activity Logs')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Activity Logs</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Activity Logs</h1>
                <p class="text-sm text-gray-500 mt-1">View all system activities and user actions</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.activities.export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i> Export
                </a>
                <button onclick="clearAllActivities()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i> Clear All
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="action" class="block text-xs font-medium text-gray-700 mb-1">Action</label>
                <select name="action" id="action" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $action)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="user_id" class="block text-xs font-medium text-gray-700 mb-1">User</label>
                <select name="user_id" id="user_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="date_from" class="block text-xs font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" name="date_from" id="date_from" 
                       value="{{ request('date_from') }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>

            <div>
                <label for="date_to" class="block text-xs font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" name="date_to" id="date_to" 
                       value="{{ request('date_to') }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors w-full">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.activities.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors w-full text-center">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Activities List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-clock text-gray-500 mr-2"></i>
                        Activity Logs
                    </h3>
                    <p class="text-sm text-gray-500">Total: {{ $activities->total() }} activities</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-400">
                        <i class="fas fa-sync mr-1"></i>
                        Auto-refresh: 30s
                    </span>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($activities as $activity)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-{{ $activity->color }}-100 flex items-center justify-center">
                                <i class="fas {{ $activity->icon }} text-{{ $activity->color }}-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">
                                    <span class="font-semibold">{{ $activity->user_name }}</span>
                                    <span class="text-gray-600">{{ $activity->description }}</span>
                                    @if($activity->subject_name)
                                        <span class="font-medium text-primary-600">{{ $activity->subject_name }}</span>
                                    @endif
                                </p>
                                <span class="text-xs px-2 py-1 rounded-full bg-{{ $activity->color }}-100 text-{{ $activity->color }}-700">
                                    {{ ucwords(str_replace('_', ' ', $activity->action)) }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-4 mt-1">
                                <span class="text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    <i class="fas fa-location-dot mr-1"></i>
                                    {{ $activity->ip_address }}
                                </span>
                                @if($activity->subject_type)
                                    <span class="text-xs text-gray-400">
                                        <i class="fas fa-cube mr-1"></i>
                                        {{ class_basename($activity->subject_type) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('admin.activities.show', $activity) }}" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">No activities found</p>
                </div>
            @endforelse
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $activities->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function clearAllActivities() {
    if (!confirm('Are you sure you want to clear all activity logs? This action cannot be undone.')) return;
    
    fetch('{{ route("admin.activities.clear") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to clear activities: ' + data.message);
        }
    })
    .catch(error => {
        alert('Failed to clear activities: ' + error.message);
    });
}

// Auto-refresh every 30 seconds
setTimeout(function() {
    location.reload();
}, 30000);
</script>
@endpush
@endsection