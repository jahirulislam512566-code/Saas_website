<div x-data="templatePreviewModal()" 
     x-init="window.addEventListener('preview-template', (e) => openPreview(e.detail.id))"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
     x-show="isOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     @click.away="isOpen = false"
     style="display: none;">
    
    <div class="bg-white rounded-2xl max-w-5xl w-full max-h-[90vh] overflow-hidden shadow-2xl" @click.stop>
        {{-- Modal Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div>
                <h3 class="text-2xl font-bold text-gray-900" x-text="template.name">Template Name</h3>
                <p class="text-gray-500 text-sm" x-text="template.category">Category</p>
            </div>
            <button @click="isOpen = false" 
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Modal Content --}}
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)]">
            {{-- Image --}}
            <img :src="template.image" 
                 :alt="template.name" 
                 class="w-full rounded-xl shadow-lg mb-6">
            
            {{-- Details Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-bold text-gray-900 mb-2">Template Details</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span x-text="template.pages + ' pages included'"></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Fully responsive design</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>SEO optimized</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span x-text="template.price === 'Free' ? 'Free to use' : 'Premium quality'"></span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-2">Features Included</h4>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="feature in template.features" :key="feature">
                            <span class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-sm font-medium rounded-lg" x-text="feature"></span>
                        </template>
                    </div>
                    
                    <div class="mt-4" x-show="template.colors && template.colors.length > 0">
                        <h4 class="font-bold text-gray-900 mb-2">Color Palette</h4>
                        <div class="flex gap-2">
                            <template x-for="color in template.colors" :key="color">
                                <div class="w-10 h-10 rounded-full border-2 border-gray-200" :style="'background: ' + color"></div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reviews --}}
            <div class="mt-6 pt-6 border-t border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1">
                        <template x-for="i in 5" :key="i">
                            <svg class="w-5 h-5" :class="i <= template.rating ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </template>
                    </div>
                    <span class="text-sm text-gray-600" x-text="template.reviews + ' reviews'"></span>
                    <span class="text-sm text-gray-500">•</span>
                    <span class="text-sm text-gray-600" x-text="template.used_by + ' users'"></span>
                </div>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="flex items-center justify-end gap-4 p-6 border-t border-gray-100">
            <button @click="isOpen = false" 
                    class="px-6 py-2.5 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Close
            </button>
            <button @click="useTemplate(template.id)" 
                    class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Use This Template
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function templatePreviewModal() {
        return {
            isOpen: false,
            template: {
                id: null,
                name: '',
                category: '',
                description: '',
                image: '',
                pages: 0,
                price: '',
                features: [],
                colors: [],
                rating: 0,
                reviews: 0,
                used_by: 0,
                popular: false,
                is_free: false,
                is_new: false
            },

            openPreview(id) {
                // Find template from the page data
                const templates = window.templatesData || [];
                const found = templates.find(t => t.id === id);
                
                if (found) {
                    this.template = found;
                    this.isOpen = true;
                    document.body.style.overflow = 'hidden';
                } else {
                    // Fallback: fetch from API
                    fetch(`/api/templates/${id}`)
                        .then(response => response.json())
                        .then(data => {
                            this.template = data;
                            this.isOpen = true;
                            document.body.style.overflow = 'hidden';
                        })
                        .catch(error => {
                            console.error('Error loading template:', error);
                        });
                }
            },

            useTemplate(id) {
                @auth
                    // Use direct URL construction - NO route helper
                    window.location.href = '/templates/' + id + '/use';
                @else
                    window.location.href = "{{ route('register') }}?template=" + id;
                @endauth
            },

            close() {
                this.isOpen = false;
                document.body.style.overflow = '';
            }
        }
    }
</script>
@endpush