{{-- resources/views/admin/seo/redirects.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Redirects Management')

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
            <span class="text-gray-500">Redirects</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Redirects Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage URL redirects for SEO optimization</p>
        </div>
        <button onclick="showAddRedirect()" 
                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Add Redirect
        </button>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Redirects</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Permanent (301)</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['permanent'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Temporary (302)</p>
            <p class="text-xl font-bold text-yellow-600">{{ $stats['temporary'] ?? 0 }}</p>
        </div>
    </div>

    <!-- ===== REDIRECTS TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Target URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hits</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($redirects as $redirect)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $redirect->source }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $redirect->target }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $redirect->type == '301' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $redirect->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $redirect->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $redirect->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $redirect->hits ?? 0 }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <button onclick="editRedirect('{{ $redirect->id }}')" 
                                            class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                            title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </button>
                                    <button onclick="toggleRedirect('{{ $redirect->id }}')" 
                                            class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" 
                                            title="{{ $redirect->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $redirect->is_active ? 'fa-pause' : 'fa-play' }} text-sm"></i>
                                    </button>
                                    <button onclick="deleteRedirect('{{ $redirect->id }}')" 
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
                                <i class="fas fa-arrow-right text-3xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No redirects configured</p>
                                <p class="text-sm mt-1">Create a redirect to manage URL changes</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $redirects->links() }}
        </div>
    </div>
</div>

<!-- Add/Edit Redirect Modal -->
<div x-data="{ show: false, editing: false, redirectId: null }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="redirect-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="redirect-modal-title">Add Redirect</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Source URL <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="source" id="redirect-source" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="/old-page">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Target URL <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="target" id="redirect-target" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="/new-page">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Redirect Type <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" id="redirect-type" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="301">301 - Permanent</option>
                                        <option value="302">302 - Temporary</option>
                                    </select>
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
                        Save Redirect
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toggle Form -->
<form id="toggle-redirect-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<!-- Delete Form -->
<form id="delete-redirect-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function showAddRedirect() {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.show = true;
        modal.editing = false;
        modal.redirectId = null;
        document.getElementById('redirect-modal-title').textContent = 'Add Redirect';
        document.getElementById('redirect-form').action = '{{ route("admin.seo.redirects.store") }}';
        document.getElementById('redirect-form').querySelector('input[name="_method"]')?.remove();
        document.getElementById('redirect-source').value = '';
        document.getElementById('redirect-target').value = '';
        document.getElementById('redirect-type').value = '301';
        document.querySelector('#redirect-form input[name="is_active"]').checked = true;
    }

    function editRedirect(redirectId) {
        fetch(`/admin/seo/redirects/${redirectId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.querySelector('[x-data]').__x.$data;
                    modal.show = true;
                    modal.editing = true;
                    modal.redirectId = redirectId;
                    document.getElementById('redirect-modal-title').textContent = 'Edit Redirect';
                    document.getElementById('redirect-form').action = `/admin/seo/redirects/${redirectId}`;
                    
                    let methodInput = document.getElementById('redirect-form').querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        document.getElementById('redirect-form').appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    
                    document.getElementById('redirect-source').value = data.data.source;
                    document.getElementById('redirect-target').value = data.data.target;
                    document.getElementById('redirect-type').value = data.data.type;
                    document.querySelector('#redirect-form input[name="is_active"]').checked = data.data.is_active;
                }
            });
    }

    function toggleRedirect(redirectId) {
        const form = document.getElementById('toggle-redirect-form');
        form.action = `/admin/seo/redirects/${redirectId}/toggle`;
        form.submit();
    }

    function deleteRedirect(redirectId) {
        if (confirm('Delete this redirect?')) {
            const form = document.getElementById('delete-redirect-form');
            form.action = `/admin/seo/redirects/${redirectId}`;
            form.submit();
        }
    }
</script>
@endpush
@endsection