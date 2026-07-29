{{-- resources/views/components/website/docs-toc.blade.php --}}
@props(['items' => []])

@if(count($items) > 0)
    <div class="bg-gray-50 rounded-xl p-6">
        <h4 class="text-sm font-semibold text-gray-900">Table of Contents</h4>
        <ul class="mt-3 space-y-2">
            @foreach($items as $item)
                <li>
                    <a href="#{{ Str::slug($item) }}" class="text-sm text-gray-600 hover:text-primary-600 transition-colors block py-1">
                        {{ $item }}
                    </a>
                    @if(isset($item['subitems']) && count($item['subitems']) > 0)
                        <ul class="ml-4 mt-1 space-y-1">
                            @foreach($item['subitems'] as $subitem)
                                <li>
                                    <a href="#{{ Str::slug($subitem) }}" class="text-sm text-gray-500 hover:text-primary-600 transition-colors block py-1">
                                        {{ $subitem }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif