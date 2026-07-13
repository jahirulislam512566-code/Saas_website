@props([
    'name',
    'label' => null,
    'value' => null,
    'rows' => 3,
    'required' => false,
    'help' => null,
])

@php
    $error = $errors->has($name);
@endphp

<div class="space-y-1">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <textarea name="{{ $name }}" 
              id="{{ $name }}"
              rows="{{ $rows }}"
              {{ $required ? 'required' : '' }}
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 {{ $error ? 'border-red-500' : '' }}"
              {{ $attributes }}
    >{{ old($name, $value) }}</textarea>
    
    @if($error)
        <p class="text-sm text-red-600">{{ $errors->first($name) }}</p>
    @endif
    
    @if($help && !$error)
        <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>