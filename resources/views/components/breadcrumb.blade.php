@props([
    'items' => [],
    'separator' => '/',
    'home' => true,
    'homeLabel' => 'Home',
    'homeRoute' => 'home',
])

<nav class="flex" aria-label="Breadcrumb" {{ $attributes }}>
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @if($home)
            <li class="inline-flex items-center">
                <a href="{{ route($homeRoute) }}" class="text-gray-700 hover:text-primary-600 transition-colors">
                    <i class="fas fa-home"></i>
                    <span class="sr-only">{{ $homeLabel }}</span>
                </a>
            </li>
            
            @if(count($items) > 0)
                <li>
                    <div class="flex items-center">
                        <span class="text-gray-400 mx-2">{{ $separator }}</span>
                    </div>
                </li>
            @endif
        @endif
        
        @foreach($items as $item)
            <li>
                <div class="flex items-center">
                    @if(!$loop->last && isset($item['url']))
                        <a href="{{ $item['url'] }}" class="text-gray-700 hover:text-primary-600 transition-colors">
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="text-gray-500">{{ $item['label'] }}</span>
                    @endif
                    
                    @if(!$loop->last)
                        <span class="text-gray-400 mx-2">{{ $separator }}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>