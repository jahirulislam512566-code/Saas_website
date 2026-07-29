<div x-data="{ show: false }" x-init="window.templatePreview = (id) => { show = true; loadTemplate(id); }">
    {{-- Modal --}}
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         @click.away="show = false">
        
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl" @click.stop>
            {{-- Header --}}
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900" x-text="templateName">Template Name</h3>
                    <p class="text-gray-500 text-sm" x-text="templateDescription">Template description</p>
                </div>
                <button @click="show = false" 
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)]">
                <img x-bind:src="templateImage" 
                     alt="Template preview" 
                     class="w-full rounded-xl shadow-lg mb-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-bold text-gray-900 mb-2">Template Details</h4>
                        <ul class="space-y-2 text-sm text-gray-600" x-html="templateDetails"></ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 mb-2">Features Included</h4>
                        <div class="flex flex-wrap gap-2" x-html="templateFeatures"></div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-4 p-6 border-t border-gray-100">
                <button @click="show = false" 
                        class="px-6 py-2.5 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Close
                </button>
                <button @click="useTemplate(templateId)" 
                        class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Use This Template
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loadTemplate(id) {
        // Fetch template data from API or use pre-loaded data
        fetch(`/api/templates/${id}`)
            .then(response => response.json())
            .then(data => {
                this.templateId = data.id;
                this.templateName = data.name;
                this.templateDescription = data.description;
                this.templateImage = data.image;
                this.templateDetails = `
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        ${data.pages} pages included
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Fully responsive design
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        SEO optimized
                    </li>
                `;
                this.templateFeatures = data.features.map(f => 
                    `<span class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-sm font-medium rounded-lg">${f}</span>`
                ).join('');
            });
    }
</script>
@endpush