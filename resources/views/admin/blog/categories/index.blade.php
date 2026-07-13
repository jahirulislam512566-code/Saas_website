{{-- resources/views/admin/categories/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Categories Management')

@section('breadcrumb')
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
            <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
            <p class="text-sm text-gray-500 mt-1">Organize your content with categories and subcategories</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.categories.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <a href="{{ route('admin.categories.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                <i class="fas fa-plus mr-2"></i> Add Category
            </a>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Categories</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Featured</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['featured'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Subcategories</p>
                    <p class="text-xl font-bold text-purple-600">{{ $stats['children'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-sitemap"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTERS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    <i class="fas fa-search mr-1 text-gray-400"></i> Search
                </label>
                <input type="text" name="search" placeholder="Search categories..." value="{{ request('search') }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    <i class="fas fa-filter mr-1 text-gray-400"></i> Status
                </label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    <i class="fas fa-star mr-1 text-gray-400"></i> Featured
                </label>
                <select name="featured" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                    <option value="">All</option>
                    <option value="true" {{ request('featured') == 'true' ? 'selected' : '' }}>Featured</option>
                    <option value="false" {{ request('featured') == 'false' ? 'selected' : '' }}>Not Featured</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    <i class="fas fa-sitemap mr-1 text-gray-400"></i> Parent
                </label>
                <select name="parent_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                    <option value="">All Categories</option>
                    <option value="null" {{ request('parent_id') == 'null' ? 'selected' : '' }}>Parent Categories</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm text-center">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- ===== CATEGORIES TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white flex flex-wrap items-center justify-between gap-2">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-tags text-gray-500 mr-2"></i>
                    Categories
                    <span class="ml-2 text-sm font-normal text-gray-500">
                        ({{ $categories->total() }} categories)
                    </span>
                </h3>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="toggleView()" class="text-gray-400 hover:text-gray-600 transition-colors" title="Toggle View">
                    <i class="fas fa-th-list" id="viewIcon"></i>
                </button>
                <button onclick="refreshCategories()" class="text-gray-400 hover:text-gray-600 transition-colors" title="Refresh">
                    <i class="fas fa-sync" id="refreshIcon"></i>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posts</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="categoriesContainer">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition-colors group" data-id="{{ $category->id }}">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $category->id }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: {{ $category->color_hex }}20;">
                                        <i class="{{ $category->icon ?? 'fas fa-folder' }}" style="color: {{ $category->color_hex }};"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $category->slug }}</p>
                                    </div>
                                    @if($category->is_featured)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-star text-xs mr-0.5"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($category->parent)
                                    <span class="text-sm text-gray-600">{{ $category->parent->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ $category->post_count }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <button onclick="toggleStatus('{{ $category->id }}')" 
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all
                                        {{ $category->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $category->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-500">{{ $category->sort_order }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-500">
                                    <div>{{ $category->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $category->created_at->diffForHumans() }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('admin.categories.show', $category) }}" 
                                       class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                       title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                       title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </a>
                                    <button onclick="deleteCategory('{{ $category->id }}', '{{ $category->name }}')" 
                                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <i class="fas fa-tags text-gray-300 text-4xl mb-3 block"></i>
                                <p class="text-gray-500 text-lg font-medium">No categories found</p>
                                <p class="text-gray-400 text-sm mt-1">Create your first category to organize your content</p>
                                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i> Create Category
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-wrap items-center justify-between gap-3">
            <div class="text-sm text-gray-500">
                Showing <span class="font-medium">{{ $categories->firstItem() ?? 0 }}</span> to 
                <span class="font-medium">{{ $categories->lastItem() ?? 0 }}</span> of 
                <span class="font-medium">{{ $categories->total() }}</span> results
            </div>
            <div>
                {{ $categories->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- ===== DELETE FORM ===== -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- ===== TOGGLE FORM ===== -->
<form id="toggle-form" method="POST" style="display: none;">
    @csrf
</form>

@push('scripts')
<script src="{{ asset('js/admin/categories.js') }}"></script>
<script>
    // Override the delete function to use AJAX
    function deleteCategory(id, name) {
        if (!confirm(`⚠️ Are you sure you want to delete category "${name}"? This action cannot be undone.`)) {
            return;
        }
        
        fetch(`/admin/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchCategories();
                showToast('success', data.message);
            } else {
                showToast('error', data.message || 'Failed to delete category');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Failed to delete category');
        });
    }

    function showToast(type, message) {
        // Implement toast notification
        alert(message);
    }
</script>
@endpush

@push('styles')
<style>
    .card-hover {
        transition: all 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
</style>
@endpush
@endsection