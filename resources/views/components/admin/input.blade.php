@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
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
    
    <input type="{{ $type }}" 
           name="{{ $name }}" 
           id="{{ $name }}"
           placeholder="{{ $placeholder }}"
           value="{{ old($name, $value) }}"
           {{ $required ? 'required' : '' }}
           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 {{ $error ? 'border-red-500' : '' }}"
           {{ $attributes }}
    >
    
    @if($error)
        <p class="text-sm text-red-600">{{ $errors->first($name) }}</p>
    @endif
    
    @if($help && !$error)
        <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>