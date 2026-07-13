{{-- resources/views/admin/notifications/emails/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Email Templates')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.notifications.index') }}" class="text-gray-500 hover:text-gray-700">Notifications</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Email Templates</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Email Templates</h1>
            <p class="text-sm text-gray-500 mt-1">Manage email notification templates</p>
        </div>
        <button onclick="showCreateTemplate()" 
                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Create Template
        </button>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Templates</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Active</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Default</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['default'] ?? 0 }}</p>
        </div>
    </div>

    <!-- ===== TEMPLATES TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Template</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Used</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($templates as $template)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $template->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $template->slug }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $template->subject }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $template->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($template->is_default)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-1">
                                        Default
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $template->used_count ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $template->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <button onclick="previewTemplate('{{ $template->id }}')" 
                                            class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                            title="Preview">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                    <button onclick="editTemplate('{{ $template->id }}')" 
                                            class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                            title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </button>
                                    <button onclick="duplicateTemplate('{{ $template->id }}')" 
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                            title="Duplicate">
                                        <i class="fas fa-copy text-sm"></i>
                                    </button>
                                    <button onclick="deleteTemplate('{{ $template->id }}', '{{ $template->name }}')" 
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-envelope text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No email templates found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $templates->links() }}
        </div>
    </div>
</div>

<!-- ===== CREATE/EDIT TEMPLATE MODAL ===== -->
<div x-data="{ show: false, editing: false, templateId: null }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form id="template-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="template-modal-title">Create Email Template</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Template Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="template-name" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="e.g., Welcome Email">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Subject <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="subject" id="template-subject" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="Welcome to {{ config('app.name') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Content <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="content" id="template-content" rows="6" required
                                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 font-mono text-sm"
                                              placeholder="Email content with HTML..."></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Variables
                                        </label>
                                        <div class="space-y-1 text-sm text-gray-500">
                                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ '{{' }} name }}</code>
                                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ '{{' }} email }}</code>
                                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ '{{' }} site_name }}</code>
                                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ '{{' }} year }}</code>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Status
                                        </label>
                                        <div class="space-y-2">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="is_active" value="1" checked
                                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                                <span class="ml-2 text-sm text-gray-700">Active</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" name="is_default" value="1"
                                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                                <span class="ml-2 text-sm text-gray-700">Default Template</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Template
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== PREVIEW MODAL ===== -->
<div x-data="{ show: false, content: '' }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Email Preview</h3>
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div id="preview-content" class="prose max-w-none"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="show = false" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="duplicate-template-form" method="POST" style="display: none;">
    @csrf
</form>

<form id="delete-template-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function showCreateTemplate() {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.show = true;
        modal.editing = false;
        modal.templateId = null;
        document.getElementById('template-modal-title').textContent = 'Create Email Template';
        document.getElementById('template-form').action = '{{ route("admin.notifications.emails.store") }}';
        document.getElementById('template-form').querySelector('input[name="_method"]')?.remove();
        document.getElementById('template-name').value = '';
        document.getElementById('template-subject').value = '';
        document.getElementById('template-content').value = '';
        document.querySelector('#template-form input[name="is_active"]').checked = true;
        document.querySelector('#template-form input[name="is_default"]').checked = false;
    }

    function editTemplate(id) {
        fetch(`/admin/notifications/emails/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.querySelector('[x-data]').__x.$data;
                    modal.show = true;
                    modal.editing = true;
                    modal.templateId = id;
                    document.getElementById('template-modal-title').textContent = 'Edit Email Template';
                    document.getElementById('template-form').action = `/admin/notifications/emails/${id}`;
                    
                    let methodInput = document.getElementById('template-form').querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        document.getElementById('template-form').appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    
                    document.getElementById('template-name').value = data.data.name;
                    document.getElementById('template-subject').value = data.data.subject;
                    document.getElementById('template-content').value = data.data.content;
                    document.querySelector('#template-form input[name="is_active"]').checked = data.data.is_active;
                    document.querySelector('#template-form input[name="is_default"]').checked = data.data.is_default;
                }
            });
    }

    function previewTemplate(id) {
        fetch(`/admin/notifications/emails/${id}/preview`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.querySelectorAll('[x-data]')[1].__x.$data;
                    modal.show = true;
                    document.getElementById('preview-content').innerHTML = data.data.content;
                }
            });
    }

    function duplicateTemplate(id) {
        if (confirm('Duplicate this template?')) {
            const form = document.getElementById('duplicate-template-form');
            form.action = `/admin/notifications/emails/${id}/duplicate`;
            form.submit();
        }
    }

    function deleteTemplate(id, name) {
        if (confirm(`Delete template "${name}"?`)) {
            const form = document.getElementById('delete-template-form');
            form.action = `/admin/notifications/emails/${id}`;
            form.submit();
        }
    }
</script>
@endpush
@endsection