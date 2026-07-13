{{-- resources/views/admin/templates/preview.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $template->name . ' - Preview')

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
            <span class="text-gray-500">Preview</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center">
                <i class="fas fa-code text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $template->name }}</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-sm text-gray-500">{{ $template->category->name ?? 'Uncategorized' }}</span>
                    <span class="text-gray-300">|</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $template->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            @if($template->demo_url)
                <a href="{{ $template->demo_url }}" target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i> View Demo
                </a>
            @endif
            <a href="{{ route('admin.templates.edit', $template) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-pen mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.templates.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- ===== TEMPLATE PREVIEW ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-500">Preview</span>
                <span class="text-xs text-gray-300">|</span>
                <span class="text-xs text-gray-500">{{ $template->name }}</span>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="toggleDevice('desktop')" 
                        class="px-3 py-1 text-sm bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-desktop mr-1"></i> Desktop
                </button>
                <button onclick="toggleDevice('tablet')" 
                        class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-tablet-alt mr-1"></i> Tablet
                </button>
                <button onclick="toggleDevice('mobile')" 
                        class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-mobile-alt mr-1"></i> Mobile
                </button>
            </div>
        </div>
        
        <div class="p-6 flex items-center justify-center bg-gray-100 min-h-[500px]">
            <div id="preview-container" class="bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300" style="width: 100%; max-width: 1200px;">
                @if($template->preview_image)
                    <img src="{{ Storage::disk('public')->url($template->preview_image) }}" 
                         alt="{{ $template->name }}" 
                         class="w-full">
                @else
                    <div class="p-12 text-center">
                        <i class="fas fa-code text-6xl text-gray-300 mb-4 block"></i>
                        <h3 class="text-xl font-medium text-gray-900">{{ $template->name }}</h3>
                        <p class="text-gray-500 mt-2">{{ $template->description ?? 'No description available' }}</p>
                        <div class="mt-6 flex justify-center space-x-4">
                            <div class="w-16 h-16 bg-gray-200 rounded-lg"></div>
                            <div class="w-16 h-16 bg-gray-200 rounded-lg"></div>
                            <div class="w-16 h-16 bg-gray-200 rounded-lg"></div>
                        </div>
                        <div class="mt-4 max-w-md mx-auto space-y-3">
                            <div class="h-4 bg-gray-200 rounded w-3/4 mx-auto"></div>
                            <div class="h-4 bg-gray-200 rounded w-1/2 mx-auto"></div>
                            <div class="h-4 bg-gray-200 rounded w-2/3 mx-auto"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ===== TEMPLATE DETAILS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Description</h3>
                @if($template->description)
                    <p class="text-gray-600">{{ $template->description }}</p>
                @else
                    <p class="text-gray-400 italic">No description provided</p>
                @endif
            </div>

            <!-- Features -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Features</h3>
                @if($template->features && count($template->features) > 0)
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($template->features as $feature)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                {{ $feature }}
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 italic">No features listed</p>
                @endif
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Template Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-500">ID</dt>
                        <dd class="text-sm text-gray-900">#{{ $template->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Name</dt>
                        <dd class="text-sm text-gray-900">{{ $template->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Category</dt>
                        <dd class="text-sm text-gray-900">{{ $template->category->name ?? 'Uncategorized' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Status</dt>
                        <dd class="text-sm text-gray-900">{{ $template->is_active ? 'Active' : 'Inactive' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Featured</dt>
                        <dd class="text-sm text-gray-900">{{ $template->is_featured ? 'Yes' : 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Total Uses</dt>
                        <dd class="text-sm text-gray-900">{{ $template->uses_count ?? 0 }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Blocks</dt>
                        <dd class="text-sm text-gray-900">{{ $template->blocks_count ?? 0 }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Created</dt>
                        <dd class="text-sm text-gray-900">{{ $template->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $template->updated_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    @if($template->demo_url)
                        <a href="{{ $template->demo_url }}" target="_blank" 
                           class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-external-link-alt mr-2"></i> Live Demo
                        </a>
                    @endif
                    <a href="{{ route('admin.templates.blocks', $template) }}" 
                       class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-cubes mr-2"></i> Manage Blocks
                    </a>
                    <button onclick="duplicateTemplate('{{ $template->id }}')" 
                            class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-copy mr-2"></i> Duplicate Template
                    </button>
                </div>
            </div>
        </div>
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

    function toggleDevice(device) {
        const container = document.getElementById('preview-container');
        const buttons = document.querySelectorAll('.flex.items-center.space-x-2 button');
        
        buttons.forEach(btn => {
            btn.classList.remove('bg-primary-600', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        });
        
        const clickedBtn = event.target.closest('button');
        clickedBtn.classList.remove('bg-gray-100', 'text-gray-700');
        clickedBtn.classList.add('bg-primary-600', 'text-white');
        
        switch(device) {
            case 'desktop':
                container.style.maxWidth = '1200px';
                container.style.width = '100%';
                break;
            case 'tablet':
                container.style.maxWidth = '768px';
                container.style.width = '100%';
                break;
            case 'mobile':
                container.style.maxWidth = '375px';
                container.style.width = '100%';
                break;
        }
    }
</script>
@endpush

@push('styles')
<style>
    #preview-container {
        transition: all 0.3s ease;
        min-height: 300px;
    }
</style>
@endpush
@endsection