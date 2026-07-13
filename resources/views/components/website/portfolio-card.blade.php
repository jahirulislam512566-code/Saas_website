{{-- resources/views/components/website/portfolio-card.blade.php --}}
@props(['project'])

<div {{ $attributes->merge(['class' => 'group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100']) }}>
    <!-- Image -->
    <div class="relative h-56 overflow-hidden bg-gradient-to-br from-indigo-100 to-purple-100">
        @if($project->image)
            <img src="{{ Storage::url($project->image) }}" 
                 alt="{{ $project->title }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <i class="fas fa-image text-5xl text-indigo-300"></i>
            </div>
        @endif
        
        <!-- Category Badge -->
        @if($project->category)
            <span class="absolute top-3 left-3 px-3 py-1 text-xs font-medium text-white bg-indigo-600 rounded-full">
                {{ $project->category->name }}
            </span>
        @endif
        
        <!-- Overlay on hover -->
        <div class="absolute inset-0 bg-indigo-600/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
            <a href="{{ route('website.portfolio.show', $project->slug) }}" 
               class="px-6 py-2 bg-white text-indigo-600 font-medium rounded-lg hover:bg-gray-50 transition">
                View Project
            </a>
        </div>
    </div>
    
    <!-- Content -->
    <div class="p-5">
        <a href="{{ route('website.portfolio.show', $project->slug) }}" class="block group-hover:text-indigo-600 transition">
            <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $project->title }}</h3>
        </a>
        <p class="mt-2 text-sm text-gray-600 line-clamp-2">
            {{ $project->short_description ?? Str::limit(strip_tags($project->description), 100) }}
        </p>
        
        <!-- Tags -->
        @if($project->tags->isNotEmpty())
            <div class="mt-3 flex flex-wrap gap-1">
                @foreach($project->tags->take(3) as $tag)
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $tag->color_class ?? 'bg-indigo-100 text-indigo-800' }}">
                        {{ $tag->name }}
                    </span>
                @endforeach
                @if($project->tags->count() > 3)
                    <span class="px-2 py-0.5 text-xs font-medium text-gray-500">
                        +{{ $project->tags->count() - 3 }}
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>