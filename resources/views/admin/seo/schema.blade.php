{{-- resources/views/admin/seo/schema.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Schema Markup')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.seo.dashboard') }}" class="text-gray-500 hover:text-gray-700">SEO</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Schema</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Schema Markup</h1>
            <p class="text-sm text-gray-500 mt-1">Manage structured data for better SEO</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="validateSchema()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-check-circle mr-2"></i> Validate
            </button>
            <button onclick="showAddSchema()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Schema
            </button>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Schemas</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Active</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Types</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['types'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Pages</p>
            <p class="text-xl font-bold text-purple-600">{{ $stats['pages'] ?? 0 }}</p>
        </div>
    </div>

    <!-- ===== SCHEMA LIST ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($schemas as $schema)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                                <i class="fas {{ $schema->icon ?? 'fa-code' }}"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">{{ $schema->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $schema->type }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $schema->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $schema->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    <p class="text-sm text-gray-600 line-clamp-2">{{ $schema->description ?? 'No description' }}</p>
                    <div class="mt-3 flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                            <span><i class="fas fa-file mr-1"></i>{{ $schema->pages ?? 0 }} pages</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <button onclick="previewSchema('{{ $schema->id }}')" 
                                    class="p-1.5 text-gray-400 hover:text-green-600 transition-colors rounded-lg hover:bg-green-50" 
                                    title="Preview">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            <button onclick="editSchema('{{ $schema->id }}')" 
                                    class="p-1.5 text-gray-400 hover:text-primary-600 transition-colors rounded-lg hover:bg-primary-50" 
                                    title="Edit">
                                <i class="fas fa-pen text-sm"></i>
                            </button>
                            <button onclick="deleteSchema('{{ $schema->id }}', '{{ $schema->name }}')" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50" 
                                    title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-code text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No schemas found</p>
                    <p class="text-sm text-gray-500 mt-1">Add structured data to improve your SEO</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Add/Edit Schema Modal -->
<div x-data="{ show: false, editing: false, schemaId: null }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form id="schema-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="schema-modal-title">Add Schema</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Schema Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="schema-name" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="e.g., Article Schema">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Type <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" id="schema-type" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="Article">Article</option>
                                        <option value="BlogPosting">Blog Post</option>
                                        <option value="Product">Product</option>
                                        <option value="Service">Service</option>
                                        <option value="FAQ">FAQ</option>
                                        <option value="HowTo">HowTo</option>
                                        <option value="Person">Person</option>
                                        <option value="Organization">Organization</option>
                                        <option value="LocalBusiness">Local Business</option>
                                        <option value="Review">Review</option>
                                        <option value="Event">Event</option>
                                        <option value="Recipe">Recipe</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Description
                                    </label>
                                    <textarea name="description" id="schema-description" rows="2"
                                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                              placeholder="Brief description"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Schema JSON-LD
                                    </label>
                                    <textarea name="schema_data" id="schema-data" rows="8"
                                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 font-mono text-sm"
                                              placeholder='{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "{{ $page->title ?? "Sample Article" }}",
    "description": "{{ $page->excerpt ?? "Article description" }}"
}'></textarea>
                                    <p class="mt-1 text-xs text-gray-500">JSON-LD structured data</p>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_active" value="1" checked
                                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Active</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Schema
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-schema-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function showAddSchema() {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.show = true;
        modal.editing = false;
        modal.schemaId = null;
        document.getElementById('schema-modal-title').textContent = 'Add Schema';
        document.getElementById('schema-form').action = '{{ route("admin.seo.schema.store") }}';
        document.getElementById('schema-form').querySelector('input[name="_method"]')?.remove();
        document.getElementById('schema-name').value = '';
        document.getElementById('schema-type').value = 'Article';
        document.getElementById('schema-description').value = '';
        document.getElementById('schema-data').value = '';
        document.querySelector('#schema-form input[name="is_active"]').checked = true;
    }

    function editSchema(schemaId) {
        fetch(`/admin/seo/schemas/${schemaId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.querySelector('[x-data]').__x.$data;
                    modal.show = true;
                    modal.editing = true;
                    modal.schemaId = schemaId;
                    document.getElementById('schema-modal-title').textContent = 'Edit Schema';
                    document.getElementById('schema-form').action = `/admin/seo/schemas/${schemaId}`;
                    
                    let methodInput = document.getElementById('schema-form').querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        document.getElementById('schema-form').appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    
                    document.getElementById('schema-name').value = data.data.name;
                    document.getElementById('schema-type').value = data.data.type;
                    document.getElementById('schema-description').value = data.data.description || '';
                    document.getElementById('schema-data').value = JSON.stringify(data.data.schema_data, null, 2);
                    document.querySelector('#schema-form input[name="is_active"]').checked = data.data.is_active;
                }
            });
    }

    function deleteSchema(schemaId, schemaName) {
        if (confirm(`Delete schema "${schemaName}"?`)) {
            const form = document.getElementById('delete-schema-form');
            form.action = `/admin/seo/schemas/${schemaId}`;
            form.submit();
        }
    }

    function previewSchema(schemaId) {
        window.open(`/admin/seo/schemas/${schemaId}/preview`, '_blank');
    }

    function validateSchema() {
        // Validate all schemas
        fetch('{{ route("admin.seo.schema.validate") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  showToast('All schemas are valid!', 'success');
              } else {
                  showToast('Some schemas have errors: ' + data.message, 'error');
              }
          });
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } shadow-lg z-50`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
</script>
@endpush
@endsection