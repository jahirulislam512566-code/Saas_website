{{-- resources/views/admin/users/subscriptions.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $user->name . ' - Subscriptions')

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
            <span class="text-gray-500">Subscriptions</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }} - Subscriptions</h1>
            <p class="text-sm text-gray-500 mt-1">Manage subscriptions for this user</p>
        </div>
        <a href="{{ route('admin.users.show', $user) }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Back to Profile
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Billing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subscriptions as $subscription)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $subscription->plan->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $subscription->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $subscription->currency ?? '$' }}{{ number_format($subscription->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ ucfirst($subscription->billing_cycle ?? 'monthly') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($subscription->current_period_start && $subscription->current_period_end)
                                    {{ $subscription->current_period_start->format('M d, Y') }} - 
                                    {{ $subscription->current_period_end->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.subscriptions.show', $subscription) }}" 
                                       class="text-gray-400 hover:text-blue-600 transition-colors" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($subscription->status != 'canceled')
                                        <form action="{{ route('admin.subscriptions.cancel', $subscription) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Cancel">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-receipt text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No subscriptions found</p>
                                <p class="text-sm mt-1">This user has no active subscriptions</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
@endsection