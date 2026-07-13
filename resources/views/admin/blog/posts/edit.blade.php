@extends('admin.layouts.admin')

@section('title', 'Edit Post: ' . $post->title)

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Post</h1>
            <p class="text-sm text-gray-500 mt-1">Update your blog post</p>
        </div>
        <div class="flex items-center space-x-3">
            @if($post->status != 'published')
                <form action="{{ route('admin.posts.publish', $post) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-check-circle mr-2"></i> Publish
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Status Bar -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center space-x-4">
            <div>
                <span class="text-sm text-gray-500">Status:</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $post->status_color }}-100 text-{{ $post->status_color }}-800 ml-2">
                    {{ $post->status_label }}
                </span>
            </div>
            <div>
                <span class="text-sm text-gray-500">Author:</span>
                <span class="text-sm font-medium text-gray-900 ml-2">{{ $post->author->name ?? 'Unknown' }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-500">Created:</span>
                <span class="text-sm text-gray-900 ml-2">{{ $post->created_at->format('M d, Y') }}</span>
            </div>
            @if($post->published_at)
                <div>
                    <span class="text-sm text-gray-500">Published:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $post->published_at->format('M d, Y') }}</span>
                </div>
            @endif
        </div>
        <div>
            <span class="text-sm text-gray-500">Views:</span>
            <span class="text-sm font-medium text-gray-900 ml-2">{{ number_format($post->views ?? 0) }}</span>
        </div>
    </div>

    <!-- Main Form -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <form action="{{ route('admin.posts.update', $post) }}" method="POST" class="p-6" id="postForm">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-heading text-gray-400"></i>
                        </div>
                        <input type="text" name="title" id="title" 
                               value="{{ old('title', $post->title) }}"
                               class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('title') border-red-500 @enderror"
                               required>
                    </div>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-xs text-gray-500" id="charCount">0 characters</span>
                        <span class="text-xs text-gray-400">Max 255 characters</span>
                    </div>
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <div class="flex items-center space-x-2">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-link text-gray-400"></i>
                            </div>
                            <input type="text" name="slug" id="slug" 
                                   value="{{ old('slug', $post->slug) }}"
                                   class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('slug') border-red-500 @enderror"
                                   placeholder="auto-generated">
                        </div>
                        <button type="button" onclick="generateSlug()" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button type="button" onclick="copySlug()" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">URL-friendly version of the title. Leave empty to auto-generate.</p>
                    <div class="mt-1 text-xs text-gray-400">
                        <i class="fas fa-globe mr-1"></i>
                        Permalink: <span id="permalink">{{ url('/blog') }}/<span id="slugDisplay">{{ $post->slug }}</span></span>
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
                    <textarea name="content" id="content" rows="15"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 font-mono text-sm @error('content') border-red-500 @enderror"
                              required>{{ old('content', $post->content) }}</textarea>
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
                    <div class="relative">
                        <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                            <i class="fas fa-align-left text-gray-400"></i>
                        </div>
                        <textarea name="excerpt" id="excerpt" rows="3"
                                  class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('excerpt') border-red-500 @enderror"
                                  placeholder="Brief summary of the post">{{ old('excerpt', $post->excerpt) }}</textarea>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Brief summary of the post (max 500 characters)</p>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-xs text-gray-400" id="excerptCount">{{ strlen(old('excerpt', $post->excerpt)) }} / 500 characters</span>
                    </div>
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category & Tags -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-folder text-gray-400"></i>
                            </div>
                            <select name="category_id" id="category_id" class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Categorize your post</p>
                    </div>
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tags text-gray-400"></i>
                            </div>
                            <select name="tags[]" id="tags" multiple 
                                    class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple tags</p>
                    </div>
                </div>

                <!-- Featured Image -->
                <div>
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
                    
                    @if($post->featured_image)
                        <div class="mb-3 p-3 bg-gray-50 rounded-lg flex items-center space-x-3">
                            <img src="{{ $post->featured_image_url }}" alt="Featured Image" class="w-16 h-16 object-cover rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Current featured image</p>
                                <button type="button" onclick="removeFeaturedImage()" class="text-xs text-red-600 hover:text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Remove
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        <select name="featured_image" id="featured_image" class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Select Image</option>
                            <!-- This would be populated via AJAX from media library -->
                        </select>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Select from media library or upload a new image</p>
                    <button type="button" onclick="openMediaBrowser()" class="mt-2 text-sm text-primary-600 hover:text-primary-700">
                        <i class="fas fa-folder-open mr-1"></i> Browse Media Library
                    </button>
                </div>

                <!-- Status & Publish Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-circle text-gray-400"></i>
                            </div>
                            <select name="status" id="status" class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>✅ Published</option>
                                <option value="scheduled" {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>📅 Scheduled</option>
                                <option value="archived" {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="datetime-local" name="published_at" id="published_at" 
                                   value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Leave empty for immediate publish</p>
                    </div>
                </div>

                <!-- Featured & Comments -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" 
                                   {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">⭐ Featured Post</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">Highlight this post as featured</p>
                    </div>
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="allow_comments" value="1" 
                                   {{ old('allow_comments', $post->allow_comments) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">💬 Allow Comments</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">Enable comments on this post</p>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-search text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">SEO Settings</h3>
                            <p class="text-xs text-gray-500">Optimize your post for search engines</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" name="meta_title" id="meta_title" 
                                       value="{{ old('meta_title', $post->meta_title) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       placeholder="SEO Title">
                            </div>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-xs text-gray-500">Recommended: 50-60 characters</span>
                                <span class="text-xs text-gray-400" id="metaTitleCount">{{ strlen(old('meta_title', $post->meta_title)) }} characters</span>
                            </div>
                        </div>
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                            <div class="relative">
                                <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                    <i class="fas fa-align-left text-gray-400"></i>
                                </div>
                                <textarea name="meta_description" id="meta_description" rows="2"
                                          class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                          placeholder="SEO Description">{{ old('meta_description', $post->meta_description) }}</textarea>
                            </div>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-xs text-gray-500">Recommended: 150-160 characters</span>
                                <span class="text-xs text-gray-400" id="metaDescCount">{{ strlen(old('meta_description', $post->meta_description)) }} characters</span>
                            </div>
                        </div>
                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                                <input type="text" name="meta_keywords" id="meta_keywords" 
                                       value="{{ old('meta_keywords', $post->meta_keywords) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       placeholder="keyword1, keyword2, keyword3">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Comma separated keywords</p>
                        </div>
                    </div>
                </div>

                <!-- Post Preview -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Post Preview</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-lg font-semibold text-gray-900" id="previewTitle">{{ $post->title }}</h4>
                        <p class="text-sm text-gray-500 mt-1" id="previewExcerpt">{{ $post->excerpt_short }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-xs text-gray-400" id="previewDate">{{ $post->formatted_published_at ?? 'Draft' }}</span>
                            <span class="text-xs text-gray-400" id="previewReadingTime">{{ $post->reading_time }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div>
                        <a href="{{ route('admin.posts.show', $post) }}" class="text-sm text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye mr-1"></i> View Post
                        </a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                            <i class="fas fa-save mr-2"></i> Update Post
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Media Browser Modal -->
<div id="mediaModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>
    <div class="relative bg-white rounded-xl shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Media Library</h3>
            <button onclick="closeMediaBrowser()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4 overflow-y-auto max-h-[calc(90vh-120px)]">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="mediaGrid">
                <!-- Media items will be loaded here -->
                <div class="col-span-full text-center py-8 text-gray-500">
                    <i class="fas fa-spinner fa-spin text-3xl mb-3 block"></i>
                    Loading media...
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Title character counter
    const titleInput = document.getElementById('title');
    const charCount = document.getElementById('charCount');
    
    titleInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length} / 255 characters`;
        if (length > 255) {
            charCount.classList.add('text-red-600');
        } else {
            charCount.classList.remove('text-red-600');
        }
        // Update preview
        document.getElementById('previewTitle').textContent = this.value || 'Untitled';
    });
    titleInput.dispatchEvent(new Event('input'));

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
    contentInput.dispatchEvent(new Event('input'));

    // Excerpt counter
    const excerptInput = document.getElementById('excerpt');
    const excerptCount = document.getElementById('excerptCount');
    
    excerptInput.addEventListener('input', function() {
        const length = this.value.length;
        excerptCount.textContent = `${length} / 500 characters`;
        if (length > 500) {
            excerptCount.classList.add('text-red-600');
        } else {
            excerptCount.classList.remove('text-red-600');
        }
        // Update preview
        document.getElementById('previewExcerpt').textContent = this.value || 'No excerpt provided';
    });
    excerptInput.dispatchEvent(new Event('input'));

    // Meta title counter
    const metaTitle = document.getElementById('meta_title');
    const metaTitleCount = document.getElementById('metaTitleCount');
    
    metaTitle.addEventListener('input', function() {
        const length = this.value.length;
        metaTitleCount.textContent = `${length} characters`;
    });

    // Meta description counter
    const metaDesc = document.getElementById('meta_description');
    const metaDescCount = document.getElementById('metaDescCount');
    
    metaDesc.addEventListener('input', function() {
        const length = this.value.length;
        metaDescCount.textContent = `${length} characters`;
    });
});

// Generate slug from title
function generateSlug() {
    const title = document.getElementById('title').value;
    if (title) {
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
        document.getElementById('slugDisplay').textContent = slug;
        document.getElementById('permalink').textContent = `{{ url('/blog') }}/${slug}`;
    }
}

// Auto-generate slug from title on blur
document.getElementById('title').addEventListener('blur', function() {
    const slugField = document.getElementById('slug');
    if (!slugField.value) {
        generateSlug();
    }
});

// Update slug display
document.getElementById('slug').addEventListener('input', function() {
    const slug = this.value || 'untitled';
    document.getElementById('slugDisplay').textContent = slug;
    document.getElementById('permalink').textContent = `{{ url('/blog') }}/${slug}`;
});

// Copy slug to clipboard
function copySlug() {
    const slug = document.getElementById('slug').value;
    if (slug) {
        navigator.clipboard.writeText(slug).then(() => {
            showToast('success', 'Slug copied to clipboard!');
        });
    }
}

// Remove featured image
function removeFeaturedImage() {
    if (confirm('Are you sure you want to remove the featured image?')) {
        fetch('{{ route("admin.posts.remove-image", $post) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

// Open media browser
function openMediaBrowser() {
    const modal = document.getElementById('mediaModal');
    modal.classList.remove('hidden');
    loadMedia();
}

// Close media browser
function closeMediaBrowser() {
    document.getElementById('mediaModal').classList.add('hidden');
}

// Load media from library
function loadMedia() {
    const grid = document.getElementById('mediaGrid');
    fetch('{{ route("admin.media.browser-api") }}')
        .then(response => response.json())
        .then(data => {
            if (data.data) {
                grid.innerHTML = '';
                data.data.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'bg-gray-50 rounded-lg overflow-hidden cursor-pointer hover:ring-2 hover:ring-primary-500 transition-all';
                    div.innerHTML = `
                        <img src="${item.thumbnail}" alt="${item.name}" class="w-full h-32 object-cover">
                        <div class="p-2">
                            <p class="text-xs font-medium text-gray-700 truncate">${item.original_name}</p>
                            <p class="text-xs text-gray-500">${item.formatted_size}</p>
                        </div>
                    `;
                    div.onclick = () => selectMedia(item.id, item.url);
                    grid.appendChild(div);
                });
            }
        });
}

// Select media from browser
function selectMedia(id, url) {
    if (confirm('Set this as featured image?')) {
        document.getElementById('featured_image').value = id;
        closeMediaBrowser();
        showToast('success', 'Featured image selected!');
    }
}

// Show toast notification
function showToast(type, message) {
    const toast = document.createElement('div');
    const colors = {
        success: 'bg-green-50 border-green-500 text-green-700',
        error: 'bg-red-50 border-red-500 text-red-700',
        warning: 'bg-yellow-50 border-yellow-500 text-yellow-700',
        info: 'bg-blue-50 border-blue-500 text-blue-700'
    };
    
    toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg border-l-4 shadow-lg max-w-sm ${colors[type] || colors.info}`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-3"></i>
            <span class="text-sm font-medium">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.5s ease';
        setTimeout(() => toast.remove(), 500);
    }, 5000);
}
</script>
@endpush
@endsection