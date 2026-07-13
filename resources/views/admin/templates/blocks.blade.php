{{-- resources/views/admin/templates/blocks.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $template->name . ' - Blocks')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.templates.index') }}" class="text-gray-500 hover:text-gray-700">Templates</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">{{ $template->name }}</span>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Blocks</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $template->name }} - Blocks</h1>
            <p class="text-sm text-gray-500 mt-1">Manage template blocks and layouts</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="showAddBlock()" 
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Block
            </button>
            <a href="{{ route('admin.templates.show', $template) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Blocks Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($blocks as $block)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                                <i class="fas {{ $block->icon ?? 'fa-cube' }}"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">{{ $block->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $block->type }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $block->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $block->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                <div class="p-4">
                    <p class="text-sm text-gray-600 line-clamp-2">{{ $block->description ?? 'No description' }}</p>
                    
                    <div class="mt-3 flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                            <span><i class="fas fa-arrows-alt mr-1"></i>Order: {{ $block->sort_order }}</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <button onclick="previewBlock('{{ $block->id }}')" 
                                    class="p-1.5 text-gray-400 hover:text-green-600 transition-colors rounded-lg hover:bg-green-50" 
                                    title="Preview">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            <button onclick="editBlock('{{ $block->id }}')" 
                                    class="p-1.5 text-gray-400 hover:text-primary-600 transition-colors rounded-lg hover:bg-primary-50" 
                                    title="Edit">
                                <i class="fas fa-pen text-sm"></i>
                            </button>
                            <button onclick="duplicateBlock('{{ $block->id }}')" 
                                    class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors rounded-lg hover:bg-blue-50" 
                                    title="Duplicate">
                                <i class="fas fa-copy text-sm"></i>
                            </button>
                            <button onclick="deleteBlock('{{ $block->id }}', '{{ $block->name }}')" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50" 
                                    title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-cubes text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No blocks found</p>
                    <p class="text-sm text-gray-500 mt-1">Add blocks to this template</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Add/Edit Block Modal -->
<div x-data="{ show: false, editing: false, blockId: null }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="block-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="block-modal-title">Add Block</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="block_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Block Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="block_name" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="e.g., Hero Section">
                                </div>
                                <div>
                                    <label for="block_type" class="block text-sm font-medium text-gray-700 mb-1">
                                        Type <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" id="block_type" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="hero">Hero</option>
                                        <option value="feature">Feature</option>
                                        <option value="content">Content</option>
                                        <option value="gallery">Gallery</option>
                                        <option value="testimonial">Testimonial</option>
                                        <option value="pricing">Pricing</option>
                                        <option value="contact">Contact</option>
                                        <option value="footer">Footer</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="block_icon" class="block text-sm font-medium text-gray-700 mb-1">
                                        Icon
                                    </label>
                                    <input type="text" name="icon" id="block_icon"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="fa-cube">
                                </div>
                                <div>
                                    <label for="block_description" class="block text-sm font-medium text-gray-700 mb-1">
                                        Description
                                    </label>
                                    <textarea name="description" id="block_description" rows="2"
                                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                              placeholder="Brief description"></textarea>
                                </div>
                                <div>
                                    <label for="block_content" class="block text-sm font-medium text-gray-700 mb-1">
                                        Content (HTML)
                                    </label>
                                    <textarea name="content" id="block_content" rows="4"
                                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 font-mono text-sm"
                                              placeholder="<div>Block content here</div>"></textarea>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_active" value="1" checked
                                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Active</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Block
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="block-delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function showAddBlock() {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.show = true;
        modal.editing = false;
        modal.blockId = null;
        document.getElementById('block-modal-title').textContent = 'Add Block';
        document.getElementById('block-form').action = '{{ route("admin.templates.blocks.store", $template) }}';
        document.getElementById('block-form').querySelector('input[name="_method"]')?.remove();
        document.getElementById('block_name').value = '';
        document.getElementById('block_type').value = 'hero';
        document.getElementById('block_icon').value = '';
        document.getElementById('block_description').value = '';
        document.getElementById('block_content').value = '';
        document.querySelector('#block-form input[name="is_active"]').checked = true;
    }

    function editBlock(blockId) {
        fetch(`/admin/templates/blocks/${blockId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.querySelector('[x-data]').__x.$data;
                    modal.show = true;
                    modal.editing = true;
                    modal.blockId = blockId;
                    document.getElementById('block-modal-title').textContent = 'Edit Block';
                    document.getElementById('block-form').action = `/admin/templates/blocks/${blockId}`;
                    
                    let methodInput = document.getElementById('block-form').querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        document.getElementById('block-form').appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    
                    document.getElementById('block_name').value = data.data.name;
                    document.getElementById('block_type').value = data.data.type;
                    document.getElementById('block_icon').value = data.data.icon || '';
                    document.getElementById('block_description').value = data.data.description || '';
                    document.getElementById('block_content').value = data.data.content || '';
                    document.querySelector('#block-form input[name="is_active"]').checked = data.data.is_active;
                }
            });
    }

    function deleteBlock(blockId, blockName) {
        if (confirm(`Delete block "${blockName}"?`)) {
            const form = document.getElementById('block-delete-form');
            form.action = `/admin/templates/blocks/${blockId}`;
            form.submit();
        }
    }

    function duplicateBlock(blockId) {
        if (confirm('Duplicate this block?')) {
            fetch(`/admin/templates/blocks/${blockId}/duplicate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => window.location.reload());
        }
    }

    function previewBlock(blockId) {
        window.open(`/admin/templates/blocks/${blockId}/preview`, '_blank');
    }
</script>
@endpush
@endsection