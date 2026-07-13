@extends('admin.layouts.admin')

@section('title', 'Subscription Plans')

@section('breadcrumb')
    <li class="text-gray-500">
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span>Plans</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Subscription Plans</h1>
            <p class="text-gray-500 mt-1">Manage pricing tiers and features</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.plans.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-medium transition-all active:scale-95">
                <i class="fas fa-plus mr-2"></i>
                New Plan
            </a>
            
            @if(isset($plans) && $plans->count() > 0)
                <a href="{{ route('admin.plans.export') }}" 
                   class="inline-flex items-center px-5 py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-2xl font-medium transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Export
                </a>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Total Plans</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-crown text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Active Plans</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Total Subscribers</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['subscribers'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Est. Monthly Revenue</p>
                    <p class="text-3xl font-bold text-amber-600 mt-2">
                        ${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Plans Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($plans as $plan)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                
                <!-- Card Header -->
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-start">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-xl text-gray-900 truncate">{{ $plan->name }}</h3>
                            <div class="flex items-center gap-2 mt-2">
                                @if($plan->is_featured)
                                    <span class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-2xl flex items-center">
                                        <i class="fas fa-star mr-1"></i> Featured
                                    </span>
                                @endif
                                <span class="px-3 py-1 text-xs font-medium rounded-2xl
                                    {{ $plan->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="mt-6">
                        <div class="flex items-baseline">
                            <span class="text-4xl font-bold text-gray-900">
                                {{ $plan->currency ?? '$' }}{{ number_format($plan->price_monthly, 2) }}
                            </span>
                            <span class="ml-1.5 text-gray-500">/mo</span>
                        </div>
                        @if($plan->price_yearly)
                            @php
                                $savings = $plan->price_monthly > 0 
                                    ? round((1 - $plan->price_yearly / ($plan->price_monthly * 12)) * 100) 
                                    : 0;
                            @endphp
                            @if($savings > 0)
                                <p class="text-emerald-600 text-sm font-medium mt-1">
                                    ${{ number_format($plan->price_yearly, 2) }}/year • Save {{ $savings }}%
                                </p>
                            @endif
                        @endif
                    </div>

                    @if($plan->description)
                        <p class="mt-4 text-sm text-gray-600 line-clamp-2">
                            {{ Str::limit($plan->description, 110) }}
                        </p>
                    @endif
                </div>

                <!-- Features -->
                <div class="p-6 border-b border-gray-100 min-h-[140px]">
                    <p class="uppercase text-xs font-semibold tracking-widest text-gray-500 mb-4">What's Included</p>
                    <div class="space-y-2.5">
                        @if($plan->features && count($plan->features) > 0)
                            @foreach(array_slice($plan->features, 0, 4) as $feature)
                                <div class="flex items-start text-sm text-gray-600">
                                    <i class="fas fa-check text-emerald-500 mt-0.5 mr-2 flex-shrink-0"></i>
                                    <span class="truncate">{{ $feature }}</span>
                                </div>
                            @endforeach
                            @if(count($plan->features) > 4)
                                <p class="text-xs text-gray-400">+ {{ count($plan->features) - 4 }} more features</p>
                            @endif
                        @else
                            <p class="text-gray-400 text-sm italic">No features configured</p>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-5 bg-gray-50 flex items-center justify-between">
                    <div class="text-xs text-gray-500 flex items-center gap-4">
                        <span><i class="fas fa-clock"></i> {{ $plan->trial_days }} days trial</span>
                        <span>{{ $plan->subscriptions_count ?? 0 }} users</span>
                    </div>
                    
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.plans.edit', $plan) }}" 
                           class="p-2.5 text-gray-400 hover:text-primary-600 hover:bg-white rounded-2xl transition-colors"
                           title="Edit">
                            <i class="fas fa-pen"></i>
                        </a>
                        
                        <button onclick="togglePlan({{ $plan->id }}, '{{ $plan->name }}')"
                                class="p-2.5 {{ $plan->is_active ? 'text-amber-500 hover:text-amber-600' : 'text-emerald-500 hover:text-emerald-600' }} hover:bg-white rounded-2xl transition-colors"
                                title="{{ $plan->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="fas {{ $plan->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                        </button>
                        
                        <button onclick="duplicatePlan({{ $plan->id }}, '{{ $plan->name }}')"
                                class="p-2.5 text-gray-400 hover:text-blue-600 hover:bg-white rounded-2xl transition-colors"
                                title="Duplicate">
                            <i class="fas fa-copy"></i>
                        </button>
                        
                        <button onclick="deletePlan({{ $plan->id }}, '{{ $plan->name }}', {{ $plan->subscriptions_count ?? 0 }})"
                                class="p-2.5 text-gray-400 hover:text-red-600 hover:bg-white rounded-2xl transition-colors"
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20">
                <div class="text-center">
                    <i class="fas fa-crown text-6xl text-gray-200"></i>
                    <h3 class="mt-6 text-xl font-medium text-gray-900">No Plans Yet</h3>
                    <p class="text-gray-500 mt-2">Create your first subscription plan to get started.</p>
                    <a href="{{ route('admin.plans.create') }}" 
                       class="inline-block mt-6 px-6 py-3 bg-primary-600 text-white rounded-2xl hover:bg-primary-700">
                        Create First Plan
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($plans ?? false && method_exists($plans, 'links'))
        <div class="flex justify-center mt-8">
            {{ $plans->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function togglePlan(id, name) {
        if (confirm(`Toggle status for "${name}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/plans/${id}/toggle`;
            form.innerHTML = `@csrf @method('PATCH')`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function duplicatePlan(id, name) {
        if (confirm(`Create a duplicate of "${name}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/plans/${id}/duplicate`;
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function deletePlan(id, name, subscribers) {
        if (subscribers > 0) {
            alert(`Cannot delete "${name}" - it has ${subscribers} active subscribers.`);
            return;
        }
        if (confirm(`Delete plan "${name}" permanently?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/plans/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush