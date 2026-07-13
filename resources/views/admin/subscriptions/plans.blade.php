{{-- resources/views/admin/subscriptions/plans.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Subscription Plans')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.subscriptions.index') }}" class="text-gray-500 hover:text-gray-700">Subscriptions</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Plans</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Subscription Plans</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your subscription plans and pricing</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.plans.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <a href="{{ route('admin.plans.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Plan
            </a>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Plans</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Active Plans</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Subscribers</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['subscribers'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Monthly Revenue</p>
            <p class="text-xl font-bold text-purple-600">${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- ===== PLANS GRID ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($plans as $plan)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                <!-- Plan Header -->
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h3>
                            @if($plan->is_featured)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                    <i class="fas fa-star mr-1"></i> Featured
                                </span>
                            @endif
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="mt-4">
                        <div class="flex items-baseline">
                            <span class="text-3xl font-bold text-gray-900">${{ number_format($plan->price_monthly, 2) }}</span>
                            <span class="text-sm text-gray-500 ml-1">/month</span>
                        </div>
                        @if($plan->price_yearly)
                            <div class="text-sm text-gray-500">
                                ${{ number_format($plan->price_yearly, 2) }}/year 
                                <span class="text-green-600 font-medium">
                                    (Save {{ round((1 - $plan->price_yearly / ($plan->price_monthly * 12)) * 100) }}%)
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    @if($plan->description)
                        <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $plan->description }}</p>
                    @endif
                </div>
                
                <!-- Features -->
                <div class="p-6 border-b border-gray-200 min-h-[120px]">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Features</p>
                    <div class="space-y-2">
                        @if($plan->features && count($plan->features) > 0)
                            @foreach(array_slice($plan->features, 0, 5) as $feature)
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-check text-green-500 mr-2 flex-shrink-0"></i>
                                    <span class="text-gray-600">{{ $feature }}</span>
                                </div>
                            @endforeach
                            @if(count($plan->features) > 5)
                                <div class="text-xs text-gray-400">+ {{ count($plan->features) - 5 }} more features</div>
                            @endif
                        @else
                            <p class="text-sm text-gray-400">No features listed</p>
                        @endif
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="p-4 bg-gray-50 flex items-center justify-between">
                    <span class="text-xs text-gray-500">{{ $plan->trial_days }} day trial</span>
                    <div class="flex items-center space-x-1">
                        <a href="{{ route('admin.plans.edit', $plan) }}" 
                           class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                           title="Edit">
                            <i class="fas fa-pen text-sm"></i>
                        </a>
                        <button onclick="duplicatePlan('{{ $plan->id }}')" 
                                class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                title="Duplicate">
                            <i class="fas fa-copy text-sm"></i>
                        </button>
                        <button onclick="deletePlan('{{ $plan->id }}', '{{ $plan->name }}')" 
                                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                title="Delete">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-crown text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No plans found</p>
                    <p class="text-sm text-gray-500 mt-1">Create your first subscription plan</p>
                    <a href="{{ route('admin.plans.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Create Plan
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- ===== PAGINATION ===== -->
    <div class="flex items-center justify-between">
        @if($plans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="text-sm text-gray-500">
                Showing {{ $plans->firstItem() ?? 0 }} to {{ $plans->lastItem() ?? 0 }} of {{ $plans->total() }} results
            </div>
            <div>
                {{ $plans->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-sm text-gray-500">
                Showing {{ $plans->count() }} results
            </div>
        @endif
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="duplicate-form" method="POST" style="display: none;">
    @csrf
</form>

<form id="delete-plan-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function duplicatePlan(planId) {
        if (confirm('Duplicate this plan?')) {
            const form = document.getElementById('duplicate-form');
            form.action = `/admin/plans/${planId}/duplicate`;
            form.submit();
        }
    }

    function deletePlan(planId, planName) {
        if (confirm(`Delete plan "${planName}"?`)) {
            const form = document.getElementById('delete-plan-form');
            form.action = `/admin/plans/${planId}`;
            form.submit();
        }
    }
</script>
@endpush

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
@endsection