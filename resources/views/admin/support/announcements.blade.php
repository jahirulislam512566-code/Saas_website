<!-- admin/support/announcements.blade.php -->
@extends('layout.app')

@section('title', 'Announcements')
@section('page-title', 'Announcements')
@section('page-subtitle', 'Create and manage system announcements for users')

@section('content')
<div x-data="announcementManager()" x-init="init()">
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Announcements</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.total">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                    <i class="fas fa-bullhorn text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Active</p>
                    <p class="text-2xl font-bold text-green-600" x-text="stats.active">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Scheduled</p>
                    <p class="text-2xl font-bold text-yellow-600" x-text="stats.scheduled">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Read Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.read_rate + '%'">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2 flex-1">
                <div class="relative flex-1 min-w-[200px]">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="searchQuery" 
                           placeholder="Search announcements..." 
                           class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <select x-model="filters.status" 
                    class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="scheduled">Scheduled</option>
                <option value="expired">Expired</option>
            </select>

            <select x-model="filters.type" 
                    class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                <option value="all">All Types</option>
                <option value="info">Information</option>
                <option value="warning">Warning</option>
                <option value="success">Success</option>
                <option value="danger">Critical</option>
            </select>

            <button @click="openCreateModal()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all ml-auto">
                <i class="fas fa-plus mr-1.5"></i>New Announcement
            </button>
        </div>
    </div>

    <!-- Announcements List -->
    <div class="space-y-4">
        <template x-for="announcement in filteredAnnouncements" :key="announcement.id">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition-all"
                 :class="{
                   'border-l-4 border-l-indigo-500': announcement.type === 'info',
                   'border-l-4 border-l-yellow-500': announcement.type === 'warning',
                   'border-l-4 border-l-green-500': announcement.type === 'success',
                   'border-l-4 border-l-red-500': announcement.type === 'danger'
                 }">
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                                      :class="{
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': announcement.type === 'info',
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': announcement.type === 'warning',
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': announcement.type === 'success',
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': announcement.type === 'danger'
                                      }"
                                      x-text="announcement.type"></span>
                                <span class="text-xs px-2 py-0.5 rounded-full"
                                      :class="{
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': announcement.status === 'active',
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': announcement.status === 'scheduled',
                                        'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400': announcement.status === 'expired'
                                      }"
                                      x-text="announcement.status"></span>
                            </div>
                            <h3 class="mt-2 font-semibold text-gray-900 dark:text-white" x-text="announcement.title"></h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="announcement.content"></p>
                            <div class="mt-2 flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span x-text="formatDate(announcement.published_at)"></span>
                                </span>
                                <span x-show="announcement.expires_at" class="flex items-center gap-1">
                                    <i class="fas fa-clock"></i>
                                    Expires: <span x-text="formatDate(announcement.expires_at)"></span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-eye"></i>
                                    <span x-text="announcement.views || 0"></span>
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 ml-2">
                            <button @click="editAnnouncement(announcement)" 
                                    class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button @click="deleteAnnouncement(announcement)" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="filteredAnnouncements.length === 0" class="text-center py-12">
        <div class="flex flex-col items-center">
            <i class="fas fa-bullhorn text-4xl text-gray-300 dark:text-gray-700 mb-3"></i>
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">No announcements</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create your first announcement to keep users informed</p>
            <button @click="openCreateModal()" 
                    class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-plus mr-1.5"></i>Create Announcement
            </button>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.away="showModal = false">
        
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="editing ? 'Edit Announcement' : 'New Announcement'"></h3>
                <button @click="showModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <form @submit.prevent="saveAnnouncement()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                        <input type="text" x-model="form.title" required
                               class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content *</label>
                        <textarea x-model="form.content" rows="4" required
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                            <select x-model="form.type"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="info">Information</option>
                                <option value="warning">Warning</option>
                                <option value="success">Success</option>
                                <option value="danger">Critical</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select x-model="form.status"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="scheduled">Scheduled</option>
                                <option value="active">Active</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Publish Date</label>
                            <input type="datetime-local" x-model="form.published_at"
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expiry Date (Optional)</label>
                            <input type="datetime-local" x-model="form.expires_at"
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Audience</label>
                        <select x-model="form.audience"
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="all">All Users</option>
                            <option value="subscribers">Subscribers Only</option>
                            <option value="admins">Admins Only</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                        <button type="button" @click="showModal = false" 
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
function announcementManager() {
    return {
        announcements: @json($announcements->items()),
        stats: @json($stats),
        searchQuery: '',
        filters: {
            status: 'all',
            type: 'all'
        },
        showModal: false,
        editing: false,
        form: {
            id: null,
            title: '',
            content: '',
            type: 'info',
            status: 'scheduled',
            published_at: '',
            expires_at: '',
            audience: 'all'
        },
        
        get filteredAnnouncements() {
            let announcements = this.announcements;
            
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                announcements = announcements.filter(a => 
                    a.title.toLowerCase().includes(query) ||
                    a.content.toLowerCase().includes(query)
                );
            }
            
            if (this.filters.status !== 'all') {
                announcements = announcements.filter(a => a.status === this.filters.status);
            }
            
            if (this.filters.type !== 'all') {
                announcements = announcements.filter(a => a.type === this.filters.type);
            }
            
            return announcements;
        },
        
        init() {
            // Initialize any additional functionality
        },
        
        openCreateModal() {
            this.editing = false;
            this.form = {
                id: null,
                title: '',
                content: '',
                type: 'info',
                status: 'scheduled',
                published_at: new Date().toISOString().slice(0, 16),
                expires_at: '',
                audience: 'all'
            };
            this.showModal = true;
        },
        
        editAnnouncement(announcement) {
            this.editing = true;
            this.form = {
                id: announcement.id,
                title: announcement.title,
                content: announcement.content,
                type: announcement.type,
                status: announcement.status,
                published_at: announcement.published_at?.slice(0, 16),
                expires_at: announcement.expires_at?.slice(0, 16),
                audience: announcement.audience || 'all'
            };
            this.showModal = true;
        },
        
        saveAnnouncement() {
            const url = this.editing 
                ? `{{ route('admin.support.announcements.update', '') }}/${this.form.id}`
                : '{{ route('admin.support.announcements.store') }}';
            
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
                    this.showModal = false;
                    location.reload();
                }
            })
            .catch(error => console.error('Error saving announcement:', error));
        },
        
        deleteAnnouncement(announcement) {
            if (confirm(`Are you sure you want to delete "${announcement.title}"?`)) {
                fetch(`{{ route('admin.support.announcements.destroy', '') }}/${announcement.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.announcements = this.announcements.filter(a => a.id !== announcement.id);
                        this.stats.total--;
                        if (announcement.status === 'active') this.stats.active--;
                        else if (announcement.status === 'scheduled') this.stats.scheduled--;
                    }
                })
                .catch(error => console.error('Error deleting announcement:', error));
            }
        },
        
        formatDate(timestamp) {
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