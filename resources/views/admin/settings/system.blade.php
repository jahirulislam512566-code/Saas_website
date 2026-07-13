@extends('admin.layouts.admin')

@section('title', 'System Settings')

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
            <span class="text-gray-500">System</span>
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
            <a href="{{ route('admin.settings.system') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium">
                <i class="fas fa-server mr-2"></i> System
            </a>
            <a href="{{ route('admin.settings.security') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
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

    <!-- System Overview -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">System Status</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $systemStatus ?? 'Operational' }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Uptime</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $uptime ?? 'N/A' }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Maintenance</p>
                    <p class="text-2xl font-bold text-gray-900">{{ app()->isDownForMaintenance() ? 'ON' : 'OFF' }}</p>
                </div>
                <div class="w-12 h-12 rounded-full {{ app()->isDownForMaintenance() ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }} flex items-center justify-center">
                    <i class="fas fa-tools text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Queue Workers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $queueWorkers ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-tasks text-xl"></i>
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
                        <i class="fas fa-server text-primary-600 mr-2"></i>
                        System Settings
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Configure system-wide settings and maintainence options</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.settings.update.system') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- Maintenance Mode -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="fas fa-tools text-red-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Maintenance Mode</h4>
                            <p class="text-xs text-gray-500">Enable or disable maintenance mode</p>
                        </div>
                        <div class="ml-auto">
                            <span class="text-xs {{ app()->isDownForMaintenance() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} px-2 py-1 rounded-full">
                                {{ app()->isDownForMaintenance() ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" value="1" 
                                   {{ old('maintenance_mode', app()->isDownForMaintenance()) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Enable Maintenance Mode</span>
                        </label>
                        
                        <div>
                            <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-1">
                                Maintenance Message
                            </label>
                            <input type="text" name="maintenance_message" id="maintenance_message" 
                                   value="{{ old('maintenance_message', setting('maintenance_message', 'Be right back!')) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="Maintenance message">
                        </div>
                        
                        <div>
                            <label for="maintenance_retry" class="block text-sm font-medium text-gray-700 mb-1">
                                Retry After (seconds)
                            </label>
                            <input type="number" name="maintenance_retry" id="maintenance_retry" 
                                   value="{{ old('maintenance_retry', setting('maintenance_retry', 60)) }}"
                                   min="0" max="3600"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <p class="mt-1 text-xs text-gray-500">Time in seconds before retry</p>
                        </div>
                    </div>
                </div>
                
                <!-- System Settings -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-cogs text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">System Configuration</h4>
                            <p class="text-xs text-gray-500">General system settings</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="allow_registration" class="flex items-center cursor-pointer">
                                <input type="checkbox" name="allow_registration" value="1" 
                                       {{ old('allow_registration', setting('allow_registration', true)) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-gray-700">Allow User Registration</span>
                            </label>
                        </div>
                        
                        <div>
                            <label for="verify_email" class="flex items-center cursor-pointer">
                                <input type="checkbox" name="verify_email" value="1" 
                                       {{ old('verify_email', setting('verify_email', true)) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-gray-700">Require Email Verification</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Backup Settings -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-database text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Backup Settings</h4>
                            <p class="text-xs text-gray-500">Automatic backup configuration</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="enable_backup" class="flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_backup" value="1" 
                                       {{ old('enable_backup', setting('enable_backup', true)) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-gray-700">Enable Automatic Backups</span>
                            </label>
                        </div>
                        
                        <div>
                            <label for="backup_frequency" class="block text-sm font-medium text-gray-700 mb-1">
                                Backup Frequency
                            </label>
                            <select name="backup_frequency" id="backup_frequency"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="daily" {{ old('backup_frequency', setting('backup_frequency', 'daily')) == 'daily' ? 'selected' : '' }}>
                                    Daily
                                </option>
                                <option value="weekly" {{ old('backup_frequency', setting('backup_frequency', 'daily')) == 'weekly' ? 'selected' : '' }}>
                                    Weekly
                                </option>
                                <option value="monthly" {{ old('backup_frequency', setting('backup_frequency', 'daily')) == 'monthly' ? 'selected' : '' }}>
                                    Monthly
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="backup_retention" class="block text-sm font-medium text-gray-700 mb-1">
                            Backup Retention (days)
                        </label>
                        <input type="number" name="backup_retention" id="backup_retention" 
                               value="{{ old('backup_retention', setting('backup_retention', 30)) }}"
                               min="1" max="365"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                </div>
                
                <!-- Logging Settings -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-file-alt text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Logging Settings</h4>
                            <p class="text-xs text-gray-500">System logging configuration</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="log_level" class="block text-sm font-medium text-gray-700 mb-1">
                                Log Level
                            </label>
                            <select name="log_level" id="log_level"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="debug" {{ old('log_level', setting('log_level', 'debug')) == 'debug' ? 'selected' : '' }}>Debug</option>
                                <option value="info" {{ old('log_level', setting('log_level', 'debug')) == 'info' ? 'selected' : '' }}>Info</option>
                                <option value="notice" {{ old('log_level', setting('log_level', 'debug')) == 'notice' ? 'selected' : '' }}>Notice</option>
                                <option value="warning" {{ old('log_level', setting('log_level', 'debug')) == 'warning' ? 'selected' : '' }}>Warning</option>
                                <option value="error" {{ old('log_level', setting('log_level', 'debug')) == 'error' ? 'selected' : '' }}>Error</option>
                                <option value="critical" {{ old('log_level', setting('log_level', 'debug')) == 'critical' ? 'selected' : '' }}>Critical</option>
                                <option value="alert" {{ old('log_level', setting('log_level', 'debug')) == 'alert' ? 'selected' : '' }}>Alert</option>
                                <option value="emergency" {{ old('log_level', setting('log_level', 'debug')) == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Minimum log level to record</p>
                        </div>
                        
                        <div>
                            <label for="log_retention" class="block text-sm font-medium text-gray-700 mb-1">
                                Log Retention (days)
                            </label>
                            <input type="number" name="log_retention" id="log_retention" 
                                   value="{{ old('log_retention', setting('log_retention', 30)) }}"
                                   min="1" max="365"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <p class="mt-1 text-xs text-gray-500">Number of days to keep logs</p>
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