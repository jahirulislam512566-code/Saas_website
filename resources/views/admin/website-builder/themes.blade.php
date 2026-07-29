<!-- admin/website-builder/themes.blade.php -->
@extends('layout.app')

@section('title', 'Themes')
@section('page-title', 'Themes & Templates')
@section('page-subtitle', 'Manage themes, global styles, and templates')

@section('content')
<div x-data="themeManager()" x-init="init()">
    <!-- Theme Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Themes</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.themes)">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Active Themes</p>
            <p class="text-2xl font-bold text-green-600" x-text="formatNumber(stats.active)">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Templates</p>
            <p class="text-2xl font-bold text-purple-600" x-text="formatNumber(stats.templates)">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Global Styles</p>
            <p class="text-2xl font-bold text-blue-600" x-text="formatNumber(stats.styles)">0</p>
        </div>
    </div>

    <!-- Theme Gallery -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @foreach($themes as $theme)
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-lg transition-all group">
                <div class="relative h-48 bg-gray-100 dark:bg-gray-800 rounded-t-xl overflow-hidden">
                    @if($theme->thumbnail)
                        <img src="{{ $theme->thumbnail }}" alt="{{ $theme->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-600">
                            <i class="fas fa-palette text-4xl"></i>
                        </div>
                    @endif
                    @if($theme->is_default)
                        <div class="absolute top-3 left-3">
                            <span class="px-2 py-1 text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 rounded-full">
                                <i class="fas fa-star mr-1"></i>Default
                            </span>
                        </div>
                    @endif
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                     {{ $theme->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                        'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400' }}">
                            {{ ucfirst($theme->status) }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $theme->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $theme->category }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button @click="previewTheme({{ $theme->id }})" 
                                    class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                            <button @click="editTheme({{ $theme }})" 
                                    class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button @click="deleteTheme({{ $theme->id }})" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-download"></i>
                            {{ number_format($theme->downloads ?? 0) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-layer-group"></i>
                            {{ number_format($theme->templates_count ?? 0) }} templates
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Global Styles -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900 dark:text-white">Global Styles</h4>
            <button @click="openStyleModal()" 
                    class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-plus mr-1.5"></i>Add Style
            </button>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Primary Color</label>
                    <div class="flex items-center gap-2 mt-1">
                        <input type="color" x-model="globalStyles.primary_color" 
                               class="w-8 h-8 rounded border border-gray-300 dark:border-gray-700 cursor-pointer">
                        <input type="text" x-model="globalStyles.primary_color" 
                               class="flex-1 px-2 py-1 text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Secondary Color</label>
                    <div class="flex items-center gap-2 mt-1">
                        <input type="color" x-model="globalStyles.secondary_color" 
                               class="w-8 h-8 rounded border border-gray-300 dark:border-gray-700 cursor-pointer">
                        <input type="text" x-model="globalStyles.secondary_color" 
                               class="flex-1 px-2 py-1 text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Font Family</label>
                    <select x-model="globalStyles.font_family"
                            class="w-full mt-1 px-2 py-1 text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="inter">Inter</option>
                        <option value="roboto">Roboto</option>
                        <option value="open-sans">Open Sans</option>
                        <option value="lato">Lato</option>
                        <option value="poppins">Poppins</option>
                        <option value="montserrat">Montserrat</option>
                    </select>
                </div>
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Border Radius</label>
                    <select x-model="globalStyles.border_radius"
                            class="w-full mt-1 px-2 py-1 text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="none">None</option>
                        <option value="sm">Small</option>
                        <option value="md">Medium</option>
                        <option value="lg">Large</option>
                        <option value="xl">Extra Large</option>
                        <option value="full">Full</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-end gap-3">
                <button @click="saveGlobalStyles()" 
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                    <i class="fas fa-save mr-1.5"></i>Save Global Styles
                </button>
                <button @click="resetGlobalStyles()" 
                        class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                    <i class="fas fa-undo mr-1.5"></i>Reset to Defaults
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function themeManager() {
    return {
        stats: @json($stats),
        themes: @json($themes),
        globalStyles: @json($globalStyles),
        
        previewTheme(id) {
            window.open(`{{ route('admin.website-builder.themes.preview', '') }}/${id}`, '_blank');
        },
        
        editTheme(theme) {
            // Open theme edit modal
            alert('Edit theme: ' + theme.name);
        },
        
        deleteTheme(id) {
            if (confirm('Are you sure you want to delete this theme?')) {
                fetch(`{{ route('admin.website-builder.themes.destroy', '') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error deleting theme:', error));
            }
        },
        
        saveGlobalStyles() {
            fetch('{{ route("admin.website-builder.themes.styles") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.globalStyles)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Global styles saved successfully!');
                }
            })
            .catch(error => console.error('Error saving global styles:', error));
        },
        
        resetGlobalStyles() {
            if (confirm('Reset global styles to defaults?')) {
                fetch('{{ route("admin.website-builder.themes.styles.reset") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.globalStyles = data.styles;
                        alert('Global styles reset successfully!');
                    }
                })
                .catch(error => console.error('Error resetting global styles:', error));
            }
        },
        
        formatNumber(value) {
            return value.toLocaleString();
        }
    }
}
</script>
@endpush