{{-- resources/views/admin/pages/sections/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $page->title . ' - Sections')

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
            <span class="text-gray-700">{{ $page->title }}</span>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Sections</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $page->title }} - Sections</h1>
            <p class="text-sm text-gray-500 mt-1">Manage page sections and layout</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="showAddSection()" 
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Section
            </button>
            <a href="{{ route('admin.pages.edit', $page) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Sections List -->
    <div class="space-y-4" id="sections-container">
        @forelse($sections as $section)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" data-id="{{ $section->id }}">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                                <i class="fas {{ $section->icon }}"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">{{ $section->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $section->type_label }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $section->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <div class="flex items-center space-x-1">
                                <button onclick="editSection('{{ $section->id }}')" 
                                        class="p-1.5 text-gray-400 hover:text-primary-600 transition-colors rounded-lg hover:bg-primary-50" 
                                        title="Edit">
                                    <i class="fas fa-pen text-sm"></i>
                                </button>
                                <button onclick="deleteSection('{{ $section->id }}', '{{ $section->name }}')" 
                                        class="p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50" 
                                        title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    <!-- Section Components -->
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-500">Components</span>
                        <button onclick="showAddComponent('{{ $section->id }}')" 
                                class="text-xs text-primary-600 hover:text-primary-700 transition-colors">
                            <i class="fas fa-plus mr-1"></i> Add Component
                        </button>
                    </div>
                    
                    <div class="space-y-2" id="components-{{ $section->id }}">
                        @forelse($section->components as $component)
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <i class="fas {{ $component->icon }} text-gray-400"></i>
                                    <div>
                                        <p class="text-sm text-gray-700">{{ $component->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $component->type_label }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <button onclick="editComponent('{{ $component->id }}')" 
                                            class="p-1 text-gray-400 hover:text-primary-600 transition-colors">
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>
                                    <button onclick="deleteComponent('{{ $component->id }}', '{{ $component->name }}')" 
                                            class="p-1 text-gray-400 hover:text-red-600 transition-colors">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-2">No components yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                <i class="fas fa-layer-group text-4xl text-gray-300 mb-3 block"></i>
                <p class="text-lg font-medium text-gray-900">No sections found</p>
                <p class="text-sm text-gray-500 mt-1">Add sections to build your page</p>
            </div>
        @endforelse
    </div>
</div>

@include('admin.pages.sections.modals')
@include('admin.pages.components.modals')

@push('scripts')
<script>
    // Section functions
    function showAddSection() {
        const modal = document.getElementById('sectionModal');
        modal.querySelector('.modal-title').textContent = 'Add Section';
        modal.querySelector('form').action = '{{ route("admin.pages.sections.store", $page) }}';
        modal.querySelector('input[name="name"]').value = '';
        modal.querySelector('select[name="type"]').value = 'content';
        modal.querySelector('textarea[name="content"]').value = '';
        modal.querySelector('input[name="is_active"]').checked = true;
        modal.style.display = 'block';
    }

    function editSection(sectionId) {
        fetch(`/admin/pages/sections/${sectionId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.getElementById('sectionModal');
                    modal.querySelector('.modal-title').textContent = 'Edit Section';
                    modal.querySelector('form').action = `/admin/pages/sections/${sectionId}`;
                    modal.querySelector('input[name="_method"]').value = 'PUT';
                    modal.querySelector('input[name="name"]').value = data.data.name;
                    modal.querySelector('select[name="type"]').value = data.data.type;
                    modal.querySelector('textarea[name="content"]').value = JSON.stringify(data.data.content, null, 2);
                    modal.querySelector('input[name="is_active"]').checked = data.data.is_active;
                    modal.style.display = 'block';
                }
            });
    }

    function deleteSection(sectionId, sectionName) {
        if (confirm(`Delete section "${sectionName}"?`)) {
            document.getElementById('section-delete-form').action = `/admin/pages/sections/${sectionId}`;
            document.getElementById('section-delete-form').submit();
        }
    }

    // Component functions
    function showAddComponent(sectionId) {
        const modal = document.getElementById('componentModal');
        modal.querySelector('.modal-title').textContent = 'Add Component';
        modal.querySelector('form').action = '/admin/pages/components';
        modal.querySelector('input[name="section_id"]').value = sectionId;
        modal.querySelector('input[name="name"]').value = '';
        modal.querySelector('select[name="type"]').value = 'text';
        modal.querySelector('textarea[name="content"]').value = '';
        modal.querySelector('input[name="is_active"]').checked = true;
        modal.style.display = 'block';
    }

    function editComponent(componentId) {
        fetch(`/admin/pages/components/${componentId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.getElementById('componentModal');
                    modal.querySelector('.modal-title').textContent = 'Edit Component';
                    modal.querySelector('form').action = `/admin/pages/components/${componentId}`;
                    modal.querySelector('input[name="_method"]').value = 'PUT';
                    modal.querySelector('input[name="section_id"]').value = data.data.section_id;
                    modal.querySelector('input[name="name"]').value = data.data.name;
                    modal.querySelector('select[name="type"]').value = data.data.type;
                    modal.querySelector('textarea[name="content"]').value = JSON.stringify(data.data.content, null, 2);
                    modal.querySelector('input[name="is_active"]').checked = data.data.is_active;
                    modal.style.display = 'block';
                }
            });
    }

    function deleteComponent(componentId, componentName) {
        if (confirm(`Delete component "${componentName}"?`)) {
            document.getElementById('component-delete-form').action = `/admin/pages/components/${componentId}`;
            document.getElementById('component-delete-form').submit();
        }
    }
</script>
@endpush
@endsection