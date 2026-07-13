<div>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Recent Subscriptions</h3>
        <a href="{{ route('admin.subscriptions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
            View All <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($subscriptions as $subscription)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $subscription->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $subscription->plan->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($subscription->status === 'trialing' ? 'bg-blue-100 text-blue-800' : 
                                   ($subscription->status === 'canceled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($subscription->status ?? 'unknown') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $subscription->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No recent subscriptions</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>