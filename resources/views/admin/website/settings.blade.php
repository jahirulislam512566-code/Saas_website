<!-- admin/websites/settings.blade.php -->
@extends('layout.app')

@section('title', 'Website Settings')
@section('page-title', 'Website Settings')
@section('page-subtitle', 'Configure advanced settings for {{ $website->name }}')

@section('content')
<div x-data="websiteSettings()" x-init="init()">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Settings -->
        <div class="lg:col-span-2 space-y-6">
            <!-- General Settings -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">General Settings</h4>
                    
                    <form @submit.prevent="saveSettings()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Language</label>
                            <select x-model="settings.language"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="en">English</option>
                                <option value="es">Spanish</option>
                                <option value="fr">French</option>
                                <option value="de">German</option>
                                <option value="zh">Chinese</option>
                                <option value="ar">Arabic</option>
                                <option value="hi">Hindi</option>
                                <option value="pt">Portuguese</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Timezone</label>
                            <select x-model="settings.timezone"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="UTC">UTC</option>
                                <option value="America/New_York">Eastern Time</option>
                                <option value="America/Chicago">Central Time</option>
                                <option value="America/Denver">Mountain Time</option>
                                <option value="America/Los_Angeles">Pacific Time</option>
                                <option value="Europe/London">London</option>
                                <option value="Europe/Paris">Paris</option>
                                <option value="Asia/Dubai">Dubai</option>
                                <option value="Asia/Singapore">Singapore</option>
                                <option value="Australia/Sydney">Sydney</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Currency</label>
                            <select x-model="settings.currency"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                                <option value="GBP">GBP (£)</option>
                                <option value="CAD">CAD ($)</option>
                                <option value="AUD">AUD ($)</option>
                                <option value="JPY">JPY (¥)</option>
                                <option value="INR">INR (₹)</option>
                                <option value="BRL">BRL (R$)</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Performance Settings -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Performance Settings</h4>
                    
                    <form @submit.prevent="saveSettings()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cache Duration (seconds)</label>
                            <input type="number" x-model="settings.cache_duration"
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">How long to cache pages (0 = no cache)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CDN Provider</label>
                            <select x-model="settings.cdn_provider"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="none">None</option>
                                <option value="cloudflare">Cloudflare</option>
                                <option value="aws">AWS CloudFront</option>
                                <option value="akamai">Akamai</option>
                                <option value="fastly">Fastly</option>
                            </select>
                        </div>

                        <div x-show="settings.cdn_provider !== 'none'">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CDN URL</label>
                            <input type="url" x-model="settings.cdn_url"
                                   placeholder="https://cdn.example.com"
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" x-model="settings.enable_gzip">
                                Enable GZIP Compression
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 mt-2">
                                <input type="checkbox" x-model="settings.enable_minify">
                                Minify CSS & JavaScript
                            </label>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Security Settings</h4>
                    
                    <form @submit.prevent="saveSettings()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rate Limiting</label>
                            <select x-model="settings.rate_limit"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="none">None</option>
                                <option value="60">60 requests per minute</option>
                                <option value="120">120 requests per minute</option>
                                <option value="300">300 requests per minute</option>
                                <option value="600">600 requests per minute</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">IP Whitelist</label>
                            <textarea x-model="settings.ip_whitelist" rows="2"
                                      placeholder="Enter IP addresses, one per line"
                                      class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to allow all IPs</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">IP Blacklist</label>
                            <textarea x-model="settings.ip_blacklist" rows="2"
                                      placeholder="Enter IP addresses, one per line"
                                      class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Blocked IPs will be denied access</p>
                        </div>

                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" x-model="settings.force_ssl">
                            Force SSL/HTTPS
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" x-model="settings.enable_cors">
                            Enable CORS
                        </label>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Save Actions -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="space-y-2">
                        <button @click="saveSettings()" 
                                class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all">
                            <i class="fas fa-save mr-1.5"></i>Save All Settings
                        </button>
                        <button @click="resetSettings()" 
                                class="w-full px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                            <i class="fas fa-undo mr-1.5"></i>Reset to Defaults
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h4>
                    
                    <div class="space-y-2">
                        <button @click="clearCache()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-trash-alt"></i> Clear Cache
                        </button>
                        <button @click="backupWebsite()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-database"></i> Backup Website
                        </button>
                        <button @click="exportWebsite()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-file-export"></i> Export Website
                        </button>
                    </div>
                </div>
            </div>

            <!-- Current Settings Status -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Status</h4>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Cache Status</span>
                            <span class="text-green-600 dark:text-green-400">
                                <i class="fas fa-check-circle mr-1"></i> Active
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">CDN Status</span>
                            <span x-show="settings.cdn_provider !== 'none'" class="text-green-600 dark:text-green-400">
                                <i class="fas fa-check-circle mr-1"></i> Connected
                            </span>
                            <span x-show="settings.cdn_provider === 'none'" class="text-gray-500 dark:text-gray-400">
                                <i class="fas fa-minus-circle mr-1"></i> Disabled
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">SSL</span>
                            <span x-show="settings.force_ssl" class="text-green-600 dark:text-green-400">
                                <i class="fas fa-check-circle mr-1"></i> Enabled
                            </span>
                            <span x-show="!settings.force_ssl" class="text-gray-500 dark:text-gray-400">
                                <i class="fas fa-minus-circle mr-1"></i> Disabled
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Last Backup</span>
                            <span class="text-gray-900 dark:text-white">Yesterday</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function websiteSettings() {
    return {
        settings: @json($website->settings ?? [
            'language' => 'en',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'cache_duration' => 3600,
            'cdn_provider' => 'none',
            'cdn_url' => '',
            'enable_gzip' => true,
            'enable_minify' => true,
            'rate_limit' => '60',
            'ip_whitelist' => '',
            'ip_blacklist' => '',
            'force_ssl' => true,
            'enable_cors' => false
        ]),
        websiteId: {{ $website->id }},
        
        saveSettings() {
            fetch(`{{ route('admin.websites.settings.update', $website) }}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ settings: this.settings })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Settings saved successfully!');
                }
            })
            .catch(error => console.error('Error saving settings:', error));
        },
        
        resetSettings() {
            if (confirm('Reset all settings to default values?')) {
                fetch(`{{ route('admin.websites.settings.reset', $website) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.settings = data.settings;
                        alert('Settings reset successfully!');
                    }
                })
                .catch(error => console.error('Error resetting settings:', error));
            }
        },
        
        clearCache() {
            if (confirm('Clear website cache?')) {
                fetch(`{{ route('admin.websites.cache.clear', $website) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Cache cleared successfully!');
                    }
                })
                .catch(error => console.error('Error clearing cache:', error));
            }
        },
        
        backupWebsite() {
            if (confirm('Create a backup of this website?')) {
                fetch(`{{ route('admin.websites.backup', $website) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Backup created successfully!');
                    }
                })
                .catch(error => console.error('Error creating backup:', error));
            }
        },
        
        exportWebsite() {
            window.open(`{{ route('admin.websites.export', $website) }}`, '_blank');
        }
    }
}
</script>
@endpush