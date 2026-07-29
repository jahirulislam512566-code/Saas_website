<!-- admin/websites/edit.blade.php -->
@extends('layout.app')

@section('title', 'Edit Website')
@section('page-title', 'Edit Website')
@section('page-subtitle', 'Update website details and configuration')

@section('content')
<div x-data="websiteForm()" x-init="init()">
    <form @submit.prevent="submitForm()" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website Name *</label>
                                <input type="text" x-model="form.name" required
                                       class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Domain *</label>
                                <input type="text" x-model="form.domain" required
                                       placeholder="example.com"
                                       class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                <textarea x-model="form.description" rows="3"
                                          class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer *</label>
                                <select x-model="form.customer_id" required
                                        class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Select customer...</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">SEO Settings</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Title</label>
                                <input type="text" x-model="form.meta_title"
                                       class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    <span x-text="form.meta_title?.length || 0"></span>/60 characters
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Description</label>
                                <textarea x-model="form.meta_description" rows="2"
                                          class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    <span x-text="form.meta_description?.length || 0"></span>/160 characters
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keywords</label>
                                <input type="text" x-model="form.keywords"
                                       placeholder="keyword1, keyword2, keyword3"
                                       class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Comma separated keywords</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Design Settings -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Design Settings</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Template</label>
                                <select x-model="form.template"
                                        class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="default">Default</option>
                                    <option value="modern">Modern</option>
                                    <option value="minimal">Minimal</option>
                                    <option value="corporate">Corporate</option>
                                    <option value="creative">Creative</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Color Scheme</label>
                                <div class="flex gap-3">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" x-model="form.color_scheme" value="light">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Light</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" x-model="form.color_scheme" value="dark">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Dark</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" x-model="form.color_scheme" value="auto">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Auto</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Color</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" x-model="form.primary_color"
                                           class="w-12 h-10 rounded border border-gray-300 dark:border-gray-700 cursor-pointer">
                                    <input type="text" x-model="form.primary_color"
                                           class="flex-1 px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Status & Settings</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select x-model="form.status"
                                        class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                    <option value="archived">Archived</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Visibility</label>
                                <select x-model="form.visibility"
                                        class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="public">Public</option>
                                    <option value="private">Private</option>
                                    <option value="password">Password Protected</option>
                                </select>
                            </div>

                            <div x-show="form.visibility === 'password'">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                                <input type="text" x-model="form.password"
                                       class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" x-model="form.is_featured">
                                Feature this website
                            </label>

                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" x-model="form.allow_indexing">
                                Allow search engine indexing
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Screenshot -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Screenshot</h4>
                        
                        <div class="flex flex-col items-center">
                            <div class="relative w-full">
                                <img id="screenshotPreview" 
                                     src="{{ $website->screenshot ?? 'https://via.placeholder.com/400x200?text=No+Screenshot' }}" 
                                     alt="Screenshot" 
                                     class="w-full h-40 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                                <label for="screenshot" 
                                       class="absolute bottom-2 right-2 p-1.5 bg-indigo-600 hover:bg-indigo-700 rounded-lg cursor-pointer transition-colors">
                                    <i class="fas fa-camera text-white text-xs"></i>
                                    <input type="file" id="screenshot" x-model="form.screenshot" accept="image/*" 
                                           class="hidden" @change="previewScreenshot($event)">
                                </label>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">PNG, JPG up to 5MB</p>
                            <button type="button" @click="generateScreenshot()" 
                                    class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                <i class="fas fa-sync mr-1"></i> Auto-generate
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="space-y-2">
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all">
                                <i class="fas fa-save mr-1.5"></i>Update Website
                            </button>
                            <a href="{{ route('admin.websites.show', $website) }}" 
                               class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <i class="fas fa-eye mr-1.5"></i>View Website
                            </a>
                            <a href="{{ route('admin.websites.index') }}" 
                               class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <i class="fas fa-arrow-left mr-1.5"></i>Back to Websites
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function websiteForm() {
    return {
        form: @json($website->toArray()),
        screenshotFile: null,
        
        previewScreenshot(event) {
            const file = event.target.files[0];
            if (file) {
                this.screenshotFile = file;
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('screenshotPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        generateScreenshot() {
            alert('Screenshot generation feature coming soon!');
        },
        
        submitForm() {
            const formData = new FormData();
            Object.entries(this.form).forEach(([key, value]) => {
                if (value !== null && value !== '') {
                    formData.append(key, value);
                }
            });
            formData.append('_method', 'PUT');
            
            if (this.screenshotFile) {
                formData.append('screenshot', this.screenshotFile);
            }
            
            fetch(`{{ route('admin.websites.update', $website) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = `{{ route('admin.websites.show', $website) }}`;
                } else {
                    alert(data.error || 'Error updating website');
                }
            })
            .catch(error => console.error('Error updating website:', error));
        }
    }
}
</script>
@endpush