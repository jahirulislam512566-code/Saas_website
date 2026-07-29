<!-- admin/website-builder/settings.blade.php -->
@extends('layout.app')

@section('title', 'Builder Settings')
@section('page-title', 'Builder Settings')
@section('page-subtitle', 'Configure the website builder behavior and features')

@section('content')
<div x-data="builderSettings()" x-init="init()">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Settings -->
        <div class="lg:col-span-2 space-y-6">
            <!-- General Settings -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">General Settings</h4>
                    
                    <form @submit.prevent="saveSettings()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Theme</label>
                            <select x-model="settings.default_theme"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @foreach($themes as $theme)
                                    <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Template</label>
                            <select x-model="settings.default_template"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Font</label>
                            <select x-model="settings.default_font"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="inter">Inter</option>
                                <option value="roboto">Roboto</option>
                                <option value="open-sans">Open Sans</option>
                                <option value="lato">Lato</option>
                                <option value="poppins">Poppins</option>
                                <option value="montserrat">Montserrat</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Feature Settings -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Features</h4>
                    
                    <form @submit.prevent="saveSettings()" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" x-model="settings.enable_drag_drop">
                                Drag & Drop Editor
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" x-model="settings.enable_live_preview">
                                Live Preview
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" x-model="settings.enable_ai_assistant">
                                AI Assistant
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" x-model="settings.enable_code_export">
                                Code Export
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" x-model="settings.enable_custom_css">
                                Custom CSS
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" x-model="settings.enable_custom_js">
                                Custom JavaScript
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" x-model="settings.enable_animation">
                                Animations
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" x-model="settings.enable_responsive">
                                Responsive Design
                            </label>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Export Settings -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Export Settings</h4>
                    
                    <form @submit.prevent="saveSettings()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Export Format</label>
                            <select x-model="settings.export_format"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="html">HTML + CSS</option>
                                <option value="zip">ZIP Archive</option>
                                <option value="vue">Vue.js Component</option>
                                <option value="react">React Component</option>
                                <option value="blade">Laravel Blade</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Minify Output</label>
                            <select x-model="settings.minify_output"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="none">None</option>
                                <option value="html">HTML Only</option>
                                <option value="css">CSS Only</option>
                                <option value="all">All (HTML, CSS, JS)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Include</label>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" x-model="settings.include_assets">
                                    Assets (images, fonts)
                                </label>
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" x-model="settings.include_meta">
                                    Meta Tags
                                </label>
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" x-model="settings.include_seo">
                                    SEO Data
                                </label>
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" x-model="settings.include_analytics">
                                    Analytics
                                </label>
                            </div>
                        </div>
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
                            <i class="fas fa-save mr-1.5"></i>Save Settings
                        </button>
                        <button @click="resetSettings()" 
                                class="w-full px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                            <i class="fas fa-undo mr-1.5"></i>Reset to Defaults
                        </button>
                    </div>
                </div>
            </div>

            <!-- Builder Status -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Builder Status</h4>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Version</span>
                            <span class="text-gray-900 dark:text-white">v2.4.1</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Status</span>
                            <span class="text-green-600 dark:text-green-400">
                                <i class="fas fa-check-circle mr-1"></i> Operational
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Last Update</span>
                            <span class="text-gray-900 dark:text-white">Today</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Cache Size</span>
                            <span class="text-gray-900 dark:text-white">24.6 MB</span>
                        </div>
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
                        <button @click="exportBuilder()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-file-export"></i> Export Builder
                        </button>
                        <button @click="importBuilder()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-file-import"></i> Import Builder
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function builderSettings() {
    return {
        settings: @json($settings),
        
        saveSettings() {
            fetch('{{ route("admin.website-builder.settings.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.settings)
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
            if (confirm('Reset all builder settings to defaults?')) {
                fetch('{{ route("admin.website-builder.settings.reset") }}', {
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
            if (confirm('Clear builder cache?')) {
                fetch('{{ route("admin.website-builder.cache.clear") }}', {
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
        
        exportBuilder() {
            window.open('{{ route("admin.website-builder.export") }}', '_blank');
        },
        
        importBuilder() {
            // File input for import
            alert('Import builder configuration');
        }
    }
}
</script>
@endpush