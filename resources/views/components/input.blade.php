@props([
    'type' => 'text',
    'name' => null,
    'id' => null,
    'label' => null,
    'placeholder' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'help' => null,
    'icon' => null,
    'iconPosition' => 'left',
    'size' => 'md',
])

@php
    $id = $id ?? $name ?? uniqid('input_');
    $errorName = $name ? str_replace(['[', ']'], ['.', ''], $name) : $id;
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-4 py-3 text-base',
    ];
    
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $errorClass = $error ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-primary-500 focus:border-primary-500';
    $disabledClass = ($disabled || $readonly) ? 'bg-gray-100 cursor-not-allowed' : 'bg-white';
    $iconPadding = $icon ? ($iconPosition === 'left' ? 'pl-10' : 'pr-10') : '';
    
    $classes = trim("block w-full rounded-lg shadow-sm {$sizeClasses} {$errorClass} {$disabledClass} {$iconPadding} transition duration-150 ease-in-out");
    
    $hasError = $error || (isset($errors) && $errors->has($errorName));
    $errorMessage = $error ?? (isset($errors) ? $errors->first($errorName) : null);
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon && $iconPosition === 'left')
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-{{ $icon }} text-gray-400"></i>
            </div>
        @endif
        
        <input type="{{ $type }}"
               id="{{ $id }}"
               name="{{ $name }}"
               value="{{ old($errorName, $value) }}"
               placeholder="{{ $placeholder }}"
               {{ $required ? 'required' : '' }}
               {{ $disabled ? 'disabled' : '' }}
               {{ $readonly ? 'readonly' : '' }}
               class="{{ $classes }}"
               {{ $attributes }}
        >
        
        @if($icon && $iconPosition === 'right')
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class="fas fa-{{ $icon }} text-gray-400"></i>
            </div>
        @endif
        
        @if($hasError)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif
    </div>
    
    @if($help && !$hasError)
        <p class="mt-1 text-sm text-gray-500">{{ $help }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-1 text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div>