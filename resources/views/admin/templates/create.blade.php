{{-- resources/views/admin/templates/create.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Create Template')

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
            <span class="text-gray-500">Create</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Create New Template</h2>
            <p class="text-sm text-gray-500 mt-1">Create a new template for websites</p>
        </div>
        
        <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Template Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-500 @enderror"
                                   placeholder="e.g., Modern Business">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                                Slug
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('slug') border-red-500 @enderror"
                                   placeholder="auto-generated">
                            <p class="mt-1 text-xs text-gray-500">Leave blank to auto-generate from name</p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" id="category_id" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('category_id') border-red-500 @enderror">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('description') border-red-500 @enderror"
                                  placeholder="Brief description of the template">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview & Demo -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Preview & Demo</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="preview_image" class="block text-sm font-medium text-gray-700 mb-1">
                                Preview Image
                            </label>
                            <input type="file" name="preview_image" id="preview_image" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG up to 5MB</p>
                            @error('preview_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="demo_url" class="block text-sm font-medium text-gray-700 mb-1">
                                Demo URL
                            </label>
                            <input type="url" name="demo_url" id="demo_url" value="{{ old('demo_url') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('demo_url') border-red-500 @enderror"
                                   placeholder="https://demo.example.com">
                            @error('demo_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Status</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                        
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Featured Template</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.templates.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Create Template
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
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
</script>
@endpush
@endsection