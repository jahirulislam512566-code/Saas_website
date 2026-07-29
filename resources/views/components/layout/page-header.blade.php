{{-- resources/views/components/layout/page-header.blade.php --}}
@props(['title' => 'Page Title', 'subtitle' => null, 'actions' => null, 'breadcrumb' => null])

<section class="pt-32 pb-16 bg-gradient-to-br from-[#f8f7ff] to-white border-b border-gray-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <h1 class="text-4xl sm:text-5xl font-bold text-[#0a0a0a] leading-tight mb-4">
                {{ $title }}
            </h1>
            @if($subtitle)
                <p class="text-xl text-[#4a4a4a] leading-relaxed mb-6">{{ $subtitle }}</p>
            @endif
            @if($actions)
                <div class="flex flex-wrap gap-4 mt-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
        @if($breadcrumb)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <x-layout.breadcrumb :items="$breadcrumb" />
            </div>
        @endif
    </div>
</section>