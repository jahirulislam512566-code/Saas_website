@extends('admin.layouts.admin')

@section('title', 'Create Post')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.posts.index') }}" class="text-gray-500 hover:text-gray-700">Posts</a>
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
<div class="max-w-4xl mx-auto">
    <!-- Alert Messages -->
    @if($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">Please fix the following errors:</p>
                    <ul class="mt-1 text-sm text-red-600 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Create New Post</h2>
            <p class="text-sm text-gray-500 mt-1">Write and publish a new blog post</p>
        </div>

        <form action="{{ route('admin.posts.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" 
                           value="{{ old('title') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('title') border-red-500 @enderror"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" name="slug" id="slug" 
                               value="{{ old('slug') }}"
                               class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('slug') border-red-500 @enderror"
                               placeholder="auto-generated">
                        <button type="button" onclick="generateSlug()" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" id="content" rows="12"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('content') border-red-500 @enderror"
                              required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-xs text-gray-500" id="wordCount">0 words</span>
                        <span class="text-xs text-gray-400">Reading time: <span id="readingTime">0 min read</span></span>
                    </div>
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
                    <textarea name="excerpt" id="excerpt" rows="3"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('excerpt') border-red-500 @enderror">{{ old('excerpt') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Brief summary of the post (max 500 characters)</p>
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category & Tags -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="category_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                        <select name="tags[]" id="tags" multiple 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple tags</p>
                    </div>
                </div>

                <!-- Status & Publish Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>✅ Published</option>
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>📅 Scheduled</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                        </select>
                    </div>
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
                        <input type="datetime-local" name="published_at" id="published_at" 
                               value="{{ old('published_at') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <p class="mt-1 text-xs text-gray-500">Leave empty for immediate publish</p>
                    </div>
                </div>

                <!-- Featured & Comments -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" 
                                   {{ old('is_featured') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">⭐ Featured Post</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="allow_comments" value="1" 
                                   {{ old('allow_comments', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">💬 Allow Comments</span>
                        </label>
                    </div>
                </div>

                <!-- SEO -->
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">SEO Settings</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" 
                                   value="{{ old('meta_title') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="SEO Title">
                        </div>
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="2"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                      placeholder="SEO Description">{{ old('meta_description') }}</textarea>
                        </div>
                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                            <input type="text" name="meta_keywords" id="meta_keywords" 
                                   value="{{ old('meta_keywords') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="keyword1, keyword2, keyword3">
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div>
                        <a href="{{ route('admin.posts.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Posts
                        </a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                            <i class="fas fa-save mr-2"></i> Create Post
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Content word counter
    const contentInput = document.getElementById('content');
    const wordCount = document.getElementById('wordCount');
    const readingTime = document.getElementById('readingTime');
    
    contentInput.addEventListener('input', function() {
        const text = this.value;
        const words = text.trim() ? text.trim().split(/\s+/).length : 0;
        wordCount.textContent = `${words} words`;
        const minutes = Math.ceil(words / 200);
        readingTime.textContent = minutes <= 1 ? '1 min read' : `${minutes} min read`;
    });
});

function generateSlug() {
    const title = document.getElementById('title').value;
    if (title) {
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
    }
}

document.getElementById('title').addEventListener('blur', function() {
    const slugField = document.getElementById('slug');
    if (!slugField.value) {
        generateSlug();
    }
});
</script>
@endpush
@endsection