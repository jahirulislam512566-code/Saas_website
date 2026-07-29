{{-- resources/views/components/layout/breadcrumb.blade.php --}}
@props(['items' => []])

<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-2 list-none p-0 m-0">
        <li><a href="/" class="text-[#6c5ce7] hover:underline no-underline">Home</a></li>
        @foreach($items as $item)
            <li class="flex items-center gap-2">
                <span class="text-[#4a4a4a]">/</span>
                @if($loop->last)
                    <span class="text-[#4a4a4a] font-medium" aria-current="page">{{ $item['label'] }}</span>
                @else
                    <a href="{{ $item['url'] ?? '#' }}" class="text-[#6c5ce7] hover:underline no-underline">{{ $item['label'] }}</a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>