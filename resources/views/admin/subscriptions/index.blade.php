<!-- admin/subscriptions/index.blade.php -->
@extends('layout.app')

@section('title', 'Subscriptions')
@section('page-title', 'Subscriptions')
@section('page-subtitle', 'Manage all customer subscriptions and plans')

@section('content')
<div x-data="subscriptionManager()" x-init="init()">
    <!-- Subscription Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                    <i class="fas fa-layer-group text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Active</p>
                    <p class="text-2xl font-bold text-green-600" x-text="formatNumber(stats.active)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                <span x-text="stats.active_percent + '%'"></span> of total
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Trial</p>
                    <p class="text-2xl font-bold text-blue-600" x-text="formatNumber(stats.trial)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                    <i class="fas fa-flask text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600" x-text="formatNumber(stats.pending)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Paused</p>
                    <p class="text-2xl font-bold text-purple-600" x-text="formatNumber(stats.paused)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-pause-circle text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">MRR</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.mrr)">$0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <div class="mt-1 text-xs text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.mrr_growth + '%'"></span> vs last month
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</label>
                <select x-model="filters.status" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="trial">Trial</option>
                    <option value="pending">Pending</option>
                    <option value="paused">Paused</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="expired">Expired</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Plan:</label>
                <select x-model="filters.plan" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Plans</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="filters.search" 
                           placeholder="Search subscriptions..." 
                           class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <button @click="applyFilters()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-filter mr-1.5"></i>Apply
            </button>
            <button @click="resetFilters()" 
                    class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                <i class="fas fa-undo mr-1.5"></i>Reset
            </button>
            
            <button @click="openCreateModal()" 
                    class="px-4 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all ml-auto">
                <i class="fas fa-plus mr-1.5"></i>New Subscription
            </button>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">
                            <input type="checkbox" @click="toggleAll" class="rounded border-gray-300 dark:border-gray-700">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Plan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Started</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Expires</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="subscription in filteredSubscriptions" :key="subscription.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <input type="checkbox" x-model="selectedSubscriptions" :value="subscription.id" 
                                       class="rounded border-gray-300 dark:border-gray-700">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <img :src="subscription.customer?.avatar || 'https://ui-avatars.com/api/?name=' + (subscription.customer?.name || 'Unknown')" 
                                         alt="" class="w-6 h-6 rounded-full">
                                    <div>
                                        <span class="text-gray-900 dark:text-white" x-text="subscription.customer?.name || 'Unknown'"></span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="subscription.customer?.email || ''"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-gray-900 dark:text-white" x-text="subscription.plan?.name || 'Unknown'"></span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-1" x-text="subscription.plan?.interval || ''"></span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-white" x-text="formatCurrency(subscription.price)"></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="{
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': subscription.status === 'active',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': subscription.status === 'trial',
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': subscription.status === 'pending',
                                        'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400': subscription.status === 'paused',
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': subscription.status === 'cancelled',
                                        'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400': subscription.status === 'expired'
                                      }">
                                    <i :class="{
                                        'fas fa-check-circle': subscription.status === 'active',
                                        'fas fa-flask': subscription.status === 'trial',
                                        'fas fa-clock': subscription.status === 'pending',
                                        'fas fa-pause-circle': subscription.status === 'paused',
                                        'fas fa-times-circle': subscription.status === 'cancelled',
                                        'fas fa-hourglass-end': subscription.status === 'expired'
                                    }"></i>
                                    <span x-text="subscription.status"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs" x-text="formatDate(subscription.started_at)"></td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs" x-text="formatDate(subscription.expires_at)"></td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="viewSubscription(subscription)" 
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button @click="editSubscription(subscription)" 
                                            class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button @click="renewSubscription(subscription)" x-show="subscription.status === 'active' || subscription.status === 'trial'"
                                            class="p-1.5 text-gray-400 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-sync text-xs"></i>
                                    </button>
                                    <button @click="deleteSubscription(subscription)" 
                                            class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredSubscriptions.length === 0">
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-sync-alt text-3xl text-gray-300 dark:text-gray-700 mb-2"></i>
                                <p>No subscriptions found</p>
                                <p class="text-xs mt-1">Try adjusting your filters or create a new subscription</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Showing <span x-text="filteredSubscriptions.length"></span> of <span x-text="subscriptions.length"></span> subscriptions
            </div>
            @include('admin.partials.pagination', ['paginator' => $subscriptions])
        </div>
    </div>

    <!-- View Subscription Modal -->
    <div x-show="showViewModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.away="showViewModal = false">
        
        <div x-show="showViewModal"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Subscription Details</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="'#' + selectedSubscription?.id"></p>
                </div>
                <button @click="showViewModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <template x-if="selectedSubscription">
                    <div class="space-y-6">
                        <!-- Customer Info -->
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <img :src="selectedSubscription.customer?.avatar || 'https://ui-avatars.com/api/?name=' + (selectedSubscription.customer?.name || 'Unknown')" 
                                 alt="" class="w-12 h-12 rounded-full">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white" x-text="selectedSubscription.customer?.name || 'Unknown'"></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="selectedSubscription.customer?.email || ''"></p>
                            </div>
                        </div>

                        <!-- Subscription Info -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Plan</label>
                                <p class="mt-1 font-medium text-gray-900 dark:text-white" x-text="selectedSubscription.plan?.name || 'Unknown'"></p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</label>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(selectedSubscription.price)"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</label>
                                <p class="mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                          :class="{
                                            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': selectedSubscription.status === 'active',
                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': selectedSubscription.status === 'trial',
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': selectedSubscription.status === 'pending',
                                            'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400': selectedSubscription.status === 'paused',
                                            'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': selectedSubscription.status === 'cancelled',
                                            'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400': selectedSubscription.status === 'expired'
                                          }">
                                        <span x-text="selectedSubscription.status"></span>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Interval</label>
                                <p class="mt-1 text-gray-900 dark:text-white" x-text="selectedSubscription.plan?.interval || 'N/A'"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Started</label>
                                <p class="mt-1 text-gray-900 dark:text-white" x-text="formatDateTime(selectedSubscription.started_at)"></p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Expires</label>
                                <p class="mt-1 text-gray-900 dark:text-white" x-text="formatDateTime(selectedSubscription.expires_at)"></p>
                            </div>
                        </div>

                        <div x-show="selectedSubscription.cancelled_at">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cancelled At</label>
                            <p class="mt-1 text-gray-900 dark:text-white" x-text="formatDateTime(selectedSubscription.cancelled_at)"></p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                            <button @click="showViewModal = false" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                Close
                            </button>
                            <button @click="renewSubscription(selectedSubscription)" x-show="selectedSubscription.status === 'active' || selectedSubscription.status === 'trial'"
                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm transition-all">
                                <i class="fas fa-sync mr-1.5"></i>Renew
                            </button>
                            <button @click="cancelSubscription(selectedSubscription)" x-show="selectedSubscription.status === 'active' || selectedSubscription.status === 'trial'"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm transition-all">
                                <i class="fas fa-times mr-1.5"></i>Cancel
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Create/Edit Subscription Modal -->
    <div x-show="showCreateModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.away="showCreateModal = false">
        
        <div x-show="showCreateModal"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="editing ? 'Edit Subscription' : 'New Subscription'"></h3>
                <button @click="showCreateModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <form @submit.prevent="saveSubscription()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer *</label>
                        <select x-model="form.customer_id" required
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select customer...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Plan *</label>
                        <select x-model="form.plan_id" required
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select plan...</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ $plan->price }}/{{ $plan->interval }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price</label>
                            <input type="number" x-model="form.price" step="0.01"
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select x-model="form.status"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="active">Active</option>
                                <option value="trial">Trial</option>
                                <option value="pending">Pending</option>
                                <option value="paused">Paused</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                            <input type="datetime-local" x-model="form.started_at"
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expiry Date</label>
                            <input type="datetime-local" x-model="form.expires_at"
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                        <textarea x-model="form.notes" rows="2"
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                        <button type="button" @click="showCreateModal = false" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-all">
                            <i class="fas fa-save mr-1.5"></i>
                            <span x-text="editing ? 'Update' : 'Create'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function subscriptionManager() {
    return {
        subscriptions: @json($subscriptions->items()),
        stats: @json($stats),
        plans: @json($plans),
        customers: @json($customers),
        filters: {
            status: 'all',
            plan: 'all',
            search: ''
        },
        selectedSubscriptions: [],
        selectedSubscription: null,
        showViewModal: false,
        showCreateModal: false,
        editing: false,
        form: {
            id: null,
            customer_id: '',
            plan_id: '',
            price: '',
            status: 'active',
            started_at: '',
            expires_at: '',
            notes: ''
        },
        
        get filteredSubscriptions() {
            let subscriptions = this.subscriptions;
            
            if (this.filters.status !== 'all') {
                subscriptions = subscriptions.filter(s => s.status === this.filters.status);
            }
            
            if (this.filters.plan !== 'all') {
                subscriptions = subscriptions.filter(s => s.plan_id == this.filters.plan);
            }
            
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                subscriptions = subscriptions.filter(s => 
                    s.customer?.name?.toLowerCase().includes(search) ||
                    s.customer?.email?.toLowerCase().includes(search) ||
                    s.plan?.name?.toLowerCase().includes(search)
                );
            }
            
            return subscriptions;
        },
        
        applyFilters() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.subscriptions.index') }}?${params.toString()}`;
        },
        
        resetFilters() {
            this.filters = {
                status: 'all',
                plan: 'all',
                search: ''
            };
            this.applyFilters();
        },
        
        toggleAll(e) {
            const checked = e.target.checked;
            this.selectedSubscriptions = checked ? this.filteredSubscriptions.map(s => s.id) : [];
        },
        
        viewSubscription(subscription) {
            this.selectedSubscription = subscription;
            this.showViewModal = true;
        },
        
        openCreateModal() {
            this.editing = false;
            this.form = {
                id: null,
                customer_id: '',
                plan_id: '',
                price: '',
                status: 'active',
                started_at: new Date().toISOString().slice(0, 16),
                expires_at: '',
                notes: ''
            };
            this.showCreateModal = true;
        },
        
        editSubscription(subscription) {
            this.editing = true;
            this.form = {
                id: subscription.id,
                customer_id: subscription.customer_id,
                plan_id: subscription.plan_id,
                price: subscription.price,
                status: subscription.status,
                started_at: subscription.started_at?.slice(0, 16),
                expires_at: subscription.expires_at?.slice(0, 16),
                notes: subscription.notes
            };
            this.showCreateModal = true;
        },
        
        saveSubscription() {
            const url = this.editing 
                ? `{{ route('admin.subscriptions.update', '') }}/${this.form.id}`
                : '{{ route('admin.subscriptions.store') }}';
            
            const method = this.editing ? 'PUT' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showCreateModal = false;
                    location.reload();
                }
            })
            .catch(error => console.error('Error saving subscription:', error));
        },
        
        renewSubscription(subscription) {
            if (confirm(`Renew subscription for ${subscription.customer?.name}?`)) {
                fetch(`{{ route('admin.subscriptions.renew', '') }}/${subscription.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error renewing subscription:', error));
            }
        },
        
        cancelSubscription(subscription) {
            if (confirm(`Cancel subscription for ${subscription.customer?.name}?`)) {
                fetch(`{{ route('admin.subscriptions.cancel', '') }}/${subscription.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error cancelling subscription:', error));
            }
        },
        
        deleteSubscription(subscription) {
            if (confirm(`Delete subscription for ${subscription.customer?.name}?`)) {
                fetch(`{{ route('admin.subscriptions.destroy', '') }}/${subscription.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.subscriptions = this.subscriptions.filter(s => s.id !== subscription.id);
                    }
                })
                .catch(error => console.error('Error deleting subscription:', error));
            }
        },
        
        formatCurrency(value) {
            return '$' + parseFloat(value).toFixed(2);
        },
        
        formatNumber(value) {
            return value.toLocaleString();
        },
        
        formatDate(timestamp) {
            if (!timestamp) return 'N/A';
            const date = new Date(timestamp);
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            });
        },
        
        formatDateTime(timestamp) {
            if (!timestamp) return 'N/A';
            const date = new Date(timestamp);
            return date.toLocaleString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
}
</script>
@endpush