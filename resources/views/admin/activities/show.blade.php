@extends('admin.layouts.admin')

@section('title', 'Activity Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.activities.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i> Back to Activities
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Activity Details</h2>
                    <p class="text-sm text-gray-500 mt-1">View complete activity information</p>
                </div>
                <span class="text-xs px-3 py-1 rounded-full bg-{{ $activity->color }}-100 text-{{ $activity->color }}-700 font-medium">
                    {{ ucwords(str_replace('_', ' ', $activity->action)) }}
                </span>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Activity Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500">User</p>
                    <p class="text-sm font-medium text-gray-900">{{ $activity->user_name }}</p>
                    <p class="text-xs text-gray-500">{{ $activity->user_email }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500">Action</p>
                    <p class="text-sm font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $activity->action)) }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500">Subject</p>
                    <p class="text-sm font-medium text-gray-900">{{ $activity->subject_name ?? 'N/A' }}</p>
                    @if($activity->subject_type)
                        <p class="text-xs text-gray-500">{{ class_basename($activity->subject_type) }}</p>
                    @endif
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500">Timestamp</p>
                    <p class="text-sm font-medium text-gray-900">{{ $activity->created_at->format('F j, Y g:i A') }}</p>
                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500">IP Address</p>
                    <p class="text-sm font-medium text-gray-900">{{ $activity->ip_address ?? 'N/A' }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500">User Agent</p>
                    <p class="text-sm font-medium text-gray-900 text-xs break-all">{{ $activity->user_agent ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500">Description</p>
                <p class="text-sm text-gray-900 mt-1">{{ $activity->description }}</p>
            </div>

            <!-- Properties -->
            @if($activity->properties)
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500">Properties</p>
                    <pre class="text-sm bg-gray-100 p-3 rounded-lg mt-2 overflow-x-auto">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif

            <!-- Changes -->
            @if($activity->old_values || $activity->new_values)
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500">Changes</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        @if($activity->old_values)
                            <div>
                                <p class="text-xs text-red-600">Old Values</p>
                                <pre class="text-sm bg-red-50 p-3 rounded-lg mt-1 overflow-x-auto">{{ json_encode($activity->old_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif
                        @if($activity->new_values)
                            <div>
                                <p class="text-xs text-green-600">New Values</p>
                                <pre class="text-sm bg-green-50 p-3 rounded-lg mt-1 overflow-x-auto">{{ json_encode($activity->new_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div>
                    <a href="{{ route('admin.activities.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
                <div class="flex items-center space-x-3">
                    <form action="{{ route('admin.activities.delete', $activity) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this activity?')" 
                                class="px-4 py-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection