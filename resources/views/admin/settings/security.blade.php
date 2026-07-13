@extends('admin.layouts.admin')

@section('title', 'Security Settings')

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
            <span class="text-gray-500">Security</span>
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
            <a href="{{ route('admin.settings.security') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium">
                <i class="fas fa-shield-alt mr-2"></i> Security
            </a>
            <a href="{{ route('admin.settings.integrations') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-plug mr-2"></i> Integrations
            </a>
            <a href="{{ route('admin.settings.environment') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-code mr-2"></i> Environment
            </a>
        </div>
    </div>

    <!-- Security Overview -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 {{ $securityScore ?? 85 >= 80 ? 'border-green-500' : ($securityScore ?? 85 >= 60 ? 'border-yellow-500' : 'border-red-500') }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Security Score</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $securityScore ?? 85 }}%</p>
                </div>
                <div class="w-12 h-12 rounded-full {{ $securityScore ?? 85 >= 80 ? 'bg-green-100 text-green-600' : ($securityScore ?? 85 >= 60 ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }} flex items-center justify-center">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
            </div>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                <div class="bg-{{ $securityScore ?? 85 >= 80 ? 'green' : ($securityScore ?? 85 >= 60 ? 'yellow' : 'red') }}-500 h-2 rounded-full" style="width: {{ $securityScore ?? 85 }}%"></div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">2FA Status</p>
                    <p class="text-2xl font-bold text-gray-900">{{ setting('enable_2fa', false) ? 'ON' : 'OFF' }}</p>
                </div>
                <div class="w-12 h-12 rounded-full {{ setting('enable_2fa', false) ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center">
                    <i class="fas fa-mobile-alt text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">SSL/HTTPS</p>
                    <p class="text-2xl font-bold text-gray-900">{{ request()->secure() ? 'Secure' : 'Not Secure' }}</p>
                </div>
                <div class="w-12 h-12 rounded-full {{ request()->secure() ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center">
                    <i class="fas fa-lock text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Failed Logins</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $failedLoginsToday ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fas fa-user-lock text-xl"></i>
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
                        <i class="fas fa-shield-alt text-primary-600 mr-2"></i>
                        Security Settings
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Configure application security and authentication settings</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.settings.update.security') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- Authentication Security -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-user-lock text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Authentication Security</h4>
                            <p class="text-xs text-gray-500">Login and authentication settings</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="enable_2fa" class="flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_2fa" value="1" 
                                       {{ old('enable_2fa', setting('enable_2fa', false)) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-gray-700">Enable Two-Factor Authentication</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Require users to verify their identity with a second factor</p>
                        </div>
                        
                        <div>
                            <label for="force_2fa_admin" class="flex items-center cursor-pointer">
                                <input type="checkbox" name="force_2fa_admin" value="1" 
                                       {{ old('force_2fa_admin', setting('force_2fa_admin', false)) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-gray-700">Force 2FA for Admins</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Require two-factor authentication for admin users</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 mb-1">
                                Max Login Attempts
                            </label>
                            <input type="number" name="max_login_attempts" id="max_login_attempts" 
                                   value="{{ old('max_login_attempts', setting('max_login_attempts', 5)) }}"
                                   min="1" max="20"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <p class="mt-1 text-xs text-gray-500">Number of failed attempts before lockout</p>
                        </div>
                        
                        <div>
                            <label for="lockout_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Lockout Time (minutes)
                            </label>
                            <input type="number" name="lockout_time" id="lockout_time" 
                                   value="{{ old('lockout_time', setting('lockout_time', 15)) }}"
                                   min="1" max="1440"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <p class="mt-1 text-xs text-gray-500">Time in minutes before lockout expires</p>
                        </div>
                    </div>
                </div>
                
                <!-- Password Policies -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-key text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Password Policies</h4>
                            <p class="text-xs text-gray-500">Password requirements and expiration</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password_min_length" class="block text-sm font-medium text-gray-700 mb-1">
                                Minimum Password Length
                            </label>
                            <input type="number" name="password_min_length" id="password_min_length" 
                                   value="{{ old('password_min_length', setting('password_min_length', 8)) }}"
                                   min="6" max="20"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        
                        <div>
                            <label for="password_expiry_days" class="block text-sm font-medium text-gray-700 mb-1">
                                Password Expiry (days)
                            </label>
                            <input type="number" name="password_expiry_days" id="password_expiry_days" 
                                   value="{{ old('password_expiry_days', setting('password_expiry_days', 0)) }}"
                                   min="0" max="365"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <p class="mt-1 text-xs text-gray-500">0 = No expiry</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 space-y-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="require_uppercase" value="1" 
                                   {{ old('require_uppercase', setting('require_uppercase', true)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Require uppercase letter</span>
                        </label>
                        
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="require_numbers" value="1" 
                                   {{ old('require_numbers', setting('require_numbers', true)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Require numbers</span>
                        </label>
                        
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="require_symbols" value="1" 
                                   {{ old('require_symbols', setting('require_symbols', false)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Require special characters</span>
                        </label>
                    </div>
                </div>
                
                <!-- Session Security -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-clock text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Session Security</h4>
                            <p class="text-xs text-gray-500">Session and timeout settings</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="session_lifetime" class="block text-sm font-medium text-gray-700 mb-1">
                                Session Lifetime (minutes)
                            </label>
                            <input type="number" name="session_lifetime" id="session_lifetime" 
                                   value="{{ old('session_lifetime', setting('session_lifetime', 120)) }}"
                                   min="5" max="1440"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        
                        <div>
                            <label for="session_idle_timeout" class="block text-sm font-medium text-gray-700 mb-1">
                                Idle Timeout (minutes)
                            </label>
                            <input type="number" name="session_idle_timeout" id="session_idle_timeout" 
                                   value="{{ old('session_idle_timeout', setting('session_idle_timeout', 30)) }}"
                                   min="1" max="1440"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <p class="mt-1 text-xs text-gray-500">Auto-logout after inactivity</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 space-y-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="single_session" value="1" 
                                   {{ old('single_session', setting('single_session', true)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Allow only one session per user</span>
                        </label>
                        
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="session_encryption" value="1" 
                                   {{ old('session_encryption', setting('session_encryption', true)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Encrypt session data</span>
                        </label>
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
                        <button type="reset" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
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
@endsection