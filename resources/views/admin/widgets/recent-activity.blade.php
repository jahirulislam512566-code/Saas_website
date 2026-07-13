<div>
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
    <div class="space-y-4">
        @forelse($activities as $activity)
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas {{ $activity->icon ?? 'fa-clock' }} text-gray-500"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center py-4">No recent activity</p>
        @endforelse
    </div>
</div>