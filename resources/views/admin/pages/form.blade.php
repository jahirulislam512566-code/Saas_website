{{-- resources/views/admin/pages/form.blade.php --}}
@extends('admin.layouts.admin')

@section('title', isset($page) ? 'Edit Page' : 'Create Page')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.pages.index') }}" class="text-gray-500 hover:text-gray-700">Pages</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">{{ isset($page) ? 'Edit' : 'Create' }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">{{ isset($page) ? 'Edit Page' : 'Create New Page' }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ isset($page) ? 'Update page information' : 'Create a new page for your website' }}</p>
        </div>
        
        <form action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}" 
              method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @if(isset($page))
                @method('PUT')
            @endif
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Page Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $page->title ?? '') }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('title') border-red-500 @enderror"
                                   placeholder="Enter page title">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                                Slug <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug ?? '') }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('slug') border-red-500 @enderror"
                                   placeholder="page-slug">
                            <p class="mt-1 text-xs text-gray-500">URL-friendly identifier</p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="website_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Website <span class="text-red-500">*</span>
                        </label>
                        <select name="website_id" id="website_id" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('website_id') border-red-500 @enderror">
                            <option value="">Select a website</option>
                            @foreach($websites as $website)
                                <option value="{{ $website->id }}" {{ old('website_id', $page->website_id ?? '') == $website->id ? 'selected' : '' }}>
                                    {{ $website->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('website_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">
                            Excerpt
                        </label>
                        <textarea name="excerpt" id="excerpt" rows="2"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('excerpt') border-red-500 @enderror"
                                  placeholder="Brief summary of the page">{{ old('excerpt', $page->excerpt ?? '') }}</textarea>
                        @error('excerpt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Page Settings -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Page Settings</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('status') border-red-500 @enderror">
                                <option value="draft" {{ old('status', $page->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $page->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $page->status ?? '') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="template_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Template
                            </label>
                            <select name="template_id" id="template_id"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('template_id') border-red-500 @enderror">
                                <option value="">Default</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" {{ old('template_id', $page->template_id ?? '') == $template->id ? 'selected' : '' }}>
                                        {{ $template->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('template_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_home" value="1" {{ old('is_home', $page->is_home ?? false) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Set as Home Page</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $page->is_featured ?? false) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Featured Page</span>
                        </label>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">SEO Settings</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">
                                Meta Title
                            </label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('meta_title') border-red-500 @enderror"
                                   placeholder="Meta title for SEO">
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">
                                Meta Description
                            </label>
                            <textarea name="meta_description" id="meta_description" rows="2"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('meta_description') border-red-500 @enderror"
                                      placeholder="Meta description for SEO">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">
                                Meta Keywords
                            </label>
                            <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('meta_keywords') border-red-500 @enderror"
                                   placeholder="keyword1, keyword2, keyword3">
                            @error('meta_keywords')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.pages.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> {{ isset($page) ? 'Update Page' : 'Create Page' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', function() {
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