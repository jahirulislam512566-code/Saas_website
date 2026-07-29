{{-- resources/views/components/website/docs-on-this-page.blade.php --}}
@props(['items' => []])

@if(count($items) > 0)
    <div class="border-l-2 border-primary-200 pl-4">
        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">On This Page</h4>
        <ul class="mt-2 space-y-1">
            @foreach($items as $item)
                <li>
                    <a href="#{{ Str::slug($item) }}" class="text-sm text-gray-600 hover:text-primary-600 transition-colors block py-0.5">
                        {{ $item }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif