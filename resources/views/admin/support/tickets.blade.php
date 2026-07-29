<!-- admin/support/tickets.blade.php -->
@extends('layout.app')

@section('title', 'Support Tickets')
@section('page-title', 'Support Tickets')
@section('page-subtitle', 'Manage customer support requests and inquiries')

@section('content')
<div x-data="ticketManager()" x-init="init()">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Tickets</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.total">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">+12% this week</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Open</p>
                    <p class="text-2xl font-bold text-yellow-600" x-text="stats.open">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Requires attention</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">In Progress</p>
                    <p class="text-2xl font-bold text-blue-600" x-text="stats.in_progress">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                    <i class="fas fa-spinner text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Being handled</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Resolved</p>
                    <p class="text-2xl font-bold text-green-600" x-text="stats.resolved">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <p class="text-xs text-green-600 dark:text-green-400 mt-2">
                <i class="fas fa-arrow-up mr-1"></i> 8% increase
            </p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Avg Response</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.avg_response + 'h'">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <p class="text-xs text-green-600 dark:text-green-400 mt-2">
                <i class="fas fa-arrow-down mr-1"></i> 2h faster
            </p>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</label>
                <select x-model="filters.status" 
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Priority:</label>
                <select x-model="filters.priority" 
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All</option>
                    <option value="urgent">Urgent</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>

            <div class="flex items-center gap-2 flex-1 min-w-[200px]">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="filters.search" 
                           placeholder="Search tickets..." 
                           class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex items-center gap-2 ml-auto">
                <button @click="applyFilters()" 
                        class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                    <i class="fas fa-filter mr-1.5"></i>Apply
                </button>
                <button @click="resetFilters()" 
                        class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-undo mr-1.5"></i>Reset
                </button>
                <button @click="openCreateModal()" 
                        class="px-4 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                    <i class="fas fa-plus mr-1.5"></i>New Ticket
                </button>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">
                            <input type="checkbox" @click="toggleAll" class="rounded border-gray-300 dark:border-gray-700">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ticket</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Priority</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="ticket in filteredTickets" :key="ticket.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors cursor-pointer" @click="viewTicket(ticket.id)">
                            <td class="px-4 py-3" @click.stop>
                                <input type="checkbox" x-model="selectedTickets" :value="ticket.id" 
                                       class="rounded border-gray-300 dark:border-gray-700">
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="text-gray-900 dark:text-white font-medium" x-text="'#' + ticket.id + ' - ' + ticket.subject"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="ticket.description?.substring(0, 60) + '...'"></p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <img :src="ticket.user?.avatar || 'https://ui-avatars.com/api/?name=' + (ticket.user?.name || 'User')" 
                                         alt="" class="w-6 h-6 rounded-full">
                                    <span class="text-gray-900 dark:text-white" x-text="ticket.user?.name || 'Guest'"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="{
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': ticket.priority === 'urgent',
                                        'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400': ticket.priority === 'high',
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': ticket.priority === 'medium',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': ticket.priority === 'low'
                                      }">
                                    <i :class="{
                                        'fas fa-exclamation-circle': ticket.priority === 'urgent',
                                        'fas fa-exclamation-triangle': ticket.priority === 'high',
                                        'fas fa-minus-circle': ticket.priority === 'medium',
                                        'fas fa-info-circle': ticket.priority === 'low'
                                      }"></i>
                                    <span x-text="ticket.priority"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="{
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': ticket.status === 'open',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': ticket.status === 'in_progress',
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': ticket.status === 'resolved',
                                        'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400': ticket.status === 'closed'
                                      }">
                                    <i :class="{
                                        'fas fa-clock': ticket.status === 'open',
                                        'fas fa-spinner': ticket.status === 'in_progress',
                                        'fas fa-check-circle': ticket.status === 'resolved',
                                        'fas fa-times-circle': ticket.status === 'closed'
                                      }"></i>
                                    <span x-text="ticket.status.replace('_', ' ')"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-600 dark:text-gray-400 text-xs" 
                                      x-text="ticket.category || 'General'"></span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs" 
                                x-text="formatDate(ticket.created_at)"></td>
                            <td class="px-4 py-3 text-right" @click.stop>
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="viewTicket(ticket.id)" 
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button @click="editTicket(ticket)" 
                                            class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button @click="deleteTicket(ticket)" 
                                            class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredTickets.length === 0">
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-ticket-alt text-3xl text-gray-300 dark:text-gray-700 mb-2"></i>
                                <p>No tickets found</p>
                                <p class="text-xs mt-1">Try adjusting your filters or create a new ticket</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Showing <span x-text="filteredTickets.length"></span> of <span x-text="tickets.length"></span> tickets
            </div>
            @include('admin.partials.pagination', ['paginator' => $tickets])
        </div>
    </div>

    <!-- Ticket Detail Modal -->
    <div x-show="showDetailModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.away="showDetailModal = false">
        
        <div x-show="showDetailModal"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="'#' + selectedTicket?.id + ' - ' + selectedTicket?.subject"></h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="selectedTicket?.category || 'General Support'"></p>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="showDetailModal = false" 
                            class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                        <i class="fas fa-times text-gray-400"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <template x-if="selectedTicket">
                    <div class="space-y-6">
                        <!-- Ticket Info -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</label>
                                <div class="flex items-center gap-2 mt-1">
                                    <img :src="selectedTicket.user?.avatar || 'https://ui-avatars.com/api/?name=' + (selectedTicket.user?.name || 'User')" 
                                         alt="" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white" x-text="selectedTicket.user?.name || 'Guest'"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="selectedTicket.user?.email || ''"></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</label>
                                <div class="mt-1">
                                    <select x-model="selectedTicket.status" 
                                            class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                        <option value="open">Open</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="resolved">Resolved</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Priority & Dates -->
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Priority</label>
                                <div class="mt-1">
                                    <select x-model="selectedTicket.priority" 
                                            class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white" x-text="formatDateTime(selectedTicket.created_at)"></p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Updated</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white" x-text="formatDateTime(selectedTicket.updated_at)"></p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</label>
                            <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <p class="text-sm text-gray-700 dark:text-gray-300" x-text="selectedTicket.description"></p>
                            </div>
                        </div>

                        <!-- Attachments -->
                        <div x-show="selectedTicket.attachments?.length">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Attachments</label>
                            <div class="mt-1 flex flex-wrap gap-2">
                                <template x-for="attachment in selectedTicket.attachments" :key="attachment.id">
                                    <a :href="attachment.url" target="_blank" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 dark:bg-gray-800 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                        <i class="fas fa-file"></i>
                                        <span x-text="attachment.name"></span>
                                        <span class="text-xs text-gray-400" x-text="attachment.size"></span>
                                    </a>
                                </template>
                            </div>
                        </div>

                        <!-- Conversations -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Conversations</label>
                                <button @click="openReplyModal()" 
                                        class="px-3 py-1 text-sm bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                                    <i class="fas fa-reply mr-1"></i>Reply
                                </button>
                            </div>
                            <div class="space-y-3 max-h-64 overflow-y-auto">
                                <template x-for="message in selectedTicket.messages" :key="message.id">
                                    <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <img :src="message.user?.avatar || 'https://ui-avatars.com/api/?name=' + (message.user?.name || 'User')" 
                                             alt="" class="w-8 h-8 rounded-full">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-gray-900 dark:text-white" x-text="message.user?.name || 'System'"></span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400" x-text="formatDate(message.created_at)"></span>
                                            </div>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1" x-text="message.content"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Create/Edit Ticket Modal -->
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
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="editingTicket ? 'Edit Ticket' : 'Create New Ticket'"></h3>
                <button @click="showCreateModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <form @submit.prevent="saveTicket()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject *</label>
                        <input type="text" x-model="form.subject" required
                               class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                        <select x-model="form.category" required
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select category...</option>
                            <option value="billing">Billing</option>
                            <option value="technical">Technical Support</option>
                            <option value="account">Account Management</option>
                            <option value="feature">Feature Request</option>
                            <option value="bug">Bug Report</option>
                            <option value="general">General</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                        <select x-model="form.priority"
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select x-model="form.status"
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assigned To</label>
                        <select x-model="form.assigned_to"
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Unassigned</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description *</label>
                        <textarea x-model="form.description" rows="4" required
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                        <button type="button" @click="showCreateModal = false" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-all">
                            <i class="fas fa-save mr-1.5"></i>
                            <span x-text="editingTicket ? 'Update' : 'Create'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reply Modal -->
    <div x-show="showReplyModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.away="showReplyModal = false">
        
        <div x-show="showReplyModal"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reply to Ticket</h3>
                <button @click="showReplyModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6">
                <form @submit.prevent="sendReply()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message *</label>
                        <textarea x-model="replyMessage" rows="5" required
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Type your reply here..."></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                        <button type="button" @click="showReplyModal = false" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-all">
                            <i class="fas fa-paper-plane mr-1.5"></i>
                            Send Reply
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
function ticketManager() {
    return {
        tickets: @json($tickets->items()),
        stats: @json($stats),
        agents: @json($agents),
        filters: {
            status: 'all',
            priority: 'all',
            search: ''
        },
        selectedTickets: [],
        selectedTicket: null,
        showDetailModal: false,
        showCreateModal: false,
        showReplyModal: false,
        editingTicket: false,
        replyMessage: '',
        form: {
            subject: '',
            category: '',
            priority: 'medium',
            status: 'open',
            assigned_to: '',
            description: ''
        },
        
        get filteredTickets() {
            let tickets = this.tickets;
            
            if (this.filters.status !== 'all') {
                tickets = tickets.filter(t => t.status === this.filters.status);
            }
            
            if (this.filters.priority !== 'all') {
                tickets = tickets.filter(t => t.priority === this.filters.priority);
            }
            
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                tickets = tickets.filter(t => 
                    t.subject.toLowerCase().includes(search) ||
                    t.id.toString().includes(search) ||
                    t.description?.toLowerCase().includes(search)
                );
            }
            
            return tickets;
        },
        
        init() {
            this.fetchStats();
            setInterval(() => this.fetchStats(), 30000);
        },
        
        fetchStats() {
            fetch('{{ route("admin.support.tickets.stats") }}')
                .then(response => response.json())
                .then(data => {
                    this.stats = data;
                })
                .catch(error => console.error('Error fetching stats:', error));
        },
        
        applyFilters() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.support.tickets') }}?${params.toString()}`;
        },
        
        resetFilters() {
            this.filters = {
                status: 'all',
                priority: 'all',
                search: ''
            };
            this.applyFilters();
        },
        
        toggleAll(e) {
            const checked = e.target.checked;
            this.selectedTickets = checked ? this.filteredTickets.map(t => t.id) : [];
        },
        
        viewTicket(id) {
            fetch(`{{ route('admin.support.tickets.show', '') }}/${id}`)
                .then(response => response.json())
                .then(data => {
                    this.selectedTicket = data;
                    this.showDetailModal = true;
                })
                .catch(error => console.error('Error fetching ticket:', error));
        },
        
        openCreateModal() {
            this.editingTicket = false;
            this.form = {
                subject: '',
                category: '',
                priority: 'medium',
                status: 'open',
                assigned_to: '',
                description: ''
            };
            this.showCreateModal = true;
        },
        
        editTicket(ticket) {
            this.editingTicket = true;
            this.form = {
                id: ticket.id,
                subject: ticket.subject,
                category: ticket.category,
                priority: ticket.priority,
                status: ticket.status,
                assigned_to: ticket.assigned_to,
                description: ticket.description
            };
            this.showCreateModal = true;
        },
        
        saveTicket() {
            const url = this.editingTicket 
                ? `{{ route('admin.support.tickets.update', '') }}/${this.form.id}`
                : '{{ route('admin.support.tickets.store') }}';
            
            const method = this.editingTicket ? 'PUT' : 'POST';
            
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
            .catch(error => console.error('Error saving ticket:', error));
        },
        
        openReplyModal() {
            this.replyMessage = '';
            this.showReplyModal = true;
        },
        
        sendReply() {
            if (!this.selectedTicket) return;
            
            fetch(`{{ route('admin.support.tickets.reply', '') }}/${this.selectedTicket.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: this.replyMessage })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showReplyModal = false;
                    this.viewTicket(this.selectedTicket.id);
                }
            })
            .catch(error => console.error('Error sending reply:', error));
        },
        
        deleteTicket(ticket) {
            if (confirm(`Are you sure you want to delete ticket #${ticket.id}?`)) {
                fetch(`{{ route('admin.support.tickets.destroy', '') }}/${ticket.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.tickets = this.tickets.filter(t => t.id !== ticket.id);
                    }
                })
                .catch(error => console.error('Error deleting ticket:', error));
            }
        },
        
        formatDate(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString('en-US', {
                month: 'short',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        },
        
        formatDateTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
    }
}
</script>
@endpush