{{-- resources/views/components/forms/input.blade.php --}}
@props([
    'name' => '',
    'id' => null,
    'label' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'error' => null,
    'help' => null,
    'icon' => null,
    'iconPosition' => 'left',
    'size' => 'md',
    'prepend' => null,
    'append' => null,
])

@php
    $id = $id ?? $name;
    $sizes = [
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-3 text-base',
        'lg' => 'px-5 py-4 text-lg',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $errorClass = $error ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20' : 'border-gray-200 focus:border-[#6c5ce7] focus:ring-[#6c5ce7]/20';
    $iconPadding = $icon ? ($iconPosition === 'left' ? 'pl-10' : 'pr-10') : '';
    $disabledClass = $disabled || $readonly ? 'bg-gray-50 cursor-not-allowed' : '';
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($prepend)
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#4a4a4a]">{{ $prepend }}</span>
        @endif

        @if($icon && $iconPosition === 'left')
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#4a4a4a]">{{ $icon }}</span>
        @endif

        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $autofocus ? 'autofocus' : '' }}
            class="w-full rounded-xl border-2 {{ $errorClass }} {{ $sizeClass }} {{ $iconPadding }} {{ $disabledClass }} transition-all duration-200 outline-none {{ $attributes->get('class') }}"
            {{ $attributes->except(['class', 'type', 'name', 'id', 'value', 'placeholder', 'required', 'disabled', 'readonly', 'autofocus']) }}
        >

        @if($icon && $iconPosition === 'right')
            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-[#4a4a4a]">{{ $icon }}</span>
        @endif

        @if($append)
            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-[#4a4a4a]">{{ $append }}</span>
        @endif
    </div>

    @if($error)
        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
            <span>⚠️</span> {{ $error }}
        </p>
    @endif

    @if($help && !$error)
        <p class="mt-1.5 text-sm text-[#4a4a4a]">{{ $help }}</p>
    @endif
</div>