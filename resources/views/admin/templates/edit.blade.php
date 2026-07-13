{{-- resources/views/admin/templates/edit.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Edit Template')

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
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Edit Template</h2>
                <p class="text-sm text-gray-500 mt-1">Update template information and settings</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $template->is_active ? 'Active' : 'Inactive' }}
                </span>
                <a href="{{ route('admin.templates.preview', $template) }}" target="_blank"
                   class="text-gray-400 hover:text-green-600 transition-colors" title="Preview">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
        
        <form action="{{ route('admin.templates.update', $template) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Template Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $template->name) }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-500 @enderror"
                                   placeholder="e.g., Modern Business">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                                Slug <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $template->slug) }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('slug') border-red-500 @enderror"
                                   placeholder="modern-business">
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
                                <option value="{{ $category->id }}" {{ old('category_id', $template->category_id) == $category->id ? 'selected' : '' }}>
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
                                  placeholder="Brief description of the template">{{ old('description', $template->description) }}</textarea>
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
                            @if($template->preview_image)
                                <div class="mb-3">
                                    <img src="{{ Storage::disk('public')->url($template->preview_image) }}" 
                                         alt="{{ $template->name }}" 
                                         class="w-full max-w-md rounded-lg border border-gray-200">
                                    <button type="button" onclick="removePreviewImage()" 
                                            class="mt-2 text-sm text-red-600 hover:text-red-700">
                                        <i class="fas fa-trash mr-1"></i> Remove Image
                                    </button>
                                </div>
                            @endif
                            <input type="file" name="preview_image" id="preview_image" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG up to 5MB. Leave blank to keep current.</p>
                            @error('preview_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="demo_url" class="block text-sm font-medium text-gray-700 mb-1">
                                Demo URL
                            </label>
                            <input type="url" name="demo_url" id="demo_url" value="{{ old('demo_url', $template->demo_url) }}"
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
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $template->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                        
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $template->is_featured) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Featured Template</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Template Stats -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Template Statistics</h4>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $template->uses_count ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Total Uses</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $template->blocks_count ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Blocks</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $template->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">Created</p>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex items-center space-x-3">
                        <span class="text-xs text-gray-400">Last updated: {{ $template->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.templates.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                            <i class="fas fa-save mr-2"></i> Update Template
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
                    <p class="text-sm font-medium text-gray-900">Delete this template</p>
                    <p class="text-xs text-gray-500">This action cannot be undone. All template data will be permanently removed.</p>
                </div>
                <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this template? This action cannot be undone!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        <i class="fas fa-trash mr-2"></i> Delete Template
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function removePreviewImage() {
        if (confirm('Remove the preview image?')) {
            fetch('{{ route('admin.templates.image.destroy', $template) }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => window.location.reload());
        }
    }
</script>
@endpush
@endsection