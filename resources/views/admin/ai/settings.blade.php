<!-- admin/ai/settings.blade.php -->
@extends('layout.app')

@section('title', 'AI Settings')
@section('page-title', 'AI Settings')
@section('page-subtitle', 'Configure AI providers, models, and behavior')

@section('content')
<div x-data="aiSettings()" x-init="init()">
    <!-- Settings Tabs -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 dark:border-gray-800">
            <nav class="flex overflow-x-auto px-4" x-data="{ tab: 'providers' }">
                <button @click="tab = 'providers'" 
                        class="px-4 py-3 text-sm font-medium border-b-2 transition-colors"
                        :class="tab === 'providers' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                    <i class="fas fa-plug mr-2"></i>Providers
                </button>
                <button @click="tab = 'models'" 
                        class="px-4 py-3 text-sm font-medium border-b-2 transition-colors"
                        :class="tab === 'models' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                    <i class="fas fa-microchip mr-2"></i>Models
                </button>
                <button @click="tab = 'limits'" 
                        class="px-4 py-3 text-sm font-medium border-b-2 transition-colors"
                        :class="tab === 'limits' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                    <i class="fas fa-gauge-high mr-2"></i>Rate Limits
                </button>
                <button @click="tab = 'tools'" 
                        class="px-4 py-3 text-sm font-medium border-b-2 transition-colors"
                        :class="tab === 'tools' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                    <i class="fas fa-tools mr-2"></i>Tools
                </button>
                <button @click="tab = 'security'" 
                        class="px-4 py-3 text-sm font-medium border-b-2 transition-colors"
                        :class="tab === 'security' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                    <i class="fas fa-shield-alt mr-2"></i>Security
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Providers Tab -->
            <div x-show="tab === 'providers'" x-cloak>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">AI Providers</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Configure API connections to AI service providers</p>
                
                <div class="space-y-4">
                    <template x-for="provider in providers" :key="provider.id">
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <img :src="provider.logo" alt="" class="w-6 h-6">
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900 dark:text-white" x-text="provider.name"></h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="provider.description"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs px-2 py-0.5 rounded-full"
                                      :class="provider.status === 'connected' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'"
                                      x-text="provider.status"></span>
                                <button @click="configureProvider(provider)" 
                                        class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                    Configure
                                </button>
                                <button @click="testConnection(provider)" 
                                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                                    <i class="fas fa-play"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 mt-0.5"></i>
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                            API keys are encrypted and stored securely. Never share your API keys publicly.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Models Tab -->
            <div x-show="tab === 'models'" x-cloak>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">AI Models</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Configure which AI models are available for each tool</p>
                
                <div class="space-y-6">
                    <template x-for="tool in toolModels" :key="tool.name">
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white capitalize" x-text="tool.name"></h5>
                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <template x-for="model in tool.models" :key="model.id">
                                    <label class="flex items-center gap-2 p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors cursor-pointer">
                                        <input type="checkbox" x-model="model.enabled" class="rounded text-indigo-600">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="model.name"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="model.provider"></p>
                                        </div>
                                        <span class="ml-auto text-xs px-2 py-0.5 rounded-full"
                                              :class="model.status === 'available' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'"
                                              x-text="model.status"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Rate Limits Tab -->
            <div x-show="tab === 'limits'" x-cloak>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Rate Limits & Quotas</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Configure usage limits to control costs and prevent abuse</p>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Requests Per User</label>
                            <input type="number" x-model="limits.per_user" 
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Requests per month per user</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Tokens Per User</label>
                            <input type="number" x-model="limits.tokens_per_user" 
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tokens per month per user</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rate Limit (per minute)</label>
                            <input type="number" x-model="limits.per_minute" 
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max requests per minute per user</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rate Limit (per hour)</label>
                            <input type="number" x-model="limits.per_hour" 
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max requests per hour per user</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max File Size (MB)</label>
                            <input type="number" x-model="limits.max_file_size" 
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">For image and file uploads</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Output Length</label>
                            <input type="number" x-model="limits.max_output_length" 
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max tokens per response</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tools Tab -->
            <div x-show="tab === 'tools'" x-cloak>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tool Configuration</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Enable/disable AI tools and configure their behavior</p>
                
                <div class="space-y-4">
                    <template x-for="tool in tools" :key="tool.id">
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                     :class="{
                                       'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400': tool.id === 'writer',
                                       'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400': tool.id === 'seo',
                                       'bg-pink-100 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400': tool.id === 'image',
                                       'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400': tool.id === 'website'
                                     }">
                                    <i :class="tool.icon"></i>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900 dark:text-white" x-text="tool.name"></h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="tool.description"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="tool.enabled" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                </label>
                                <button @click="configureTool(tool)" 
                                        class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                    Configure
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Security Tab -->
            <div x-show="tab === 'security'" x-cloak>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Security Settings</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Configure content moderation and security controls</p>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white">Content Moderation</h5>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Filter inappropriate or harmful content</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="security.content_moderation" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white">PII Detection</h5>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Automatically detect and redact personal information</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="security.pii_detection" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white">Prompt Injection Protection</h5>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Protect against prompt injection attacks</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="security.prompt_injection" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white">Audit Logging</h5>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Log all AI interactions for compliance</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="security.audit_logging" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-800 mt-6">
                <button @click="saveSettings()" 
                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all">
                    <i class="fas fa-save mr-2"></i>Save Settings
                </button>
                <button @click="resetDefaults()" 
                        class="ml-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    Reset to Defaults
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function aiSettings() {
    return {
        providers: @json($providers),
        toolModels: @json($toolModels),
        limits: @json($limits),
        tools: @json($tools),
        security: @json($security),
        
        configureProvider(provider) {
            // Open provider configuration modal
            alert('Configure ' + provider.name + ' provider settings');
        },
        
        testConnection(provider) {
            // Test API connection
            fetch(`{{ route('admin.ai.test-connection') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ provider: provider.id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Connection successful!');
                } else {
                    alert('Connection failed: ' + data.error);
                }
            });
        },
        
        configureTool(tool) {
            // Open tool configuration modal
            alert('Configure ' + tool.name + ' tool settings');
        },
        
        saveSettings() {
            // Save all settings
            const settings = {
                providers: this.providers,
                models: this.toolModels,
                limits: this.limits,
                tools: this.tools,
                security: this.security
            };
            
            fetch('{{ route("admin.ai.settings.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(settings)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Settings saved successfully!');
                }
            })
            .catch(error => console.error('Error saving settings:', error));
        },
        
        resetDefaults() {
            if (confirm('Are you sure you want to reset all settings to defaults?')) {
                fetch('{{ route("admin.ai.settings.reset") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }
    }
}
</script>
@endpush