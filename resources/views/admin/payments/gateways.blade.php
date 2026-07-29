<!-- admin/payments/gateways.blade.php -->
@extends('layout.app')

@section('title', 'Payment Gateways')
@section('page-title', 'Payment Gateways')
@section('page-subtitle', 'Configure and manage payment gateway integrations')

@section('content')
<div x-data="gatewayManager()" x-init="init()">
    <!-- Gateway Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Stripe -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                            <i class="fab fa-stripe text-2xl text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Stripe</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Credit card & digital payments</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                          :class="gateways.stripe.enabled ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'"
                          x-text="gateways.stripe.enabled ? 'Enabled' : 'Disabled'">
                    </span>
                </div>
                <div class="mt-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Supports all major cards</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Instant settlements</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <span class="text-sm text-gray-500 dark:text-gray-400" x-text="'Connected: ' + (gateways.stripe.connected ? 'Yes' : 'No')"></span>
                    <button @click="configureGateway('stripe')" 
                            class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        Configure <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- PayPal -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                            <i class="fab fa-paypal text-2xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">PayPal</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Global payment processing</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                          :class="gateways.paypal.enabled ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'"
                          x-text="gateways.paypal.enabled ? 'Enabled' : 'Disabled'">
                    </span>
                </div>
                <div class="mt-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Supports PayPal wallet</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Buyer protection included</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <span class="text-sm text-gray-500 dark:text-gray-400" x-text="'Connected: ' + (gateways.paypal.connected ? 'Yes' : 'No')"></span>
                    <button @click="configureGateway('paypal')" 
                            class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        Configure <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- SSLCommerz -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                            <i class="fas fa-shield-alt text-2xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">SSLCommerz</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Local & international payments</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                          :class="gateways.sslcommerz.enabled ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'"
                          x-text="gateways.sslcommerz.enabled ? 'Enabled' : 'Disabled'">
                    </span>
                </div>
                <div class="mt-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Local bank integrations</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Mobile wallet support</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <span class="text-sm text-gray-500 dark:text-gray-400" x-text="'Connected: ' + (gateways.sslcommerz.connected ? 'Yes' : 'No')"></span>
                    <button @click="configureGateway('sslcommerz')" 
                            class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        Configure <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Manual -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                            <i class="fas fa-hand text-2xl text-gray-600 dark:text-gray-400"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Manual Payments</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Bank transfer & offline payments</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                          :class="gateways.manual.enabled ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'"
                          x-text="gateways.manual.enabled ? 'Enabled' : 'Disabled'">
                    </span>
                </div>
                <div class="mt-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Bank transfer support</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Manual verification</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <span class="text-sm text-gray-500 dark:text-gray-400" x-text="'Connected: ' + (gateways.manual.connected ? 'Yes' : 'No')"></span>
                    <button @click="configureGateway('manual')" 
                            class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        Configure <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Gateway Configuration Modal -->
    <div x-show="showConfigModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.away="showConfigModal = false">
        
        <div x-show="showConfigModal"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="'Configure ' + selectedGateway?.name"></h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="selectedGateway?.description"></p>
                </div>
                <button @click="showConfigModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <form @submit.prevent="saveGatewayConfig()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Enabled</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="config.enabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div x-show="config.enabled">
                        <template x-for="(field, key) in selectedGateway?.fields || {}" :key="key">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 capitalize" x-text="key.replace('_', ' ')"></label>
                                <input :type="field.type || 'text'" 
                                       x-model="config.fields[key]" 
                                       :placeholder="field.placeholder || ''"
                                       class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-text="field.help || ''"></p>
                            </div>
                        </template>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                        <button type="button" @click="showConfigModal = false" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="button" @click="testConnection()" 
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm transition-all">
                            <i class="fas fa-play mr-1.5"></i>Test Connection
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-all">
                            <i class="fas fa-save mr-1.5"></i>Save Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Webhook Management -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden mt-6">
        <div class="p-6">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Webhook Management</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Configure webhook endpoints for payment notifications</p>
            
            <div class="space-y-3">
                <template x-for="webhook in webhooks" :key="webhook.id">
                    <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white" x-text="webhook.event"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="webhook.url"></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs px-2 py-0.5 rounded-full"
                                  :class="webhook.status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'"
                                  x-text="webhook.status"></span>
                            <button @click="editWebhook(webhook)" 
                                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                Edit
                            </button>
                        </div>
                    </div>
                </template>
                
                <button @click="addWebhook()" 
                        class="w-full p-3 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:border-indigo-500 dark:hover:border-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    <i class="fas fa-plus mr-1.5"></i>Add Webhook Endpoint
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function gatewayManager() {
    return {
        gateways: @json($gateways),
        webhooks: @json($webhooks),
        selectedGateway: null,
        config: {
            enabled: false,
            fields: {}
        },
        showConfigModal: false,
        
        configureGateway(gatewayKey) {
            this.selectedGateway = {
                key: gatewayKey,
                name: this.gatewayNames[gatewayKey],
                description: this.gatewayDescriptions[gatewayKey],
                fields: this.getGatewayFields(gatewayKey)
            };
            this.config = {
                enabled: this.gateways[gatewayKey].enabled,
                fields: this.gateways[gatewayKey].config || {}
            };
            this.showConfigModal = true;
        },
        
        saveGatewayConfig() {
            fetch('{{ route("admin.payments.gateways.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    gateway: this.selectedGateway.key,
                    config: this.config
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showConfigModal = false;
                    location.reload();
                }
            })
            .catch(error => console.error('Error saving gateway config:', error));
        },
        
        testConnection() {
            fetch('{{ route("admin.payments.gateways.test") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    gateway: this.selectedGateway.key,
                    config: this.config
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Connection successful!');
                } else {
                    alert('Connection failed: ' + data.error);
                }
            })
            .catch(error => console.error('Error testing connection:', error));
        },
        
        editWebhook(webhook) {
            // Implement webhook editing
            alert('Edit webhook: ' + webhook.event);
        },
        
        addWebhook() {
            // Implement adding webhook
            alert('Add new webhook endpoint');
        },
        
        gatewayNames: {
            stripe: 'Stripe',
            paypal: 'PayPal',
            sslcommerz: 'SSLCommerz',
            manual: 'Manual Payments'
        },
        
        gatewayDescriptions: {
            stripe: 'Credit card and digital payments',
            paypal: 'Global payment processing',
            sslcommerz: 'Local and international payments',
            manual: 'Bank transfer and offline payments'
        },
        
        getGatewayFields(gateway) {
            const fields = {
                stripe: {
                    publishable_key: {
                        type: 'text',
                        placeholder: 'pk_test_...',
                        help: 'Your Stripe publishable key'
                    },
                    secret_key: {
                        type: 'password',
                        placeholder: 'sk_test_...',
                        help: 'Your Stripe secret key'
                    },
                    webhook_secret: {
                        type: 'password',
                        placeholder: 'whsec_...',
                        help: 'Your Stripe webhook signing secret'
                    }
                },
                paypal: {
                    client_id: {
                        type: 'text',
                        placeholder: 'your-client-id',
                        help: 'Your PayPal Client ID'
                    },
                    client_secret: {
                        type: 'password',
                        placeholder: 'your-client-secret',
                        help: 'Your PayPal Client Secret'
                    },
                    mode: {
                        type: 'select',
                        options: ['sandbox', 'live'],
                        help: 'Payment environment'
                    }
                },
                sslcommerz: {
                    store_id: {
                        type: 'text',
                        placeholder: 'your-store-id',
                        help: 'Your SSLCommerz Store ID'
                    },
                    store_password: {
                        type: 'password',
                        placeholder: 'your-store-password',
                        help: 'Your SSLCommerz Store Password'
                    },
                    mode: {
                        type: 'select',
                        options: ['test', 'live'],
                        help: 'Payment environment'
                    }
                },
                manual: {
                    bank_name: {
                        type: 'text',
                        placeholder: 'Bank Name',
                        help: 'Name of the bank for manual transfers'
                    },
                    account_name: {
                        type: 'text',
                        placeholder: 'Account Holder Name',
                        help: 'Name on the bank account'
                    },
                    account_number: {
                        type: 'text',
                        placeholder: 'Account Number',
                        help: 'Bank account number'
                    },
                    routing_number: {
                        type: 'text',
                        placeholder: 'Routing Number',
                        help: 'Bank routing number'
                    },
                    instructions: {
                        type: 'textarea',
                        placeholder: 'Payment instructions...',
                        help: 'Instructions for customers making manual payments'
                    }
                }
            };
            return fields[gateway] || {};
        }
    }
}
</script>
@endpush