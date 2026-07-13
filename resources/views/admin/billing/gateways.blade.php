{{-- resources/views/admin/billing/gateways.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Payment Gateways')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.billing.index') }}" class="text-gray-500 hover:text-gray-700">Billing</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Gateways</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Gateways</h1>
            <p class="text-sm text-gray-500 mt-1">Configure payment gateway settings</p>
        </div>
        <button onclick="testGateway()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-vial mr-2"></i> Test Connection
        </button>
    </div>

    <!-- ===== GATEWAYS GRID ===== -->
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
                            <p class="text-xs text-gray-500">Credit Card & Digital Wallets</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $gateways['stripe']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $gateways['stripe']['enabled'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $gateways['stripe']['enabled'] ? 'Connected' : 'Not Connected' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Mode</span>
                    <span class="text-gray-900">{{ ucfirst($gateways['stripe']['mode'] ?? 'live') }}</span>
                </div>
                <button onclick="configureGateway('stripe')" 
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
                            <p class="text-xs text-gray-500">PayPal & Venmo</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $gateways['paypal']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $gateways['paypal']['enabled'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $gateways['paypal']['enabled'] ? 'Connected' : 'Not Connected' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Mode</span>
                    <span class="text-gray-900">{{ ucfirst($gateways['paypal']['mode'] ?? 'live') }}</span>
                </div>
                <button onclick="configureGateway('paypal')" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-2"></i> Configure
                </button>
            </div>
        </div>

        <!-- Razorpay -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl font-bold">
                            R
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Razorpay</h3>
                            <p class="text-xs text-gray-500">Indian Payment Gateway</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $gateways['razorpay']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $gateways['razorpay']['enabled'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $gateways['razorpay']['enabled'] ? 'Connected' : 'Not Connected' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Mode</span>
                    <span class="text-gray-900">{{ ucfirst($gateways['razorpay']['mode'] ?? 'live') }}</span>
                </div>
                <button onclick="configureGateway('razorpay')" 
                        class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-2"></i> Configure
                </button>
            </div>
        </div>

        <!-- Paddle -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-teal-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-2xl font-bold">
                            P
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Paddle</h3>
                            <p class="text-xs text-gray-500">Global Payment Platform</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $gateways['paddle']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $gateways['paddle']['enabled'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $gateways['paddle']['enabled'] ? 'Connected' : 'Not Connected' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Mode</span>
                    <span class="text-gray-900">{{ ucfirst($gateways['paddle']['mode'] ?? 'live') }}</span>
                </div>
                <button onclick="configureGateway('paddle')" 
                        class="w-full px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-2"></i> Configure
                </button>
            </div>
        </div>

        <!-- Crypto -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center text-2xl font-bold">
                            ₿
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Cryptocurrency</h3>
                            <p class="text-xs text-gray-500">Bitcoin, Ethereum & More</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $gateways['crypto']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $gateways['crypto']['enabled'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $gateways['crypto']['enabled'] ? 'Connected' : 'Not Connected' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Mode</span>
                    <span class="text-gray-900">{{ ucfirst($gateways['crypto']['mode'] ?? 'live') }}</span>
                </div>
                <button onclick="configureGateway('crypto')" 
                        class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-2"></i> Configure
                </button>
            </div>
        </div>

        <!-- Bank Transfer -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center text-2xl font-bold">
                            🏦
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Bank Transfer</h3>
                            <p class="text-xs text-gray-500">Direct Bank Transfers</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $gateways['bank_transfer']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $gateways['bank_transfer']['enabled'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $gateways['bank_transfer']['enabled'] ? 'Configured' : 'Not Configured' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Currencies</span>
                    <span class="text-gray-900">USD, EUR, GBP</span>
                </div>
                <button onclick="configureGateway('bank_transfer')" 
                        class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-2"></i> Configure
                </button>
            </div>
        </div>
    </div>

    <!-- ===== GATEWAY CONFIGURATION MODAL ===== -->
    <div x-data="{ show: false, gateway: null }" 
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" @click="show = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="gateway-form" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="gateway-modal-title">Configure Gateway</h3>
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
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            API Key / Client ID
                                        </label>
                                        <input type="text" name="api_key" 
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            API Secret / Secret Key
                                        </label>
                                        <input type="password" name="api_secret" 
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Webhook Secret
                                        </label>
                                        <input type="text" name="webhook_secret" 
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Supported Currencies
                                        </label>
                                        <select name="currencies[]" multiple 
                                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" size="4">
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                            <option value="GBP">GBP</option>
                                            <option value="CAD">CAD</option>
                                            <option value="AUD">AUD</option>
                                            <option value="JPY">JPY</option>
                                            <option value="INR">INR</option>
                                        </select>
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
</div>

@push('scripts')
<script>
    function configureGateway(gateway) {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.show = true;
        modal.gateway = gateway;
        document.getElementById('gateway-modal-title').textContent = `Configure ${gateway.charAt(0).toUpperCase() + gateway.slice(1)}`;
        document.getElementById('gateway-form').action = `/admin/billing/gateways/${gateway}`;
        
        // Load existing configuration
        fetch(`/admin/billing/gateways/${gateway}/config`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('select[name="enabled"]').value = data.data.enabled ? 1 : 0;
                    document.querySelector('select[name="mode"]').value = data.data.mode || 'live';
                    document.querySelector('input[name="api_key"]').value = data.data.api_key || '';
                    document.querySelector('input[name="api_secret"]').value = data.data.api_secret || '';
                    document.querySelector('input[name="webhook_secret"]').value = data.data.webhook_secret || '';
                }
            });
    }

    function testGateway() {
        fetch('{{ route("admin.billing.gateways.test") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('All gateways are connected successfully!');
            } else {
                alert('Some gateways failed to connect: ' + data.message);
            }
        });
    }
</script>
@endpush
@endsection