<!-- admin/website-builder/blocks.blade.php -->
@extends('layout.app')

@section('title', 'Builder Blocks')
@section('page-title', 'Builder Blocks')
@section('page-subtitle', 'Manage all available blocks for the website builder')

@section('content')
<div x-data="blockManager()" x-init="init()">
    <!-- Block Categories -->
    <div class="flex flex-wrap items-center gap-2 mb-6">
        <button @click="category = 'all'" 
                class="px-3 py-1.5 text-sm font-medium rounded-lg transition-all"
                :class="category === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
            All Blocks
        </button>
        @foreach($categories as $cat)
            <button @click="category = '{{ $cat->slug }}'" 
                    class="px-3 py-1.5 text-sm font-medium rounded-lg transition-all"
                    :class="category === '{{ $cat->slug }}' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
                {{ $cat->name }}
            </button>
        @endforeach
        <button @click="openCreateModal()" 
                class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all ml-auto">
            <i class="fas fa-plus mr-1.5"></i>New Block
        </button>
    </div>

    <!-- Blocks Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($blocks as $block)
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition-all group">
                <!-- Block Preview -->
                <div class="h-32 bg-gray-100 dark:bg-gray-800 rounded-t-xl flex items-center justify-center overflow-hidden">
                    @if($block->preview)
                        <img src="{{ $block->preview }}" alt="{{ $block->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-cube text-3xl text-gray-400 dark:text-gray-600"></i>
                    @endif
                </div>
                
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $block->name }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $block->category->name ?? 'Uncategorized' }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button @click="editBlock({{ $block }})" 
                                    class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button @click="deleteBlock({{ $block->id }})" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-2 flex flex-wrap gap-1">
                        <span class="text-xs px-2 py-0.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-full">
                            <i class="fas fa-{{ $block->type === 'section' ? 'columns' : 'cube' }} mr-1"></i>
                            {{ ucfirst($block->type) }}
                        </span>
                        @if($block->is_global)
                            <span class="text-xs px-2 py-0.5 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 rounded-full">
                                <i class="fas fa-globe mr-1"></i>Global
                            </span>
                        @endif
                        @if($block->is_active)
                            <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i>Active
                            </span>
                        @else
                            <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400 rounded-full">
                                <i class="fas fa-times-circle mr-1"></i>Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($blocks->isEmpty())
        <div class="text-center py-12">
            <div class="flex flex-col items-center">
                <i class="fas fa-cubes text-4xl text-gray-300 dark:text-gray-700 mb-3"></i>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">No blocks found</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create your first block for the website builder</p>
                <button @click="openCreateModal()" 
                        class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                    <i class="fas fa-plus mr-1.5"></i>Create Block
                </button>
            </div>
        </div>
    @endif

    <!-- Create/Edit Block Modal -->
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
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="editing ? 'Edit Block' : 'New Block'"></h3>
                <button @click="showModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <form @submit.prevent="saveBlock()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Block Name *</label>
                        <input type="text" x-model="form.name" required
                               class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                        <select x-model="form.category_id"
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select category...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                        <select x-model="form.type"
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="section">Section</option>
                            <option value="component">Component</option>
                            <option value="layout">Layout</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preview Image URL</label>
                        <input type="url" x-model="form.preview"
                               placeholder="https://example.com/preview.jpg"
                               class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">HTML Content *</label>
                        <textarea x-model="form.content" rows="6" required
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 font-mono text-sm"></textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">HTML structure with Tailwind CSS classes</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CSS (Optional)</label>
                        <textarea x-model="form.css" rows="3"
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 font-mono text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">JavaScript (Optional)</label>
                        <textarea x-model="form.js" rows="3"
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 font-mono text-sm"></textarea>
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" x-model="form.is_global">
                            Global Block (available on all pages)
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" x-model="form.is_active">
                            Active
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
</div>

@push('scripts')
<script>
function blockManager() {
    return {
        category: 'all',
        showModal: false,
        editing: false,
        form: {
            id: null,
            name: '',
            category_id: '',
            type: 'section',
            preview: '',
            content: '',
            css: '',
            js: '',
            is_global: false,
            is_active: true
        },
        
        openCreateModal() {
            this.editing = false;
            this.form = {
                id: null,
                name: '',
                category_id: '',
                type: 'section',
                preview: '',
                content: '',
                css: '',
                js: '',
                is_global: false,
                is_active: true
            };
            this.showModal = true;
        },
        
        editBlock(block) {
            this.editing = true;
            this.form = {
                id: block.id,
                name: block.name,
                category_id: block.category_id,
                type: block.type,
                preview: block.preview,
                content: block.content,
                css: block.css,
                js: block.js,
                is_global: block.is_global,
                is_active: block.is_active
            };
            this.showModal = true;
        },
        
        saveBlock() {
            const url = this.editing 
                ? `{{ route('admin.website-builder.blocks.update', '') }}/${this.form.id}`
                : '{{ route('admin.website-builder.blocks.store') }}';
            
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
            .catch(error => console.error('Error saving block:', error));
        },
        
        deleteBlock(id) {
            if (confirm('Are you sure you want to delete this block?')) {
                fetch(`{{ route('admin.website-builder.blocks.destroy', '') }}/${id}`, {
                    method: 'DELETE',
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
                .catch(error => console.error('Error deleting block:', error));
            }
        }
    }
}
</script>
@endpush