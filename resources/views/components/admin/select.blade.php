@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
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
    
    <select name="{{ $name }}" 
            id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 {{ $error ? 'border-red-500' : '' }}"
            {{ $attributes }}
    >
        <option value="">Select an option</option>
        @foreach($options as $key => $label)
            <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    
    @if($error)
        <p class="text-sm text-red-600">{{ $errors->first($name) }}</p>
    @endif
    
    @if($help && !$error)
        <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>