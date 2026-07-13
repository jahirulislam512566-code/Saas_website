{{-- resources/views/admin/integrations/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Integrations')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Integrations</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Integrations</h1>
            <p class="text-sm text-gray-500 mt-1">Connect and manage third-party services</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="refreshIntegrations()" 
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-sync mr-2"></i> Refresh
            </button>
            <a href="{{ route('admin.integrations.webhook') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-webhook mr-2"></i> Webhooks
            </a>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Integrations</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-plug"></i>
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
                    <p class="text-sm text-gray-500">Connected</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['connected'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-link"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Webhooks</p>
                    <p class="text-xl font-bold text-purple-600">{{ $stats['webhooks'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-webhook"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== INTEGRATIONS GRID ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Stripe -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-2xl font-bold">
                            S
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Stripe</h3>
                            <p class="text-xs text-gray-500">Payment Processing</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $integrations['stripe']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $integrations['stripe']['enabled'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $integrations['stripe']['enabled'] ? 'Connected' : 'Disconnected' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Mode</span>
                    <span class="text-gray-900">{{ ucfirst($integrations['stripe']['mode'] ?? 'live') }}</span>
                </div>
                <button onclick="configureIntegration('stripe')" 
                        class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-2"></i> Configure
                </button>
            </div>
        </div>

        <!-- PayPal -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-2xl font-bold">
                            P
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">PayPal</h3>
                            <p class="text-xs text-gray-500">Payment Processing</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $integrations['paypal']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $integrations['paypal']['enabled'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $integrations['paypal']['enabled'] ? 'Connected' : 'Disconnected' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Mode</span>
                    <span class="text-gray-900">{{ ucfirst($integrations['paypal']['mode'] ?? 'live') }}</span>
                </div>
                <button onclick="configureIntegration('paypal')" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-2"></i> Configure
                </button>
            </div>
        </div>

        <!-- Google -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-2xl font-bold">
                            G
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Google</h3>
                            <p class="text-xs text-gray-500">Analytics & Auth</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $integrations['google']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $integrations['google']['enabled'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $integrations['google']['enabled'] ? 'Connected' : 'Disconnected' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Services</span>
                    <span class="text-gray-900">Analytics, Auth</span>
                </div>
                <button onclick="configureIntegration('google')" 
                        class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-2"></i> Configure
                </button>
            </div>
        </div>

        <!-- Cloudinary -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-sky-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center text-2xl font-bold">
                            C
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Cloudinary</h3>
                            <p class="text-xs text-gray-500">Media Management</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $integrations['cloudinary']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $integrations['cloudinary']['enabled'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $integrations['cloudinary']['enabled'] ? 'Connected' : 'Disconnected' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Storage</span>
                    <span class="text-gray-900">{{ $integrations['cloudinary']['storage'] ?? 'N/A' }}</span>
                </div>
                <button onclick="configureIntegration('cloudinary')" 
                        class="w-full px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-2"></i> Configure
                </button>
            </div>
        </div>

        <!-- Mail -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-emerald-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                            M
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Mail</h3>
                            <p class="text-xs text-gray-500">Email Service</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $integrations['mail']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $integrations['mail']['enabled'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $integrations['mail']['enabled'] ? 'Connected' : 'Disconnected' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Driver</span>
                    <span class="text-gray-900">{{ $integrations['mail']['driver'] ?? 'smtp' }}</span>
                </div>
                <button onclick="configureIntegration('mail')" 
                        class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-2"></i> Configure
                </button>
            </div>
        </div>

        <!-- Webhooks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl font-bold">
                            W
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Webhooks</h3>
                            <p class="text-xs text-gray-500">Real-time Events</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $integrations['webhook']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $integrations['webhook']['enabled'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $integrations['webhook']['enabled'] ? 'Active' : 'Inactive' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Endpoints</span>
                    <span class="text-gray-900">{{ $integrations['webhook']['endpoints'] ?? 0 }}</span>
                </div>
                <a href="{{ route('admin.integrations.webhook') }}" 
                   class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                    <i class="fas fa-webhook mr-2"></i> Manage Webhooks
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ===== CONFIGURE MODAL ===== -->
<div x-data="{ show: false, integration: null }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="integration-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="integration-modal-title">Configure Integration</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Status
                                    </label>
                                    <select name="enabled" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Mode
                                    </label>
                                    <select name="mode" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="live">Live</option>
                                        <option value="test">Test</option>
                                    </select>
                                </div>
                                <div id="integration-fields">
                                    <!-- Dynamic fields will be loaded here -->
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="test_connection" value="1" checked
                                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Test connection on save</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Configuration
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function configureIntegration(integration) {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.show = true;
        modal.integration = integration;
        document.getElementById('integration-modal-title').textContent = `Configure ${integration.charAt(0).toUpperCase() + integration.slice(1)}`;
        document.getElementById('integration-form').action = `/admin/integrations/${integration}`;
        
        // Load configuration
        fetch(`/admin/integrations/${integration}/config`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('select[name="enabled"]').value = data.data.enabled ? 1 : 0;
                    document.querySelector('select[name="mode"]').value = data.data.mode || 'live';
                    
                    // Load dynamic fields
                    loadIntegrationFields(integration, data.data);
                }
            });
    }

    function loadIntegrationFields(integration, data) {
        const container = document.getElementById('integration-fields');
        let fields = '';

        switch(integration) {
            case 'stripe':
                fields = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Publishable Key</label>
                        <input type="text" name="publishable_key" value="${data.publishable_key || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Secret Key</label>
                        <input type="password" name="secret_key" value="${data.secret_key || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Webhook Secret</label>
                        <input type="text" name="webhook_secret" value="${data.webhook_secret || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                `;
                break;

            case 'paypal':
                fields = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client ID</label>
                        <input type="text" name="client_id" value="${data.client_id || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client Secret</label>
                        <input type="password" name="client_secret" value="${data.client_secret || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                `;
                break;

            case 'google':
                fields = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client ID</label>
                        <input type="text" name="client_id" value="${data.client_id || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client Secret</label>
                        <input type="password" name="client_secret" value="${data.client_secret || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                        <input type="text" name="api_key" value="${data.api_key || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                `;
                break;

            case 'cloudinary':
                fields = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cloud Name</label>
                        <input type="text" name="cloud_name" value="${data.cloud_name || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                        <input type="text" name="api_key" value="${data.api_key || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">API Secret</label>
                        <input type="password" name="api_secret" value="${data.api_secret || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                `;
                break;

            case 'mail':
                fields = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mail Driver</label>
                        <select name="driver" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="smtp" ${data.driver === 'smtp' ? 'selected' : ''}>SMTP</option>
                            <option value="mailgun" ${data.driver === 'mailgun' ? 'selected' : ''}>Mailgun</option>
                            <option value="postmark" ${data.driver === 'postmark' ? 'selected' : ''}>Postmark</option>
                            <option value="ses" ${data.driver === 'ses' ? 'selected' : ''}>SES</option>
                            <option value="sendmail" ${data.driver === 'sendmail' ? 'selected' : ''}>Sendmail</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                        <input type="text" name="host" value="${data.host || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                        <input type="number" name="port" value="${data.port || '587'}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" value="${data.username || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" value="${data.password || ''}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                        <select name="encryption" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="tls" ${data.encryption === 'tls' ? 'selected' : ''}>TLS</option>
                            <option value="ssl" ${data.encryption === 'ssl' ? 'selected' : ''}>SSL</option>
                            <option value="" ${!data.encryption ? 'selected' : ''}>None</option>
                        </select>
                    </div>
                `;
                break;
        }

        container.innerHTML = fields;
    }

    function refreshIntegrations() {
        window.location.reload();
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