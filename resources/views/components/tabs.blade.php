@props([
    'tabs' => [],
    'active' => 0,
])

<div x-data="{ active: @json($active) }">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            @foreach($tabs as $index => $tab)
                <button @click="active = {{ $index }}"
                        :class="active === {{ $index }} ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    @if(isset($tab['icon']))
                        <i class="fas fa-{{ $tab['icon'] }} mr-2"></i>
                    @endif
                    {{ $tab['label'] }}
                    
                    @if(isset($tab['badge']))
                        <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-gray-200 text-gray-600">
                            {{ $tab['badge'] }}
                        </span>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>
    
    <div class="mt-4">
        @foreach($tabs as $index => $tab)
            <div x-show="active === {{ $index }}" x-cloak>
                {{ $tab['content'] ?? '' }}
            </div>
        @endforeach
    </div>
</div>