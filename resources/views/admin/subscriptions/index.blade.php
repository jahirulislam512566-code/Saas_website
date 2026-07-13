{{-- resources/views/admin/subscriptions/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Subscriptions Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Subscriptions</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Subscriptions</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all user subscriptions and billing</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.subscriptions.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <a href="{{ route('admin.subscriptions.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Subscription
            </a>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Trialing</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['trialing'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Past Due</p>
                    <p class="text-xl font-bold text-orange-600">{{ $stats['past_due'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Monthly Revenue</p>
                    <p class="text-xl font-bold text-purple-600">${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTERS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" placeholder="User or plan..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="trialing" {{ request('status') == 'trialing' ? 'selected' : '' }}>Trialing</option>
                    <option value="past_due" {{ request('status') == 'past_due' ? 'selected' : '' }}>Past Due</option>
                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="incomplete" {{ request('status') == 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle</label>
                <select name="billing_cycle" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Cycles</option>
                    <option value="monthly" {{ request('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ request('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    <option value="quarterly" {{ request('billing_cycle') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <select name="period" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Time</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="quarter" {{ request('period') == 'quarter' ? 'selected' : '' }}>This Quarter</option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>This Year</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.subscriptions.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- ===== SUBSCRIPTIONS TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Billing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subscriptions as $subscription)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="selected_ids[]" value="{{ $subscription->id }}" 
                                       class="subscription-checkbox rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <x-admin.avatar :src="$subscription->user->avatar ?? null" :name="$subscription->user->name ?? 'N/A'" size="sm" />
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $subscription->user->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $subscription->user->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $subscription->plan->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">ID: #{{ $subscription->id }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">${{ number_format($subscription->amount, 2) }}</p>
                                <p class="text-xs text-gray-500">{{ $subscription->currency ?? 'USD' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($subscription->billing_cycle) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'trialing' => 'bg-blue-100 text-blue-800',
                                        'past_due' => 'bg-orange-100 text-orange-800',
                                        'canceled' => 'bg-red-100 text-red-800',
                                        'unpaid' => 'bg-red-100 text-red-800',
                                        'incomplete' => 'bg-gray-100 text-gray-800',
                                        'paused' => 'bg-yellow-100 text-yellow-800',
                                    ];
                                    $color = $statusColors[$subscription->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst(str_replace('_', ' ', $subscription->status)) }}
                                </span>
                                @if($subscription->status === 'trialing')
                                    <span class="text-xs text-gray-500 block mt-1">
                                        {{ $subscription->trial_ends_at?->diffForHumans() }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500">
                                    <div>{{ $subscription->current_period_start?->format('M d, Y') }}</div>
                                    <div class="text-xs">to {{ $subscription->current_period_end?->format('M d, Y') }}</div>
                                    @if($subscription->current_period_end && $subscription->current_period_end->isPast())
                                        <span class="text-xs text-red-600 font-medium">(Expired)</span>
                                    @elseif($subscription->current_period_end && $subscription->current_period_end->diffInDays(now()) <= 7)
                                        <span class="text-xs text-orange-600 font-medium">(Expiring soon)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('admin.subscriptions.show', $subscription) }}" 
                                       class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.subscriptions.edit', $subscription) }}" 
                                       class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                       title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </a>
                                    @if($subscription->status !== 'canceled' && $subscription->status !== 'unpaid')
                                        <button onclick="cancelSubscription('{{ $subscription->id }}', '{{ $subscription->user->name ?? 'User' }}')" 
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                title="Cancel Subscription">
                                            <i class="fas fa-ban text-sm"></i>
                                        </button>
                                    @endif
                                    @if($subscription->status === 'active')
                                        <button onclick="pauseSubscription('{{ $subscription->id }}')" 
                                                class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" 
                                                title="Pause Subscription">
                                            <i class="fas fa-pause text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-receipt text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No subscriptions found</p>
                                <p class="text-sm mt-1">Try adjusting your filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- ===== BULK ACTIONS & PAGINATION ===== -->
        <div class="px-6 py-4 border-t border-gray-200 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <select id="bulk-action" class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                    <option value="">Bulk Actions</option>
                    <option value="cancel">Cancel Selected</option>
                    <option value="pause">Pause Selected</option>
                    <option value="delete">Delete Selected</option>
                </select>
                <button onclick="applyBulkAction()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm">
                    Apply
                </button>
                <span id="selected-count" class="text-sm text-gray-500">0 selected</span>
            </div>
            
            <div>
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="cancel-form" method="POST" style="display: none;">
    @csrf
    @method('POST')
</form>

<form id="pause-form" method="POST" style="display: none;">
    @csrf
    @method('POST')
</form>

<form id="bulk-form" method="POST" style="display: none;">
    @csrf
    @method('POST')
    <input type="hidden" name="ids" id="bulk-ids">
</form>

@push('scripts')
<script>
    // Select All
    document.getElementById('select-all').addEventListener('change', function() {
        document.querySelectorAll('.subscription-checkbox').forEach(cb => cb.checked = this.checked);
        updateSelectedCount();
    });

    // Update selected count
    document.querySelectorAll('.subscription-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const count = document.querySelectorAll('.subscription-checkbox:checked').length;
        document.getElementById('selected-count').textContent = count + ' selected';
    }

    // Cancel subscription
    function cancelSubscription(id, userName) {
        if (confirm(`Are you sure you want to cancel the subscription for ${userName}?`)) {
            const form = document.getElementById('cancel-form');
            form.action = `/admin/subscriptions/${id}/cancel`;
            form.submit();
        }
    }

    // Pause subscription
    function pauseSubscription(id) {
        if (confirm('Are you sure you want to pause this subscription?')) {
            const form = document.getElementById('pause-form');
            form.action = `/admin/subscriptions/${id}/pause`;
            form.submit();
        }
    }

    // Bulk actions
    function applyBulkAction() {
        const action = document.getElementById('bulk-action').value;
        const selected = document.querySelectorAll('.subscription-checkbox:checked');
        
        if (!action) {
            alert('Please select a bulk action.');
            return;
        }
        
        if (selected.length === 0) {
            alert('Please select at least one subscription.');
            return;
        }
        
        if (!confirm(`Are you sure you want to ${action} ${selected.length} subscription(s)?`)) {
            return;
        }
        
        const ids = Array.from(selected).map(cb => cb.value);
        const form = document.getElementById('bulk-form');
        document.getElementById('bulk-ids').value = JSON.stringify(ids);
        form.action = `/admin/subscriptions/bulk-${action}`;
        form.submit();
    }
</script>
@endpush

@push('styles')
<style>
    .card-hover {
        transition: all 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
</style>
@endpush
@endsection