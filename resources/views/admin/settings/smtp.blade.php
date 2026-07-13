@extends('admin.layouts.admin')

@section('title', 'SMTP Settings')

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
            <span class="text-gray-500">SMTP</span>
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
            <a href="{{ route('admin.settings.payment') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-credit-card mr-2"></i> Payment
            </a>
            <a href="{{ route('admin.settings.smtp') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium">
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

    <!-- SMTP Status Overview -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 {{ setting('mail_host') ? 'border-green-500' : 'border-gray-300' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Status</p>
                    <p class="text-lg font-bold {{ setting('mail_host') ? 'text-green-600' : 'text-gray-400' }}">
                        {{ setting('mail_host') ? 'Configured' : 'Not Configured' }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full {{ setting('mail_host') ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center">
                    <i class="fas {{ setting('mail_host') ? 'fa-check-circle' : 'fa-times-circle' }} text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Mailer</p>
                    <p class="text-lg font-bold text-gray-900">{{ ucfirst(setting('mail_driver', 'smtp')) }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-server text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Host</p>
                    <p class="text-lg font-bold text-gray-900 truncate">{{ setting('mail_host', 'Not Set') }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-globe text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Port</p>
                    <p class="text-lg font-bold text-gray-900">{{ setting('mail_port', 'Not Set') }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-plug text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-envelope text-primary-600 mr-2"></i>
                        SMTP Settings
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Configure your email server settings for sending emails</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        Last updated: {{ setting('smtp_updated_at') ? \Carbon\Carbon::parse(setting('smtp_updated_at'))->diffForHumans() : 'Never' }}
                    </span>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.settings.update.smtp') }}" method="POST" class="p-6" id="smtpForm">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- Mail Configuration -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-cogs text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Mail Configuration</h4>
                            <p class="text-xs text-gray-500">Basic mail server settings</p>
                        </div>
                        <div class="ml-auto">
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i> Required
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="mail_driver" class="block text-sm font-medium text-gray-700 mb-1">
                                Mail Driver <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tachometer-alt text-gray-400"></i>
                                </div>
                                <select name="mail_driver" id="mail_driver" required
                                        class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('mail_driver') border-red-500 @enderror">
                                    <option value="smtp" {{ old('mail_driver', setting('mail_driver', 'smtp')) == 'smtp' ? 'selected' : '' }}>📨 SMTP</option>
                                    <option value="sendmail" {{ old('mail_driver', setting('mail_driver', 'smtp')) == 'sendmail' ? 'selected' : '' }}>📤 Sendmail</option>
                                    <option value="mailgun" {{ old('mail_driver', setting('mail_driver', 'smtp')) == 'mailgun' ? 'selected' : '' }}>🔫 Mailgun</option>
                                    <option value="postmark" {{ old('mail_driver', setting('mail_driver', 'smtp')) == 'postmark' ? 'selected' : '' }}>📮 Postmark</option>
                                    <option value="ses" {{ old('mail_driver', setting('mail_driver', 'smtp')) == 'ses' ? 'selected' : '' }}>☁️ Amazon SES</option>
                                    <option value="log" {{ old('mail_driver', setting('mail_driver', 'smtp')) == 'log' ? 'selected' : '' }}>📋 Log (Testing)</option>
                                </select>
                            </div>
                            @error('mail_driver')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Choose the mail driver for sending emails</p>
                        </div>
                        
                        <div>
                            <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-1">
                                Mail Host <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-server text-gray-400"></i>
                                </div>
                                <input type="text" name="mail_host" id="mail_host" 
                                       value="{{ old('mail_host', setting('mail_host', 'smtp.mailtrap.io')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('mail_host') border-red-500 @enderror"
                                       placeholder="smtp.example.com"
                                       required
                                       oninput="updateStatus()">
                            </div>
                            @error('mail_host')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Your SMTP server hostname or IP address</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-1">
                                Port <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-plug text-gray-400"></i>
                                </div>
                                <input type="number" name="mail_port" id="mail_port" 
                                       value="{{ old('mail_port', setting('mail_port', 587)) }}"
                                       min="1" max="65535"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('mail_port') border-red-500 @enderror"
                                       required>
                            </div>
                            @error('mail_port')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Common ports: 25, 465 (SSL), 587 (TLS)</p>
                        </div>
                        
                        <div>
                            <label for="mail_encryption" class="block text-sm font-medium text-gray-700 mb-1">
                                Encryption
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <select name="mail_encryption" id="mail_encryption"
                                        class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('mail_encryption') border-red-500 @enderror">
                                    <option value="tls" {{ old('mail_encryption', setting('mail_encryption', 'tls')) == 'tls' ? 'selected' : '' }}>🔒 TLS</option>
                                    <option value="ssl" {{ old('mail_encryption', setting('mail_encryption', 'tls')) == 'ssl' ? 'selected' : '' }}>🔒 SSL</option>
                                    <option value="" {{ old('mail_encryption', setting('mail_encryption', 'tls')) == '' ? 'selected' : '' }}>🔓 None</option>
                                </select>
                            </div>
                            @error('mail_encryption')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Recommended: TLS for security</p>
                        </div>
                    </div>
                </div>
                
                <!-- Authentication -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-user-lock text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Authentication</h4>
                            <p class="text-xs text-gray-500">SMTP login credentials</p>
                        </div>
                        <div class="ml-auto">
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
                                <i class="fas fa-shield-alt mr-1"></i> Secure
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-1">
                                Username
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" name="mail_username" id="mail_username" 
                                       value="{{ old('mail_username', setting('mail_username')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('mail_username') border-red-500 @enderror"
                                       placeholder="username@example.com">
                            </div>
                            @error('mail_username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                                <input type="password" name="mail_password" id="mail_password" 
                                       value="{{ old('mail_password', setting('mail_password')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('mail_password') border-red-500 @enderror"
                                       placeholder="********">
                                <button type="button" onclick="togglePasswordVisibility('mail_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Leave empty to keep current password
                            </p>
                            @error('mail_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-xs text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Note:</strong> Your credentials are encrypted and stored securely.
                        </p>
                    </div>
                </div>
                
                <!-- Sender Information -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-user-edit text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Sender Information</h4>
                            <p class="text-xs text-gray-500">Email sender details</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-1">
                                From Address <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-at text-gray-400"></i>
                                </div>
                                <input type="email" name="mail_from_address" id="mail_from_address" 
                                       value="{{ old('mail_from_address', setting('mail_from_address', 'noreply@example.com')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('mail_from_address') border-red-500 @enderror"
                                       placeholder="noreply@example.com"
                                       required>
                            </div>
                            @error('mail_from_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">The email address that will appear as the sender</p>
                        </div>
                        
                        <div>
                            <label for="mail_from_name" class="block text-sm font-medium text-gray-700 mb-1">
                                From Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tag text-gray-400"></i>
                                </div>
                                <input type="text" name="mail_from_name" id="mail_from_name" 
                                       value="{{ old('mail_from_name', setting('mail_from_name', config('app.name'))) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('mail_from_name') border-red-500 @enderror"
                                       placeholder="{{ config('app.name') }}"
                                       required>
                            </div>
                            @error('mail_from_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">The name that will appear as the sender</p>
                        </div>
                    </div>
                </div>
                
                <!-- Test Email Section -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-paper-plane text-indigo-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Test Email</h4>
                            <p class="text-xs text-gray-500">Send a test email to verify your configuration</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" id="test_email" 
                                       value="{{ auth()->user()->email }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       placeholder="Enter email to send test">
                            </div>
                        </div>
                        <button type="button" onclick="sendTestEmail()" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors whitespace-nowrap">
                            <i class="fas fa-paper-plane mr-2"></i> Send Test Email
                        </button>
                    </div>
                    
                    <div id="testEmailStatus" class="mt-3 hidden"></div>
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

// Update status indicator
function updateStatus() {
    const host = document.getElementById('mail_host').value;
    const statusBadge = document.querySelector('.border-l-4 .text-lg.font-bold');
    if (statusBadge) {
        statusBadge.textContent = host ? 'Configured' : 'Not Configured';
    }
}

// Send test email
function sendTestEmail() {
    const email = document.getElementById('test_email').value;
    const statusDiv = document.getElementById('testEmailStatus');
    
    if (!email) {
        statusDiv.className = 'mt-3 p-3 bg-red-50 border border-red-200 rounded-lg';
        statusDiv.innerHTML = '<p class="text-sm text-red-700"><i class="fas fa-exclamation-circle mr-2"></i> Please enter an email address.</p>';
        statusDiv.classList.remove('hidden');
        return;
    }
    
    // Show loading
    statusDiv.className = 'mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg';
    statusDiv.innerHTML = '<p class="text-sm text-blue-700"><i class="fas fa-spinner fa-spin mr-2"></i> Sending test email...</p>';
    statusDiv.classList.remove('hidden');
    
    // Get current SMTP settings from form
    const formData = new FormData(document.getElementById('smtpForm'));
    formData.append('test_email', email);
    
    fetch('{{ route("admin.settings.test.smtp") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusDiv.className = 'mt-3 p-3 bg-green-50 border border-green-200 rounded-lg';
            statusDiv.innerHTML = `<p class="text-sm text-green-700"><i class="fas fa-check-circle mr-2"></i> ${data.message}</p>`;
        } else {
            statusDiv.className = 'mt-3 p-3 bg-red-50 border border-red-200 rounded-lg';
            statusDiv.innerHTML = `<p class="text-sm text-red-700"><i class="fas fa-times-circle mr-2"></i> ${data.message}</p>`;
        }
    })
    .catch(error => {
        statusDiv.className = 'mt-3 p-3 bg-red-50 border border-red-200 rounded-lg';
        statusDiv.innerHTML = `<p class="text-sm text-red-700"><i class="fas fa-times-circle mr-2"></i> Failed to send test email: ${error.message}</p>`;
    });
}

// Reset form
function resetForm() {
    if (confirm('Are you sure you want to reset all fields to their current saved values?')) {
        location.reload();
    }
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.animate-slide-down');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>
@endpush
@endsection