{{-- resources/views/admin/integrations/webhooks.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Webhooks')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.integrations.index') }}" class="text-gray-500 hover:text-gray-700">Integrations</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Webhooks</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Webhooks</h1>
            <p class="text-sm text-gray-500 mt-1">Manage webhook endpoints and events</p>
        </div>
        <button onclick="showCreateWebhook()" 
                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Create Webhook
        </button>
    </div>

    <!-- ===== WEBHOOKS TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Webhook</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Events</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($webhooks as $webhook)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $webhook->name }}</p>
                                <p class="text-xs text-gray-500">ID: {{ $webhook->id }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($webhook->url, 40) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($webhook->events, 0, 3) as $event)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $event }}
                                        </span>
                                    @endforeach
                                    @if(count($webhook->events) > 3)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            +{{ count($webhook->events) - 3 }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $webhook->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $webhook->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $webhook->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <button onclick="testWebhook('{{ $webhook->id }}')" 
                                            class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                            title="Test">
                                        <i class="fas fa-vial text-sm"></i>
                                    </button>
                                    <button onclick="viewWebhookLogs('{{ $webhook->id }}')" 
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                            title="Logs">
                                        <i class="fas fa-history text-sm"></i>
                                    </button>
                                    <button onclick="editWebhook('{{ $webhook->id }}')" 
                                            class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                            title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </button>
                                    <button onclick="toggleWebhook('{{ $webhook->id }}')" 
                                            class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" 
                                            title="{{ $webhook->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $webhook->is_active ? 'fa-pause' : 'fa-play' }} text-sm"></i>
                                    </button>
                                    <button onclick="deleteWebhook('{{ $webhook->id }}', '{{ $webhook->name }}')" 
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
                                <i class="fas fa-webhook text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No webhooks configured</p>
                                <p class="text-sm mt-1">Create your first webhook endpoint</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $webhooks->links() }}
        </div>
    </div>
</div>

<!-- ===== CREATE/EDIT WEBHOOK MODAL ===== -->
<div x-data="{ show: false, editing: false, webhookId: null }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="webhook-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="webhook-modal-title">Create Webhook</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="webhook-name" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="e.g., Slack Notifications">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        URL <span class="text-red-500">*</span>
                                    </label>
                                    <input type="url" name="url" id="webhook-url" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="https://example.com/webhook">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Events <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="events[]" value="user.created" checked
                                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm">User Created</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="events[]" value="user.updated"
                                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm">User Updated</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="events[]" value="subscription.created"
                                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm">Subscription Created</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="events[]" value="subscription.updated"
                                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm">Subscription Updated</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="events[]" value="payment.success"
                                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm">Payment Success</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="events[]" value="payment.failed"
                                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm">Payment Failed</span>
                                        </label>
                                    </div>
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
                        Save Webhook
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="toggle-webhook-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="delete-webhook-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function showCreateWebhook() {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.show = true;
        modal.editing = false;
        modal.webhookId = null;
        document.getElementById('webhook-modal-title').textContent = 'Create Webhook';
        document.getElementById('webhook-form').action = '{{ route("admin.integrations.webhook.store") }}';
        document.getElementById('webhook-form').querySelector('input[name="_method"]')?.remove();
        document.getElementById('webhook-name').value = '';
        document.getElementById('webhook-url').value = '';
        document.querySelector('#webhook-form input[name="is_active"]').checked = true;
    }

    function editWebhook(id) {
        fetch(`/admin/integrations/webhooks/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.querySelector('[x-data]').__x.$data;
                    modal.show = true;
                    modal.editing = true;
                    modal.webhookId = id;
                    document.getElementById('webhook-modal-title').textContent = 'Edit Webhook';
                    document.getElementById('webhook-form').action = `/admin/integrations/webhooks/${id}`;
                    
                    let methodInput = document.getElementById('webhook-form').querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        document.getElementById('webhook-form').appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    
                    document.getElementById('webhook-name').value = data.data.name;
                    document.getElementById('webhook-url').value = data.data.url;
                    data.data.events.forEach(event => {
                        document.querySelector(`input[name="events[]"][value="${event}"]`).checked = true;
                    });
                    document.querySelector('#webhook-form input[name="is_active"]').checked = data.data.is_active;
                }
            });
    }

    function testWebhook(id) {
        if (confirm('Test this webhook?')) {
            fetch(`/admin/integrations/webhooks/${id}/test`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Webhook test successful!');
                } else {
                    alert('Webhook test failed: ' + data.message);
                }
            });
        }
    }

    function toggleWebhook(id) {
        const form = document.getElementById('toggle-webhook-form');
        form.action = `/admin/integrations/webhooks/${id}/toggle`;
        form.submit();
    }

    function deleteWebhook(id, name) {
        if (confirm(`Delete webhook "${name}"?`)) {
            const form = document.getElementById('delete-webhook-form');
            form.action = `/admin/integrations/webhooks/${id}`;
            form.submit();
        }
    }

    function viewWebhookLogs(id) {
        window.location.href = `/admin/integrations/webhooks/${id}/logs`;
    }
</script>
@endpush
@endsection