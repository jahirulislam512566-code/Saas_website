@extends('admin.layouts.admin')

@section('title', 'Payment Settings')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.settings.index') }}" class="text-gray-500 hover:text-gray-700">Settings</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Payment</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Settings Navigation -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-2 bg-white rounded-xl shadow-sm p-3">
            <a href="{{ route('admin.settings.general') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-cog mr-2"></i> General
            </a>
            <a href="{{ route('admin.settings.payment') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium">
                <i class="fas fa-credit-card mr-2"></i> Payment
            </a>
            <a href="{{ route('admin.settings.smtp') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-envelope mr-2"></i> SMTP
            </a>
            <a href="{{ route('admin.settings.seo') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-search mr-2"></i> SEO
            </a>
            <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-share-alt mr-2"></i> Social
            </a>
            <a href="{{ route('admin.settings.system') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-server mr-2"></i> System
            </a>
            <a href="{{ route('admin.settings.security') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-shield-alt mr-2"></i> Security
            </a>
            <a href="{{ route('admin.settings.integrations') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-plug mr-2"></i> Integrations
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-slide-down">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
                <button type="button" class="ml-auto text-green-500 hover:text-green-700" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-slide-down">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
                <button type="button" class="ml-auto text-red-500 hover:text-red-700" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Form -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-credit-card text-primary-600 mr-2"></i>
                        Payment Settings
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Configure payment gateway and billing preferences</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        Last updated: {{ setting('payment_updated_at') ? \Carbon\Carbon::parse(setting('payment_updated_at'))->diffForHumans() : 'Never' }}
                    </span>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.settings.update.payment') }}" method="POST" class="p-6" id="paymentForm">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- Payment Gateway Selection -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-credit-card text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Payment Gateway</h4>
                            <p class="text-xs text-gray-500">Choose your primary payment processor</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="payment_gateway" class="block text-sm font-medium text-gray-700 mb-1">
                                Default Gateway <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-credit-card text-gray-400"></i>
                                </div>
                                <select name="payment_gateway" id="payment_gateway" required
                                        class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('payment_gateway') border-red-500 @enderror">
                                    <option value="stripe" {{ old('payment_gateway', setting('payment_gateway', 'stripe')) == 'stripe' ? 'selected' : '' }}>
                                        💳 Stripe
                                    </option>
                                    <option value="paypal" {{ old('payment_gateway', setting('payment_gateway', 'stripe')) == 'paypal' ? 'selected' : '' }}>
                                        💰 PayPal
                                    </option>
                                    <option value="razorpay" {{ old('payment_gateway', setting('payment_gateway', 'stripe')) == 'razorpay' ? 'selected' : '' }}>
                                        💰 Razorpay
                                    </option>
                                    <option value="paddle" {{ old('payment_gateway', setting('payment_gateway', 'stripe')) == 'paddle' ? 'selected' : '' }}>
                                        🏓 Paddle
                                    </option>
                                </select>
                            </div>
                            @error('payment_gateway')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">
                                Default Currency <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-dollar-sign text-gray-400"></i>
                                </div>
                                <select name="currency" id="currency" required
                                        class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('currency') border-red-500 @enderror">
                                    <option value="USD" {{ old('currency', setting('currency', 'USD')) == 'USD' ? 'selected' : '' }}>$ USD - US Dollar</option>
                                    <option value="EUR" {{ old('currency', setting('currency', 'USD')) == 'EUR' ? 'selected' : '' }}>€ EUR - Euro</option>
                                    <option value="GBP" {{ old('currency', setting('currency', 'USD')) == 'GBP' ? 'selected' : '' }}>£ GBP - British Pound</option>
                                    <option value="CAD" {{ old('currency', setting('currency', 'USD')) == 'CAD' ? 'selected' : '' }}>C$ CAD - Canadian Dollar</option>
                                    <option value="AUD" {{ old('currency', setting('currency', 'USD')) == 'AUD' ? 'selected' : '' }}>A$ AUD - Australian Dollar</option>
                                    <option value="JPY" {{ old('currency', setting('currency', 'USD')) == 'JPY' ? 'selected' : '' }}>¥ JPY - Japanese Yen</option>
                                    <option value="INR" {{ old('currency', setting('currency', 'USD')) == 'INR' ? 'selected' : '' }}>₹ INR - Indian Rupee</option>
                                    <option value="BRL" {{ old('currency', setting('currency', 'USD')) == 'BRL' ? 'selected' : '' }}>R$ BRL - Brazilian Real</option>
                                </select>
                            </div>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Stripe Configuration -->
                <div id="stripe-config" class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fab fa-stripe text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Stripe Configuration</h4>
                            <p class="text-xs text-gray-500">Enter your Stripe API credentials</p>
                        </div>
                        <div class="ml-auto">
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i> Secure
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="stripe_key" class="block text-sm font-medium text-gray-700 mb-1">
                                Publishable Key <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                                <input type="text" name="stripe_key" id="stripe_key" 
                                       value="{{ old('stripe_key', setting('stripe_key')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('stripe_key') border-red-500 @enderror"
                                       placeholder="pk_test_xxxxxxxxxxxxx"
                                       required>
                            </div>
                            @error('stripe_key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Publishable key for frontend (starts with pk_)</p>
                        </div>
                        
                        <div>
                            <label for="stripe_secret" class="block text-sm font-medium text-gray-700 mb-1">
                                Secret Key <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                                <input type="password" name="stripe_secret" id="stripe_secret" 
                                       value="{{ old('stripe_secret', setting('stripe_secret')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('stripe_secret') border-red-500 @enderror"
                                       placeholder="sk_test_xxxxxxxxxxxxx"
                                       required>
                                <button type="button" onclick="togglePasswordVisibility('stripe_secret')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                            @error('stripe_secret')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Secret key for backend (starts with sk_)</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="stripe_webhook_secret" class="block text-sm font-medium text-gray-700 mb-1">
                            Webhook Secret
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-webhook text-gray-400"></i>
                            </div>
                            <input type="text" name="stripe_webhook_secret" id="stripe_webhook_secret" 
                                   value="{{ old('stripe_webhook_secret', setting('stripe_webhook_secret')) }}"
                                   class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('stripe_webhook_secret') border-red-500 @enderror"
                                   placeholder="whsec_xxxxxxxxxxxxx">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Used to verify webhook signatures (starts with whsec_)</p>
                        @error('stripe_webhook_secret')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-xs text-blue-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                Webhook URL: <code class="bg-blue-100 px-2 py-1 rounded">{{ url('/webhooks/stripe') }}</code>
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="button" onclick="testStripeConnection()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <i class="fas fa-plug mr-2"></i> Test Stripe Connection
                        </button>
                        <span id="stripeTestResult" class="ml-3"></span>
                    </div>
                </div>
                
                <!-- PayPal Configuration -->
                <div id="paypal-config" style="display: none;" class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fab fa-paypal text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">PayPal Configuration</h4>
                            <p class="text-xs text-gray-500">Enter your PayPal API credentials</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="paypal_client_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Client ID <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-id-card text-gray-400"></i>
                                </div>
                                <input type="text" name="paypal_client_id" id="paypal_client_id" 
                                       value="{{ old('paypal_client_id', setting('paypal_client_id')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('paypal_client_id') border-red-500 @enderror"
                                       placeholder="AU1Jq_xxxxxxxxxxxxx">
                            </div>
                            @error('paypal_client_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="paypal_secret" class="block text-sm font-medium text-gray-700 mb-1">
                                Secret <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                                <input type="password" name="paypal_secret" id="paypal_secret" 
                                       value="{{ old('paypal_secret', setting('paypal_secret')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('paypal_secret') border-red-500 @enderror"
                                       placeholder="EJf-xxxxxxxxxxxxx">
                                <button type="button" onclick="togglePasswordVisibility('paypal_secret')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                            @error('paypal_secret')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="paypal_mode" class="block text-sm font-medium text-gray-700 mb-1">
                            Mode <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-globe text-gray-400"></i>
                            </div>
                            <select name="paypal_mode" id="paypal_mode"
                                    class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('paypal_mode') border-red-500 @enderror">
                                <option value="sandbox" {{ old('paypal_mode', setting('paypal_mode', 'sandbox')) == 'sandbox' ? 'selected' : '' }}>
                                    🧪 Sandbox (Test Mode)
                                </option>
                                <option value="live" {{ old('paypal_mode', setting('paypal_mode', 'sandbox')) == 'live' ? 'selected' : '' }}>
                                    🚀 Live (Production)
                                </option>
                            </select>
                        </div>
                        @error('paypal_mode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <div class="mt-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                            <p class="text-xs text-yellow-700">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Warning:</strong> Always test with sandbox mode before going live.
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="button" onclick="testPaypalConnection()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <i class="fas fa-plug mr-2"></i> Test PayPal Connection
                        </button>
                        <span id="paypalTestResult" class="ml-3"></span>
                    </div>
                </div>
                
                <!-- Razorpay Configuration -->
                <div id="razorpay-config" style="display: none;" class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-rupee-sign text-indigo-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Razorpay Configuration</h4>
                            <p class="text-xs text-gray-500">Enter your Razorpay API credentials</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="razorpay_key" class="block text-sm font-medium text-gray-700 mb-1">
                                Key ID <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-id-card text-gray-400"></i>
                                </div>
                                <input type="text" name="razorpay_key" id="razorpay_key" 
                                       value="{{ old('razorpay_key', setting('razorpay_key')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('razorpay_key') border-red-500 @enderror"
                                       placeholder="rzp_test_xxxxxxxxxxxxx">
                            </div>
                            @error('razorpay_key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="razorpay_secret" class="block text-sm font-medium text-gray-700 mb-1">
                                Secret <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                                <input type="password" name="razorpay_secret" id="razorpay_secret" 
                                       value="{{ old('razorpay_secret', setting('razorpay_secret')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('razorpay_secret') border-red-500 @enderror"
                                       placeholder="XXXXXXXXXXXXXXXXXXXX">
                                <button type="button" onclick="togglePasswordVisibility('razorpay_secret')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                            @error('razorpay_secret')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Billing Settings -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-file-invoice text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Billing Settings</h4>
                            <p class="text-xs text-gray-500">Configure invoice and billing preferences</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="invoice_prefix" class="block text-sm font-medium text-gray-700 mb-1">
                                Invoice Prefix
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-hashtag text-gray-400"></i>
                                </div>
                                <input type="text" name="invoice_prefix" id="invoice_prefix" 
                                       value="{{ old('invoice_prefix', setting('invoice_prefix', 'INV-')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('invoice_prefix') border-red-500 @enderror"
                                       placeholder="INV-">
                            </div>
                            @error('invoice_prefix')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="invoice_due_days" class="block text-sm font-medium text-gray-700 mb-1">
                                Invoice Due Days
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-day text-gray-400"></i>
                                </div>
                                <input type="number" name="invoice_due_days" id="invoice_due_days" 
                                       value="{{ old('invoice_due_days', setting('invoice_due_days', 30)) }}"
                                       min="1" max="90"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('invoice_due_days') border-red-500 @enderror">
                            </div>
                            @error('invoice_due_days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="tax_enabled" value="1" 
                                   {{ old('tax_enabled', setting('tax_enabled', false)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Enable Tax Calculation</span>
                            <span class="ml-2 text-xs text-gray-500">(Apply tax to all invoices)</span>
                        </label>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4" id="tax-settings" style="{{ old('tax_enabled', setting('tax_enabled', false)) ? '' : 'display: none;' }}">
                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">
                                Tax Rate (%) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-percent text-gray-400"></i>
                                </div>
                                <input type="number" name="tax_rate" id="tax_rate" 
                                       value="{{ old('tax_rate', setting('tax_rate', 0)) }}"
                                       step="0.01" min="0" max="100"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('tax_rate') border-red-500 @enderror"
                                       placeholder="20">
                            </div>
                            @error('tax_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="tax_label" class="block text-sm font-medium text-gray-700 mb-1">
                                Tax Label
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" name="tax_label" id="tax_label" 
                                       value="{{ old('tax_label', setting('tax_label', 'VAT')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('tax_label') border-red-500 @enderror"
                                       placeholder="VAT">
                            </div>
                            @error('tax_label')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div>
                        <a href="{{ route('admin.settings.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Settings
                        </a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="resetForm()" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-undo mr-2"></i> Reset
                        </button>
                        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle gateway configuration visibility
    const paymentGateway = document.getElementById('payment_gateway');
    const stripeConfig = document.getElementById('stripe-config');
    const paypalConfig = document.getElementById('paypal-config');
    const razorpayConfig = document.getElementById('razorpay-config');
    
    function toggleGatewayConfig() {
        const value = paymentGateway.value;
        stripeConfig.style.display = value === 'stripe' ? 'block' : 'none';
        paypalConfig.style.display = value === 'paypal' ? 'block' : 'none';
        razorpayConfig.style.display = value === 'razorpay' ? 'block' : 'none';
    }
    
    paymentGateway.addEventListener('change', toggleGatewayConfig);
    toggleGatewayConfig();
    
    // Tax settings toggle
    const taxEnabled = document.querySelector('input[name="tax_enabled"]');
    const taxSettings = document.getElementById('tax-settings');
    
    taxEnabled.addEventListener('change', function() {
        taxSettings.style.display = this.checked ? 'grid' : 'none';
    });
});

// Toggle password visibility
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.parentElement.querySelector('button');
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Test Stripe Connection
function testStripeConnection() {
    const resultSpan = document.getElementById('stripeTestResult');
    resultSpan.innerHTML = '<span class="text-blue-600"><i class="fas fa-spinner fa-spin mr-1"></i> Testing connection...</span>';
    
    fetch('{{ route("admin.settings.test.stripe") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            key: document.getElementById('stripe_key').value,
            secret: document.getElementById('stripe_secret').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultSpan.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle mr-1"></i> Connection successful!</span>';
        } else {
            resultSpan.innerHTML = '<span class="text-red-600"><i class="fas fa-times-circle mr-1"></i> ' + data.message + '</span>';
        }
    })
    .catch(error => {
        resultSpan.innerHTML = '<span class="text-red-600"><i class="fas fa-times-circle mr-1"></i> Connection failed: ' + error.message + '</span>';
    });
}

// Test PayPal Connection
function testPaypalConnection() {
    const resultSpan = document.getElementById('paypalTestResult');
    resultSpan.innerHTML = '<span class="text-blue-600"><i class="fas fa-spinner fa-spin mr-1"></i> Testing connection...</span>';
    
    fetch('{{ route("admin.settings.test.paypal") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            client_id: document.getElementById('paypal_client_id').value,
            secret: document.getElementById('paypal_secret').value,
            mode: document.getElementById('paypal_mode').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultSpan.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle mr-1"></i> Connection successful!</span>';
        } else {
            resultSpan.innerHTML = '<span class="text-red-600"><i class="fas fa-times-circle mr-1"></i> ' + data.message + '</span>';
        }
    })
    .catch(error => {
        resultSpan.innerHTML = '<span class="text-red-600"><i class="fas fa-times-circle mr-1"></i> Connection failed: ' + error.message + '</span>';
    });
}

// Reset form
function resetForm() {
    if (confirm('Are you sure you want to reset all fields to their current saved values?')) {
        location.reload();
    }
}
</script>
@endpush
@endsection