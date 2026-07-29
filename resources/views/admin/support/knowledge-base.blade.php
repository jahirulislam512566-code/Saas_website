<!-- admin/support/knowledge-base.blade.php -->
@extends('layout.app')

@section('title', 'Knowledge Base')
@section('page-title', 'Knowledge Base')
@section('page-subtitle', 'Manage help articles, documentation, and FAQs')

@section('content')
<div x-data="knowledgeBase()" x-init="init()">
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Articles</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.total">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                    <i class="fas fa-book text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Published</p>
                    <p class="text-2xl font-bold text-green-600" x-text="stats.published">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Drafts</p>
                    <p class="text-2xl font-bold text-yellow-600" x-text="stats.drafts">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i class="fas fa-pen-fancy text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Views</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.views">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-eye text-purple-600 dark:text-purple-400"></i>
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
                           placeholder="Search articles..." 
                           class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <select x-model="filters.category" 
                    class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                <option value="all">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <select x-model="filters.status" 
                    class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                <option value="all">All Status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="archived">Archived</option>
            </select>

            <button @click="openCreateModal()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all ml-auto">
                <i class="fas fa-plus mr-1.5"></i>New Article
            </button>
        </div>
    </div>

    <!-- Articles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <template x-for="article in filteredArticles" :key="article.id">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition-all">
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 px-2 py-0.5 rounded-full">
                                <span x-text="article.category?.name || 'General'"></span>
                            </span>
                            <h3 class="mt-2 font-semibold text-gray-900 dark:text-white" x-text="article.title"></h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2" x-text="article.excerpt || article.content?.substring(0, 120) + '...'"></p>
                        </div>
                        <div class="flex items-center gap-1 ml-2">
                            <button @click="editArticle(article)" 
                                    class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button @click="deleteArticle(article)" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-3 flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-eye"></i>
                            <span x-text="article.views || 0"></span>
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-thumbs-up"></i>
                            <span x-text="article.helpful || 0"></span>
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-clock"></i>
                            <span x-text="formatDate(article.updated_at)"></span>
                        </span>
                    </div>
                    
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <span class="text-xs px-2 py-0.5 rounded-full"
                              :class="{
                                'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': article.status === 'published',
                                'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': article.status === 'draft',
                                'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400': article.status === 'archived'
                              }"
                              x-text="article.status"></span>
                        <a :href="article.url" target="_blank" 
                           class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">
                            View <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="filteredArticles.length === 0" class="text-center py-12">
        <div class="flex flex-col items-center">
            <i class="fas fa-book-open text-4xl text-gray-300 dark:text-gray-700 mb-3"></i>
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">No articles found</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create your first knowledge base article</p>
            <button @click="openCreateModal()" 
                    class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-plus mr-1.5"></i>Create Article
            </button>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        @include('admin.partials.pagination', ['paginator' => $articles])
    </div>

    <!-- Create/Edit Article Modal -->
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
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="editing ? 'Edit Article' : 'New Article'"></h3>
                <button @click="showModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <form @submit.prevent="saveArticle()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                        <input type="text" x-model="form.title" required
                               class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                        <select x-model="form.category_id" required
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select x-model="form.status"
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content *</label>
                        <textarea x-model="form.content" rows="8" required
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tags (comma separated)</label>
                        <input type="text" x-model="form.tags" 
                               placeholder="e.g. billing, setup, troubleshooting"
                               class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
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
function knowledgeBase() {
    return {
        articles: @json($articles->items()),
        stats: @json($stats),
        categories: @json($categories),
        searchQuery: '',
        filters: {
            category: 'all',
            status: 'all'
        },
        showModal: false,
        editing: false,
        form: {
            id: null,
            title: '',
            category_id: '',
            status: 'draft',
            content: '',
            tags: ''
        },
        
        get filteredArticles() {
            let articles = this.articles;
            
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                articles = articles.filter(a => 
                    a.title.toLowerCase().includes(query) ||
                    a.content?.toLowerCase().includes(query)
                );
            }
            
            if (this.filters.category !== 'all') {
                articles = articles.filter(a => a.category_id == this.filters.category);
            }
            
            if (this.filters.status !== 'all') {
                articles = articles.filter(a => a.status === this.filters.status);
            }
            
            return articles;
        },
        
        init() {
            // Initialize any additional functionality
        },
        
        openCreateModal() {
            this.editing = false;
            this.form = {
                id: null,
                title: '',
                category_id: '',
                status: 'draft',
                content: '',
                tags: ''
            };
            this.showModal = true;
        },
        
        editArticle(article) {
            this.editing = true;
            this.form = {
                id: article.id,
                title: article.title,
                category_id: article.category_id,
                status: article.status,
                content: article.content,
                tags: article.tags?.join(', ') || ''
            };
            this.showModal = true;
        },
        
        saveArticle() {
            const url = this.editing 
                ? `{{ route('admin.support.knowledge-base.update', '') }}/${this.form.id}`
                : '{{ route('admin.support.knowledge-base.store') }}';
            
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
            .catch(error => console.error('Error saving article:', error));
        },
        
        deleteArticle(article) {
            if (confirm(`Are you sure you want to delete "${article.title}"?`)) {
                fetch(`{{ route('admin.support.knowledge-base.destroy', '') }}/${article.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.articles = this.articles.filter(a => a.id !== article.id);
                        this.stats.total--;
                        if (article.status === 'published') this.stats.published--;
                        else if (article.status === 'draft') this.stats.drafts--;
                    }
                })
                .catch(error => console.error('Error deleting article:', error));
            }
        },
        
        formatDate(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            });
        }
    }
}
</script>
@endpush