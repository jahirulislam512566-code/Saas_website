{{-- resources/views/admin/templates/categories.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Template Categories')

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
            <span class="text-gray-500">Categories</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Template Categories</h1>
            <p class="text-sm text-gray-500 mt-1">Organize templates into categories</p>
        </div>
        <button onclick="showAddCategory()" 
                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Add Category
        </button>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Categories</p>
            <p class="text-xl font-bold text-gray-900">{{ $categories->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Templates</p>
            <p class="text-xl font-bold text-blue-600">{{ $totalTemplates ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Avg. Templates per Category</p>
            <p class="text-xl font-bold text-green-600">{{ $categories->count() > 0 ? round($totalTemplates / $categories->count(), 1) : 0 }}</p>
        </div>
    </div>

    <!-- ===== CATEGORIES GRID ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: {{ $category->color_hex }}20;">
                                <i class="fas {{ $category->icon ?? 'fa-folder' }}" style="color: {{ $category->color_hex }};"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $category->templates_count ?? 0 }} templates</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            <a href="{{ route('admin.templates.by-category', $category) }}" 
                               class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors rounded-lg hover:bg-blue-50" 
                               title="View Templates">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <button onclick="editCategory('{{ $category->id }}')" 
                                    class="p-1.5 text-gray-400 hover:text-primary-600 transition-colors rounded-lg hover:bg-primary-50" 
                                    title="Edit">
                                <i class="fas fa-pen text-sm"></i>
                            </button>
                            <button onclick="deleteCategory('{{ $category->id }}', '{{ $category->name }}')" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50" 
                                    title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    @if($category->description)
                        <p class="mt-3 text-sm text-gray-600">{{ Str::limit($category->description, 100) }}</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-tags text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No categories found</p>
                    <p class="text-sm text-gray-500 mt-1">Create your first category to organize templates</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div x-data="{ show: false, editing: false, categoryId: null }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="category-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Add Category</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="category_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Category Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="category_name" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="e.g., Business">
                                </div>
                                <div>
                                    <label for="category_slug" class="block text-sm font-medium text-gray-700 mb-1">
                                        Slug
                                    </label>
                                    <input type="text" name="slug" id="category_slug"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="auto-generated">
                                </div>
                                <div>
                                    <label for="category_description" class="block text-sm font-medium text-gray-700 mb-1">
                                        Description
                                    </label>
                                    <textarea name="description" id="category_description" rows="2"
                                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                              placeholder="Brief description"></textarea>
                                </div>
                                <div>
                                    <label for="category_icon" class="block text-sm font-medium text-gray-700 mb-1">
                                        Icon
                                    </label>
                                    <input type="text" name="icon" id="category_icon"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="fa-folder">
                                </div>
                                <div>
                                    <label for="category_color" class="block text-sm font-medium text-gray-700 mb-1">
                                        Color
                                    </label>
                                    <select name="color" id="category_color"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="gray">Gray</option>
                                        <option value="red">Red</option>
                                        <option value="orange">Orange</option>
                                        <option value="yellow">Yellow</option>
                                        <option value="green">Green</option>
                                        <option value="blue">Blue</option>
                                        <option value="indigo">Indigo</option>
                                        <option value="purple">Purple</option>
                                        <option value="pink">Pink</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Category
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
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function showAddCategory() {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.show = true;
        modal.editing = false;
        modal.categoryId = null;
        document.getElementById('modal-title').textContent = 'Add Category';
        document.getElementById('category-form').action = '{{ route("admin.templates.categories.store") }}';
        document.getElementById('category-form').querySelector('input[name="_method"]')?.remove();
        document.getElementById('category_name').value = '';
        document.getElementById('category_slug').value = '';
        document.getElementById('category_description').value = '';
        document.getElementById('category_icon').value = '';
        document.getElementById('category_color').value = 'gray';
    }

    function editCategory(categoryId) {
        fetch(`/admin/templates/categories/${categoryId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.querySelector('[x-data]').__x.$data;
                    modal.show = true;
                    modal.editing = true;
                    modal.categoryId = categoryId;
                    document.getElementById('modal-title').textContent = 'Edit Category';
                    document.getElementById('category-form').action = `/admin/templates/categories/${categoryId}`;
                    
                    // Add method override
                    let methodInput = document.getElementById('category-form').querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        document.getElementById('category-form').appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    
                    document.getElementById('category_name').value = data.data.name;
                    document.getElementById('category_slug').value = data.data.slug;
                    document.getElementById('category_description').value = data.data.description;
                    document.getElementById('category_icon').value = data.data.icon;
                    document.getElementById('category_color').value = data.data.color;
                }
            });
    }

    function deleteCategory(categoryId, categoryName) {
        if (confirm(`Are you sure you want to delete category "${categoryName}"?`)) {
            const form = document.getElementById('delete-form');
            form.action = `/admin/templates/categories/${categoryId}`;
            form.submit();
        }
    }

    // Auto-generate slug from name
    document.getElementById('category_name').addEventListener('input', function() {
        const slugField = document.getElementById('category_slug');
        if (!slugField.dataset.manual) {
            slugField.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });
    
    document.getElementById('category_slug').addEventListener('focus', function() {
        this.dataset.manual = 'true';
    });
</script>
@endpush
@endsection