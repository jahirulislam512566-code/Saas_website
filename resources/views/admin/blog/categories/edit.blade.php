{{-- resources/views/admin/categories/edit.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Edit Category')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-700">Categories</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">{{ $category->name }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <span class="text-green-700">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <span class="text-red-700">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Edit Category</h2>
                <p class="text-sm text-gray-500 mt-1">Update category information and settings</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $category->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                </span>
                <span class="text-gray-300">|</span>
                <span class="text-xs text-gray-500">ID: #{{ $category->id }}</span>
                <a href="{{ route('admin.categories.show', $category) }}" 
                   class="text-gray-400 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-blue-50"
                   title="View Category">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
        
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="p-6" id="categoryForm">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-gray-400 mr-2"></i>
                        Basic Information
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $category->name) }}" 
                                       required
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                       placeholder="Enter category name">
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                                Slug <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-link text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="slug" 
                                       id="slug" 
                                       value="{{ old('slug', $category->slug) }}" 
                                       required
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                                       placeholder="slug">
                            </div>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Parent Category
                        </label>
                        <select name="parent_id" id="parent_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('parent_id') border-red-500 @enderror">
                            <option value="">None (Top Level)</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="Brief description of the category">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Visual Settings -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-palette text-gray-400 mr-2"></i>
                        Visual Settings
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">
                                Icon
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-icons text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="icon" 
                                       id="icon" 
                                       value="{{ old('icon', $category->icon) }}"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('icon') border-red-500 @enderror"
                                       placeholder="fa-folder">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Font Awesome icon class (e.g., fa-folder, fa-tag)</p>
                            @error('icon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 mb-1">
                                Color
                            </label>
                            <select name="color" id="color" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('color') border-red-500 @enderror">
                                <option value="gray" {{ old('color', $category->color) == 'gray' ? 'selected' : '' }}>Gray</option>
                                <option value="red" {{ old('color', $category->color) == 'red' ? 'selected' : '' }}>Red</option>
                                <option value="orange" {{ old('color', $category->color) == 'orange' ? 'selected' : '' }}>Orange</option>
                                <option value="amber" {{ old('color', $category->color) == 'amber' ? 'selected' : '' }}>Amber</option>
                                <option value="yellow" {{ old('color', $category->color) == 'yellow' ? 'selected' : '' }}>Yellow</option>
                                <option value="green" {{ old('color', $category->color) == 'green' ? 'selected' : '' }}>Green</option>
                                <option value="emerald" {{ old('color', $category->color) == 'emerald' ? 'selected' : '' }}>Emerald</option>
                                <option value="teal" {{ old('color', $category->color) == 'teal' ? 'selected' : '' }}>Teal</option>
                                <option value="cyan" {{ old('color', $category->color) == 'cyan' ? 'selected' : '' }}>Cyan</option>
                                <option value="blue" {{ old('color', $category->color) == 'blue' ? 'selected' : '' }}>Blue</option>
                                <option value="indigo" {{ old('color', $category->color) == 'indigo' ? 'selected' : '' }}>Indigo</option>
                                <option value="purple" {{ old('color', $category->color) == 'purple' ? 'selected' : '' }}>Purple</option>
                                <option value="pink" {{ old('color', $category->color) == 'pink' ? 'selected' : '' }}>Pink</option>
                            </select>
                            @error('color')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                            Category Image
                        </label>
                        @if($category->image)
                            <div class="mb-3 flex items-center space-x-4">
                                <img src="{{ Storage::disk('public')->url($category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-20 h-20 object-cover rounded-lg">
                                <button type="button" 
                                        onclick="deleteImage('{{ $category->id }}')"
                                        class="text-sm text-red-600 hover:text-red-700">
                                    <i class="fas fa-trash mr-1"></i> Remove
                                </button>
                            </div>
                        @endif
                        <input type="file" 
                               name="image" 
                               id="image" 
                               accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB. Leave blank to keep current image.</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SEO Settings -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-search text-gray-400 mr-2"></i>
                        SEO Settings
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">
                                Meta Title
                            </label>
                            <input type="text" 
                                   name="meta_title" 
                                   id="meta_title" 
                                   value="{{ old('meta_title', $category->meta_title) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('meta_title') border-red-500 @enderror"
                                   placeholder="Meta title for SEO">
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">
                                Meta Description
                            </label>
                            <textarea name="meta_description" 
                                      id="meta_description" 
                                      rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('meta_description') border-red-500 @enderror"
                                      placeholder="Meta description for SEO">{{ old('meta_description', $category->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">
                                Meta Keywords
                            </label>
                            <input type="text" 
                                   name="meta_keywords" 
                                   id="meta_keywords" 
                                   value="{{ old('meta_keywords', $category->meta_keywords) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('meta_keywords') border-red-500 @enderror"
                                   placeholder="keyword1, keyword2, keyword3">
                            @error('meta_keywords')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-toggle-on text-gray-400 mr-2"></i>
                        Status
                    </h4>
                    
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Featured Category</span>
                            <span class="ml-2 text-xs text-gray-400">(Will be highlighted in the frontend)</span>
                        </label>
                    </div>
                </div>

                <!-- Category Statistics -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-simple text-gray-400 mr-2"></i>
                        Category Statistics
                    </h4>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-xl font-bold text-gray-900">{{ $category->post_count }}</p>
                            <p class="text-xs text-gray-500">Posts</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-xl font-bold text-gray-900">{{ $category->children_count }}</p>
                            <p class="text-xs text-gray-500">Subcategories</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-xl font-bold text-gray-900">{{ $category->level }}</p>
                            <p class="text-xs text-gray-500">Level</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-xl font-bold text-gray-900">{{ $category->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">Created</p>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex items-center space-x-3">
                        <span class="text-xs text-gray-400">Last updated: {{ $category->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.categories.index') }}" 
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                        <button type="submit" 
                                id="submitBtn"
                                class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-save mr-2"></i> Update Category
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden border-2 border-red-200">
        <div class="px-6 py-4 border-b border-red-200 bg-red-50">
            <h3 class="text-sm font-bold text-red-700 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Danger Zone
            </h3>
        </div>
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900">Delete this category</p>
                    <p class="text-xs text-gray-500">This action cannot be undone. All associated data will be removed.</p>
                </div>
                <button onclick="deleteCategory('{{ $category->id }}', '{{ $category->name }}')" 
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    <i class="fas fa-trash mr-2"></i> Delete Category
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Image Form -->
<form id="delete-image-form" method="POST" action="{{ route('admin.categories.image.delete', $category) }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    // Delete category
    function deleteCategory(id, name) {
        if (!confirm(`⚠️ Are you sure you want to delete category "${name}"? This action cannot be undone.`)) {
            return;
        }
        
        const form = document.getElementById('delete-form');
        form.action = `/admin/categories/${id}`;
        form.submit();
    }

    // Delete image
    function deleteImage(id) {
        if (!confirm('Are you sure you want to remove the category image?')) {
            return;
        }
        document.getElementById('delete-image-form').submit();
    }

    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const slugField = document.getElementById('slug');
        if (!slugField.dataset.manual) {
            slugField.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });
    
    document.getElementById('slug').addEventListener('focus', function() {
        this.dataset.manual = 'true';
    });

    // Form submission loading state
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
    });
</script>
@endpush
@endsection