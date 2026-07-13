@props([
    'name',
    'label' => null,
    'checked' => false,
    'required' => false,
])

<div class="flex items-start">
    <div class="flex items-center h-5">
        <input type="checkbox" 
               name="{{ $name }}" 
               id="{{ $name }}"
               {{ $checked ? 'checked' : '' }}
               {{ $required ? 'required' : '' }}
               class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500"
               {{ $attributes }}
        >
    </div>
    
    @if($label)
        <div class="ml-3 text-sm">
            <label for="{{ $name }}" class="text-gray-700">{{ $label }}</label>
        </div>
    @endif
</div>