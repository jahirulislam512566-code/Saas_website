{{-- resources/views/components/website/pricing-card.blade.php --}}
@props([
    'name' => 'Plan Name',
    'price' => '$0',
    'period' => '/month',
    'description' => 'Plan description',
    'features' => [],
    'cta_text' => 'Get Started',
    'cta_link' => '#',
    'popular' => false,
])

<div {{ $attributes->merge(['class' => 'relative bg-white rounded-2xl shadow-sm hover:shadow-xl border transition-all duration-300 ' . ($popular ? 'border-indigo-500 ring-4 ring-indigo-100' : 'border-gray-100')]) }}>
    @if($popular)
        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
            <span class="px-4 py-1 text-xs font-semibold text-white bg-indigo-600 rounded-full">
                Most Popular
            </span>
        </div>
    @endif
    
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900">{{ $name }}</h3>
        <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
        
        <div class="mt-4 flex items-baseline">
            <span class="text-4xl font-extrabold text-gray-900">{{ $price }}</span>
            <span class="ml-1 text-gray-500">{{ $period }}</span>
        </div>
        
        <ul class="mt-6 space-y-3">
            @foreach($features as $feature)
                <li class="flex items-start gap-2 text-sm text-gray-600">
                    <i class="fas fa-check-circle text-indigo-500 mt-0.5"></i>
                    <span>{{ $feature }}</span>
                </li>
            @endforeach
        </ul>
        
        <div class="mt-8">
            @if(Route::has($cta_link) || filter_var($cta_link, FILTER_VALIDATE_URL))
                <a href="{{ $cta_link }}" class="block w-full text-center px-4 py-2.5 text-sm font-medium text-white {{ $popular ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-indigo-600 hover:bg-indigo-700' }} rounded-lg shadow-sm hover:shadow transition">
                    {{ $cta_text }}
                </a>
            @else
                <a href="#" class="block w-full text-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm hover:shadow transition">
                    {{ $cta_text }}
                </a>
            @endif
        </div>
    </div>
</div>