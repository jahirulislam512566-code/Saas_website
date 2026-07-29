@props([
    'id' => 1,
    'name' => 'Template Name',
    'category' => 'Category',
    'description' => 'Template description goes here.',
    'image' => null,
    'pages' => 6,
    'colors' => [],
    'popular' => false,
    'price' => 'Free',
    'features' => [],
])

<div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden hover:-translate-y-2">
    {{-- Thumbnail --}}
    <div class="relative overflow-hidden">
        <img src="{{ $image }}" 
             alt="{{ $name }}" 
             class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-700">
        
        {{-- Hover Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
            <div class="p-6 w-full">
                <div class="flex gap-3">
                    <button @click="showPreview = true" 
                            class="flex-1 px-4 py-2.5 bg-white text-gray-900 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Preview
                        </span>
                    </button>
                    <button onclick="useTemplate({{ $id }})" 
                            class="flex-1 px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Use Template
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Badges --}}
        <div class="absolute top-4 left-4 flex gap-2">
            @if($popular)
                <span class="px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Popular
                </span>
            @endif
            @if($price === 'Free')
                <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">
                    Free
                </span>
            @endif
        </div>

        {{-- Price --}}
        <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-sm font-bold rounded-lg shadow-lg">
            {{ $price }}
        </div>
    </div>

    {{-- Content --}}
    <div class="p-6">
        <div class="flex items-start justify-between mb-2">
            <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                {{ $name }}
            </h3>
            <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
                {{ $pages }} pages
            </span>
        </div>
        
        <p class="text-gray-600 text-sm leading-relaxed mb-4">
            {{ $description }}
        </p>

        @if(!empty($colors))
            <div class="flex items-center gap-2 mb-4">
                <span class="text-xs text-gray-400">Colors:</span>
                <div class="flex gap-1.5">
                    @foreach($colors as $color)
                        <div class="w-5 h-5 rounded-full border border-gray-200" style="background: {{ $color }}"></div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(!empty($features))
            <div class="flex flex-wrap gap-2">
                @foreach($features as $feature)
                    <span class="px-2 py-1 bg-indigo-50 text-indigo-600 text-xs font-medium rounded-full">
                        {{ $feature }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>
</div>