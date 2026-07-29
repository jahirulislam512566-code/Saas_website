<!-- admin/ai/prompts.blade.php -->
@extends('layout.app')

@section('title', 'Prompt Library')
@section('page-title', 'Prompt Library')
@section('page-subtitle', 'Manage and organize AI prompts for various use cases')

@section('content')
<div x-data="promptLibrary()" x-init="init()">
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Prompts</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.total">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Categories</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.categories">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Most Used</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.most_used">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Success Rate</p>
            <p class="text-2xl font-bold text-green-600" x-text="stats.success_rate + '%'">0%</p>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Category:</label>
                <select x-model="filters.category" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tool:</label>
                <select x-model="filters.tool" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Tools</option>
                    <option value="writer">AI Writer</option>
                    <option value="seo">SEO Generator</option>
                    <option value="image">Image Generator</option>
                    <option value="website">Website Generator</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="filters.search" 
                           placeholder="Search prompts..." 
                           class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <button @click="openCreateModal()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all ml-auto">
                <i class="fas fa-plus mr-1.5"></i>New Prompt
            </button>
        </div>
    </div>

    <!-- Prompts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <template x-for="prompt in filteredPrompts" :key="prompt.id">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition-all group">
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded-full"
                                      :class="{
                                        'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400': prompt.tool === 'writer',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': prompt.tool === 'seo',
                                        'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400': prompt.tool === 'image',
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': prompt.tool === 'website'
                                      }"
                                      x-text="prompt.tool"></span>
                                <span class="text-xs text-gray-500 dark:text-gray-400" x-text="prompt.category"></span>
                            </div>
                            <h4 class="mt-2 font-semibold text-gray-900 dark:text-white" x-text="prompt.title"></h4>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2" x-text="prompt.description"></p>
                            <div class="mt-2 flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-clock"></i>
                                    <span x-text="formatDate(prompt.updated_at)"></span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-play"></i>
                                    <span x-text="prompt.usage_count + ' uses'"></span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-star" :class="prompt.is_favorite ? 'text-yellow-500' : 'text-gray-300'"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 ml-2">
                            <button @click="usePrompt(prompt)" 
                                    class="p-1.5 text-gray-400 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-play text-xs"></i>
                            </button>
                            <button @click="editPrompt(prompt)" 
                                    class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button @click="duplicatePrompt(prompt)" 
                                    class="p-1.5 text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-copy text-xs"></i>
                            </button>
                            <button @click="deletePrompt(prompt)" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Prompt Preview -->
                    <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <pre class="text-xs text-gray-600 dark:text-gray-400 whitespace-pre-wrap font-mono" x-text="prompt.prompt_text.substring(0, 150) + '...'"></pre>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="filteredPrompts.length === 0" class="text-center py-12">
        <div class="flex flex-col items-center">
            <i class="fas fa-scroll text-4xl text-gray-300 dark:text-gray-700 mb-3"></i>
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">No prompts found</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create your first prompt to get started</p>
            <button @click="openCreateModal()" 
                    class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-plus mr-1.5"></i>Create Prompt
            </button>
        </div>
    </div>

    <!-- Create/Edit Prompt Modal -->
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
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="editing ? 'Edit Prompt' : 'New Prompt'"></h3>
                <button @click="showModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <form @submit.prevent="savePrompt()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                        <input type="text" x-model="form.title" required
                               class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea x-model="form.description" rows="2"
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
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
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tool</label>
                            <select x-model="form.tool"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="writer">AI Writer</option>
                                <option value="seo">SEO Generator</option>
                                <option value="image">Image Generator</option>
                                <option value="website">Website Generator</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prompt Text *</label>
                        <textarea x-model="form.prompt_text" rows="6" required
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 font-mono text-sm"></textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Use <span class="text-indigo-600 dark:text-indigo-400">{{variable}}</span> for dynamic content
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Variables (comma separated)</label>
                        <input type="text" x-model="form.variables" 
                               placeholder="e.g. topic, tone, length"
                               class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" x-model="form.is_public">
                            Make public
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" x-model="form.is_favorite">
                            Add to favorites
                        </label>
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

    <!-- Use Prompt Modal -->
    <div x-show="showUseModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.away="showUseModal = false">
        
        <div x-show="showUseModal"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Use Prompt</h3>
                <button @click="showUseModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <form @submit.prevent="executePrompt()" class="space-y-4">
                    <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prompt Preview</label>
                        <pre class="text-xs text-gray-600 dark:text-gray-400 whitespace-pre-wrap font-mono" x-text="selectedPrompt?.prompt_text"></pre>
                    </div>

                    <template x-for="variable in selectedPrompt?.variables?.split(',') || []" :key="variable">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 capitalize" x-text="variable.trim()"></label>
                            <input type="text" x-model="promptValues[variable.trim()]" 
                                   :placeholder="'Enter ' + variable.trim()"
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </template>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Additional Instructions</label>
                        <textarea x-model="additionalInstructions" rows="2"
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Optional additional context..."></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                        <button type="button" @click="showUseModal = false" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-all">
                            <i class="fas fa-rocket mr-1.5"></i>
                            Generate
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
function promptLibrary() {
    return {
        prompts: @json($prompts),
        stats: @json($stats),
        categories: @json($categories),
        filters: {
            category: 'all',
            tool: 'all',
            search: ''
        },
        showModal: false,
        showUseModal: false,
        editing: false,
        selectedPrompt: null,
        promptValues: {},
        additionalInstructions: '',
        form: {
            id: null,
            title: '',
            description: '',
            category_id: '',
            tool: 'writer',
            prompt_text: '',
            variables: '',
            is_public: false,
            is_favorite: false
        },
        
        get filteredPrompts() {
            let prompts = this.prompts;
            
            if (this.filters.category !== 'all') {
                prompts = prompts.filter(p => p.category_id == this.filters.category);
            }
            
            if (this.filters.tool !== 'all') {
                prompts = prompts.filter(p => p.tool === this.filters.tool);
            }
            
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                prompts = prompts.filter(p => 
                    p.title.toLowerCase().includes(search) ||
                    p.description?.toLowerCase().includes(search) ||
                    p.prompt_text.toLowerCase().includes(search)
                );
            }
            
            return prompts;
        },
        
        applyFilters() {
            // Filter logic handled by computed property
        },
        
        openCreateModal() {
            this.editing = false;
            this.form = {
                id: null,
                title: '',
                description: '',
                category_id: '',
                tool: 'writer',
                prompt_text: '',
                variables: '',
                is_public: false,
                is_favorite: false
            };
            this.showModal = true;
        },
        
        editPrompt(prompt) {
            this.editing = true;
            this.form = {
                id: prompt.id,
                title: prompt.title,
                description: prompt.description,
                category_id: prompt.category_id,
                tool: prompt.tool,
                prompt_text: prompt.prompt_text,
                variables: prompt.variables?.join(', ') || '',
                is_public: prompt.is_public,
                is_favorite: prompt.is_favorite
            };
            this.showModal = true;
        },
        
        savePrompt() {
            const url = this.editing 
                ? `{{ route('admin.ai.prompts.update', '') }}/${this.form.id}`
                : '{{ route('admin.ai.prompts.store') }}';
            
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
            .catch(error => console.error('Error saving prompt:', error));
        },
        
        usePrompt(prompt) {
            this.selectedPrompt = prompt;
            this.promptValues = {};
            this.additionalInstructions = '';
            
            // Parse variables
            if (prompt.variables) {
                const vars = typeof prompt.variables === 'string' 
                    ? prompt.variables.split(',').map(v => v.trim())
                    : prompt.variables;
                vars.forEach(v => {
                    this.promptValues[v] = '';
                });
            }
            
            this.showUseModal = true;
        },
        
        executePrompt() {
            // Prepare the prompt with variable substitution
            let promptText = this.selectedPrompt.prompt_text;
            
            Object.entries(this.promptValues).forEach(([key, value]) => {
                promptText = promptText.replace(new RegExp(`{{${key}}}`, 'g'), value);
            });
            
            if (this.additionalInstructions) {
                promptText += '\n\nAdditional instructions: ' + this.additionalInstructions;
            }
            
            // Redirect to the appropriate tool with the prompt
            const tool = this.selectedPrompt.tool;
            const routes = {
                writer: '{{ route("admin.ai.writer") }}',
                seo: '{{ route("admin.ai.seo") }}',
                image: '{{ route("admin.ai.image") }}',
                website: '{{ route("admin.ai.generator") }}'
            };
            
            window.location.href = routes[tool] + '?prompt=' + encodeURIComponent(promptText);
        },
        
        duplicatePrompt(prompt) {
            fetch(`{{ route('admin.ai.prompts.duplicate', '') }}/${prompt.id}`, {
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
            .catch(error => console.error('Error duplicating prompt:', error));
        },
        
        deletePrompt(prompt) {
            if (confirm(`Are you sure you want to delete "${prompt.title}"?`)) {
                fetch(`{{ route('admin.ai.prompts.destroy', '') }}/${prompt.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.prompts = this.prompts.filter(p => p.id !== prompt.id);
                        this.stats.total--;
                    }
                })
                .catch(error => console.error('Error deleting prompt:', error));
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