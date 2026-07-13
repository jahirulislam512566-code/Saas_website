{{-- resources/views/admin/templates/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Templates Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Templates</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Templates</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all website templates and layouts</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.templates.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <a href="{{ route('admin.templates.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Template
            </a>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Templates</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-layer-group"></i>
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
                    <p class="text-sm text-gray-500">Total Uses</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['total_uses'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-sync"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTERS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" placeholder="Search templates..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.templates.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- ===== TEMPLATES GRID ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $template)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                <!-- Template Preview -->
                <div class="relative h-48 bg-gray-100 overflow-hidden">
                    @if($template->preview_image)
                        <img src="{{ Storage::disk('public')->url($template->preview_image) }}" 
                             alt="{{ $template->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100">
                            <div class="text-center">
                                <i class="fas fa-code text-4xl text-primary-400"></i>
                                <p class="mt-2 text-sm text-gray-500">{{ $template->name }}</p>
                            </div>
                        </div>
                    @endif
                    <div class="absolute top-3 right-3 flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($template->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star text-xs mr-1"></i> Featured
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Template Info -->
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $template->name }}</h3>
                            <p class="text-sm text-gray-500 truncate">{{ $template->category->name ?? 'Uncategorized' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-2 flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                            <span><i class="fas fa-sync mr-1"></i>{{ $template->uses_count ?? 0 }} uses</span>
                            <span>•</span>
                            <span>{{ $template->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <a href="{{ route('admin.templates.preview', $template) }}" target="_blank"
                               class="p-1.5 text-gray-400 hover:text-green-600 transition-colors rounded-lg hover:bg-green-50" 
                               title="Preview">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('admin.templates.edit', $template) }}" 
                               class="p-1.5 text-gray-400 hover:text-primary-600 transition-colors rounded-lg hover:bg-primary-50" 
                               title="Edit">
                                <i class="fas fa-pen text-sm"></i>
                            </a>
                            <a href="{{ route('admin.templates.blocks', $template) }}" 
                               class="p-1.5 text-gray-400 hover:text-purple-600 transition-colors rounded-lg hover:bg-purple-50" 
                               title="Blocks">
                                <i class="fas fa-cubes text-sm"></i>
                            </a>
                            <button onclick="duplicateTemplate('{{ $template->id }}')" 
                                    class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors rounded-lg hover:bg-blue-50" 
                                    title="Duplicate">
                                <i class="fas fa-copy text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-layer-group text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No templates found</p>
                    <p class="text-sm text-gray-500 mt-1">Get started by creating your first template</p>
                    <a href="{{ route('admin.templates.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Create Template
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- ===== PAGINATION ===== -->
    <div class="flex items-center justify-between">
        @if($templates instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="text-sm text-gray-500">
                Showing {{ $templates->firstItem() ?? 0 }} to {{ $templates->lastItem() ?? 0 }} of {{ $templates->total() }} results
            </div>
            <div>
                {{ $templates->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-sm text-gray-500">
                Showing {{ $templates->count() }} results
            </div>
        @endif
    </div>
</div>

<!-- Duplicate Form -->
<form id="duplicate-form" method="POST" style="display: none;">
    @csrf
</form>

@push('scripts')
<script>
    function duplicateTemplate(templateId) {
        if (confirm('Duplicate this template?')) {
            const form = document.getElementById('duplicate-form');
            form.action = `/admin/templates/${templateId}/duplicate`;
            form.submit();
        }
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