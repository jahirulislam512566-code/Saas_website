@extends('admin.layouts.admin')

@section('title', 'Edit Plan - ' . $plan->name)

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.plans.index') }}" class="text-gray-500 hover:text-gray-700">Plans</a>
        </div>
    </li>
    <li class="text-gray-500">
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span>{{ $plan->name }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Header -->
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Plan</h1>
                <p class="text-gray-500 mt-1">Update subscription plan details</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $plan->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                </span>
                <span class="text-gray-400">•</span>
                <span class="text-sm text-gray-600">
                    <strong>{{ $plan->subscriptions_count ?? 0 }}</strong> subscribers
                </span>
            </div>
        </div>

        <form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="p-8" id="plan-form">
            @csrf
            @method('PUT')

            <div class="space-y-10">
                
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-5 flex items-center gap-3">
                        <i class="fas fa-info-circle text-primary-600"></i>
                        Basic Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Plan Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $plan->name) }}" required
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                Slug <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $plan->slug) }}" required
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-3 rounded-3xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 outline-none transition-all resize-y">{{ old('description', $plan->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-5 flex items-center gap-3">
                        <i class="fas fa-dollar-sign text-primary-600"></i>
                        Pricing
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price_monthly" class="block text-sm font-medium text-gray-700 mb-2">
                                Monthly Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">$</span>
                                <input type="number" name="price_monthly" id="price_monthly" 
                                       value="{{ old('price_monthly', $plan->price_monthly) }}" required step="0.01" min="0"
                                       class="w-full pl-8 pr-4 py-3 rounded-2xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 outline-none"
                                       oninput="calculateSavings()">
                            </div>
                            @error('price_monthly')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="price_yearly" class="block text-sm font-medium text-gray-700 mb-2">
                                Yearly Price
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">$</span>
                                <input type="number" name="price_yearly" id="price_yearly" 
                                       value="{{ old('price_yearly', $plan->price_yearly) }}" step="0.01" min="0"
                                       class="w-full pl-8 pr-4 py-3 rounded-2xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 outline-none"
                                       oninput="calculateSavings()">
                            </div>
                            <div id="savings-display" class="mt-3 text-sm font-medium text-emerald-600 hidden">
                                <i class="fas fa-check-circle mr-1"></i>
                                Save <span id="savings-percentage">0</span>% with yearly billing
                            </div>
                            @error('price_yearly')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                                Currency <span class="text-red-500">*</span>
                            </label>
                            <select name="currency" id="currency" required
                                    class="w-full px-4 py-3 rounded-2xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 outline-none">
                                @foreach(['USD','EUR','GBP','CAD','AUD','JPY','INR','BRL','MXN','SGD','NZD','CHF','SEK','NOK','DKK','PLN','THB','IDR','MYR'] as $curr)
                                    <option value="{{ $curr }}" {{ old('currency', $plan->currency) == $curr ? 'selected' : '' }}>
                                        {{ $curr }}
                                    </option>
                                @endforeach
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="trial_days" class="block text-sm font-medium text-gray-700 mb-2">
                                Trial Days
                            </label>
                            <input type="number" name="trial_days" id="trial_days" 
                                   value="{{ old('trial_days', $plan->trial_days) }}" min="0" max="365"
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 outline-none">
                            @error('trial_days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Features & Limits -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Features -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Features</h3>
                        <div x-data="{ features: @json(old('features', $plan->features ?? [''])) }">
                            <template x-for="(feature, index) in features" :key="index">
                                <div class="flex gap-3 mb-3">
                                    <input type="text" :name="'features[' + index + ']'" 
                                           x-model="features[index]"
                                           class="flex-1 px-4 py-3 rounded-2xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500"
                                           placeholder="e.g., Unlimited Projects">
                                    <button type="button" @click="features.splice(index, 1)" 
                                            class="px-5 text-red-500 hover:bg-red-50 rounded-2xl transition-colors"
                                            :disabled="features.length === 1">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="features.push('')" 
                                    class="text-primary-600 hover:text-primary-700 flex items-center gap-2 text-sm font-medium">
                                <i class="fas fa-plus"></i> Add Feature
                            </button>
                        </div>
                    </div>

                    <!-- Usage Limits -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Usage Limits</h3>
                        <div x-data="{ limits: @json(old('limits', $plan->limits ?? [''])) }">
                            <template x-for="(limit, index) in limits" :key="index">
                                <div class="flex gap-3 mb-3">
                                    <input type="text" :name="'limits[' + index + ']'" 
                                           x-model="limits[index]"
                                           class="flex-1 px-4 py-3 rounded-2xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500"
                                           placeholder="e.g., 50GB Storage">
                                    <button type="button" @click="limits.splice(index, 1)" 
                                            class="px-5 text-red-500 hover:bg-red-50 rounded-2xl transition-colors"
                                            :disabled="limits.length === 1">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="limits.push('')" 
                                    class="text-primary-600 hover:text-primary-700 flex items-center gap-2 text-sm font-medium">
                                <i class="fas fa-plus"></i> Add Limit
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stripe -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-5 flex items-center gap-3">
                        <i class="fas fa-credit-card text-primary-600"></i>
                        Stripe Integration
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Price ID</label>
                            <input type="text" name="stripe_price_id_monthly" 
                                   value="{{ old('stripe_price_id_monthly', $plan->stripe_price_id_monthly) }}"
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500"
                                   placeholder="price_xxxxxxxxxxxxxxxx">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Yearly Price ID</label>
                            <input type="text" name="stripe_price_id_yearly" 
                                   value="{{ old('stripe_price_id_yearly', $plan->stripe_price_id_yearly) }}"
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500"
                                   placeholder="price_xxxxxxxxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                    <div class="space-y-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" 
                                   {{ old('is_active', $plan->is_active) ? 'checked' : '' }}
                                   class="w-5 h-5 text-primary-600 rounded border-gray-300">
                            <span class="text-gray-700">Active (visible to users)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" 
                                   {{ old('is_featured', $plan->is_featured) ? 'checked' : '' }}
                                   class="w-5 h-5 text-primary-600 rounded border-gray-300">
                            <span class="text-gray-700">Featured Plan</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-6 border-t border-gray-100 flex justify-end gap-4">
                    <a href="{{ route('admin.plans.index') }}" 
                       class="px-6 py-3 text-gray-700 hover:bg-gray-100 rounded-2xl font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-medium flex items-center gap-2 transition-all active:scale-95">
                        <i class="fas fa-save"></i>
                        Update Plan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto slug generation
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            if (!slugInput.dataset.manual) {
                slugInput.value = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });

        slugInput.addEventListener('focus', function() {
            this.dataset.manual = 'true';
        });
    }

    // Yearly savings calculator
    function calculateSavings() {
        const monthly = parseFloat(document.getElementById('price_monthly').value) || 0;
        const yearly = parseFloat(document.getElementById('price_yearly').value) || 0;
        const display = document.getElementById('savings-display');
        const percentageEl = document.getElementById('savings-percentage');

        if (monthly > 0 && yearly > 0 && yearly < monthly * 12) {
            const savings = Math.round(((monthly * 12 - yearly) / (monthly * 12)) * 100);
            percentageEl.textContent = savings;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }

    // Initialize savings
    document.addEventListener('DOMContentLoaded', () => {
        calculateSavings();
    });
</script>
@endpush