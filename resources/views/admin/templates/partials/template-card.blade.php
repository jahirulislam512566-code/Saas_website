@props(['template'])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group">
    <!-- Template Image -->
    <div class="relative h-48 bg-gray-100 overflow-hidden">
        @if($template->preview_image)
            <img src="{{ asset('storage/' . $template->preview_image) }}" 
                 alt="{{ $template->name }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400">
                <i class="fas fa-image text-5xl"></i>
            </div>
        @endif
        
        <!-- Badges -->
        <div class="absolute top-2 left-2 flex flex-col space-y-1">
            @if(!$template->is_free)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <i class="fas fa-crown mr-1"></i> Premium
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-gift mr-1"></i> Free
                </span>
            @endif
            
            @if($template->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-check mr-1"></i> Active
                </span>
            @endif
        </div>
        
        <!-- Hover Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
            <a href="{{ route('admin.templates.preview', $template) }}" class="px-4 py-2 bg-white text-gray-900 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-eye mr-2"></i> Preview
            </a>
        </div>
    </div>
    
    <!-- Template Info -->
    <div class="p-4">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                    {{ $template->name }}
                </h3>
                <p class="text-xs text-gray-500">{{ $template->category ?? 'Uncategorized' }}</p>
            </div>
            <span class="text-xs text-gray-500">v{{ $template->version ?? '1.0.0' }}</span>
        </div>
        
        <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $template->description }}</p>
        
        <!-- Stats -->
        <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
            <div class="flex items-center space-x-4">
                <span>
                    <i class="fas fa-download mr-1"></i> {{ number_format($template->downloads ?? 0) }}
                </span>
                <span>
                    <i class="fas fa-star text-yellow-400 mr-1"></i> {{ number_format($template->rating ?? 0, 1) }}
                </span>
            </div>
            
            @if(!$template->is_free && $template->price)
                <span class="font-medium text-gray-900">${{ number_format($template->price, 2) }}</span>
            @endif
        </div>
    </div>
</div>