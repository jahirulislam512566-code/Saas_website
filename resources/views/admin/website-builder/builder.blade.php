<!-- admin/website-builder/builder.blade.php -->
@extends('layout.app')

@section('title', 'Website Builder')
@section('page-title', 'Website Builder')
@section('page-subtitle', 'Drag and drop builder for your website')

@section('styles')
<style>
    .builder-panel {
        height: calc(100vh - 120px);
        overflow-y: auto;
    }
    .drop-zone {
        min-height: 200px;
        border: 2px dashed #d1d5db;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }
    .drop-zone.drag-over {
        border-color: #4f46e5;
        background-color: rgba(79, 70, 229, 0.05);
    }
    .builder-element {
        cursor: move;
        transition: all 0.2s ease;
    }
    .builder-element:hover {
        border-color: #4f46e5;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .builder-element.selected {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }
    .element-handle {
        cursor: move;
    }
</style>
@endpush

@section('content')
<div x-data="builderInterface()" x-init="init()">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Sidebar - Components -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Components</h4>
                    <div class="mt-2 relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" x-model="searchComponents" 
                               placeholder="Search components..." 
                               class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="p-4 builder-panel">
                    <div class="space-y-2">
                        <template x-for="component in filteredComponents" :key="component.id">
                            <div class="builder-element p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-move hover:border-indigo-500 transition-colors"
                                 draggable="true"
                                 @dragstart="dragStart($event, component)"
                                 @click="selectComponent(component)">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                                        <i class="fas fa-{{ component.icon || 'cube' }} text-indigo-600 dark:text-indigo-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="component.name"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="component.category"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Builder Area -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <!-- Builder Toolbar -->
                <div class="p-3 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex items-center gap-2">
                        <button class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-undo text-gray-600 dark:text-gray-400"></i>
                        </button>
                        <button class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-redo text-gray-600 dark:text-gray-400"></i>
                        </button>
                        <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>
                        <select class="px-2 py-1 text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option>Desktop</option>
                            <option>Tablet</option>
                            <option>Mobile</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="previewWebsite()" 
                                class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-eye mr-1.5"></i>Preview
                        </button>
                        <button @click="saveWebsite()" 
                                class="px-3 py-1.5 text-sm bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all">
                            <i class="fas fa-save mr-1.5"></i>Save
                        </button>
                        <button @click="publishWebsite()" 
                                class="px-3 py-1.5 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-sm transition-all">
                            <i class="fas fa-rocket mr-1.5"></i>Publish
                        </button>
                    </div>
                </div>

                <!-- Builder Canvas -->
                <div class="p-4 builder-panel bg-gray-50 dark:bg-gray-800/30">
                    <div id="builder-canvas" 
                         class="min-h-[600px] bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                         @dragover.prevent
                         @drop.prevent="dropElement($event)">
                        
                        <!-- Drop Zone -->
                        <div class="drop-zone flex items-center justify-center text-gray-400 dark:text-gray-600"
                             :class="{'drag-over': dragOver}"
                             x-show="elements.length === 0">
                            <div class="text-center">
                                <i class="fas fa-arrow-down text-3xl mb-2"></i>
                                <p>Drag & drop components here</p>
                                <p class="text-xs mt-1">or click a component to add it</p>
                            </div>
                        </div>

                        <!-- Builder Elements -->
                        <template x-for="(element, index) in elements" :key="index">
                            <div class="builder-element relative group mb-2 p-3 border border-gray-200 dark:border-gray-700 rounded-lg"
                                 :class="{'selected': selectedIndex === index}"
                                 @click="selectElement(index)">
                                <div class="flex items-start gap-2">
                                    <div class="element-handle p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-move">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                    <div class="flex-1" x-html="element.content"></div>
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="duplicateElement(index)" 
                                                class="p-1 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <i class="fas fa-copy text-xs"></i>
                                        </button>
                                        <button @click="deleteElement(index)" 
                                                class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Properties -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Properties</h4>
                </div>
                <div class="p-4 builder-panel">
                    <template x-if="selectedElement">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Element Type</label>
                                <p class="text-sm text-gray-600 dark:text-gray-400" x-text="selectedElement.type"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Background Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" x-model="selectedElement.bgColor" 
                                           class="w-10 h-10 rounded border border-gray-300 dark:border-gray-700 cursor-pointer">
                                    <input type="text" x-model="selectedElement.bgColor" 
                                           class="flex-1 px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Padding</label>
                                <input type="text" x-model="selectedElement.padding" 
                                       placeholder="p-4"
                                       class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Margin</label>
                                <input type="text" x-model="selectedElement.margin" 
                                       placeholder="m-2"
                                       class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Custom CSS</label>
                                <textarea x-model="selectedElement.customCss" rows="2"
                                          class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 font-mono"></textarea>
                            </div>

                            <button @click="applyProperties()" 
                                    class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                                <i class="fas fa-check mr-1.5"></i>Apply Changes
                            </button>
                        </div>
                    </template>
                    <template x-if="!selectedElement">
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                            <i class="fas fa-cube text-2xl mb-2"></i>
                            <p>Select an element to edit</p>
                            <p class="text-xs mt-1">Click on any element in the builder</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function builderInterface() {
    return {
        websiteId: {{ $website->id ?? 0 }},
        components: @json($components),
        elements: @json($page->elements ?? []),
        searchComponents: '',
        selectedIndex: null,
        dragOver: false,
        
        get filteredComponents() {
            if (!this.searchComponents) return this.components;
            const search = this.searchComponents.toLowerCase();
            return this.components.filter(c => 
                c.name.toLowerCase().includes(search) ||
                c.category?.toLowerCase().includes(search)
            );
        },
        
        get selectedElement() {
            if (this.selectedIndex !== null && this.elements[this.selectedIndex]) {
                return this.elements[this.selectedIndex];
            }
            return null;
        },
        
        init() {
            // Initialize sortable
            this.initSortable();
        },
        
        initSortable() {
            // Implementation for sortable elements
        },
        
        dragStart(event, component) {
            event.dataTransfer.setData('text/plain', JSON.stringify(component));
        },
        
        dropElement(event) {
            this.dragOver = false;
            try {
                const data = event.dataTransfer.getData('text/plain');
                const component = JSON.parse(data);
                this.addElement(component);
            } catch (e) {
                console.error('Invalid drop data', e);
            }
        },
        
        selectComponent(component) {
            this.addElement(component);
        },
        
        addElement(component) {
            this.elements.push({
                id: Date.now(),
                type: component.name,
                content: component.html || `<div class="p-4 bg-white dark:bg-gray-900 rounded-lg">${component.name}</div>`,
                bgColor: '#ffffff',
                padding: 'p-4',
                margin: 'm-0',
                customCss: ''
            });
            this.selectedIndex = this.elements.length - 1;
        },
        
        selectElement(index) {
            this.selectedIndex = index;
        },
        
        duplicateElement(index) {
            const element = {...this.elements[index]};
            element.id = Date.now();
            this.elements.splice(index + 1, 0, element);
        },
        
        deleteElement(index) {
            if (confirm('Remove this element?')) {
                this.elements.splice(index, 1);
                if (this.selectedIndex === index) {
                    this.selectedIndex = null;
                }
            }
        },
        
        applyProperties() {
            // Properties are applied automatically via binding
        },
        
        saveWebsite() {
            fetch(`{{ route('admin.website-builder.builder.save', $website ?? 0) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    elements: this.elements
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Website saved successfully!');
                }
            })
            .catch(error => console.error('Error saving website:', error));
        },
        
        previewWebsite() {
            window.open(`{{ route('admin.website-builder.preview', $website ?? 0) }}`, '_blank');
        },
        
        publishWebsite() {
            if (confirm('Publish this website?')) {
                fetch(`{{ route('admin.website-builder.publish', $website ?? 0) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Website published successfully!');
                    }
                })
                .catch(error => console.error('Error publishing website:', error));
            }
        }
    }
}
</script>
@endpush