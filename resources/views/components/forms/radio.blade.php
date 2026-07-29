{{-- resources/views/components/forms/radio.blade.php --}}
@props([
    'name' => '',
    'id' => null,
    'label' => null,
    'value' => '',
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'size' => 'md',
    'inline' => false,
])

@php
    $id = $id ?? $name . '_' . str_replace(' ', '_', $value);
    $sizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $checkedAttr = old($name, $checked) ? 'checked' : '';
    $errorClass = $error ? 'border-red-500 focus:ring-red-500/20' : 'border-gray-300 focus:ring-[#6c5ce7]/20';
    $disabledClass = $disabled ? 'bg-gray-100 cursor-not-allowed' : '';
    $inlineClass = $inline ? 'inline-flex' : 'flex';
@endphp

<div class="w-full">
    <label for="{{ $id }}" class="{{ $inlineClass }} items-start gap-3 cursor-pointer {{ $disabled ? 'cursor-not-allowed opacity-60' : '' }}">
        <input
            type="radio"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ $value }}"
            {{ $checkedAttr }}
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="{{ $sizeClass }} rounded-full border-2 {{ $errorClass }} {{ $disabledClass }} transition-all duration-200 text-[#6c5ce7] focus:ring-2 focus:ring-offset-2 focus:ring-[#6c5ce7]/20 focus:ring-offset-white {{ $attributes->get('class') }}"
            {{ $attributes->except(['class', 'name', 'id', 'value', 'required', 'disabled', 'checked']) }}
        >
        <div>
            @if($label)
                <span class="block text-sm font-medium text-[#0a0a0a]">{{ $label }}</span>
            @endif
            @if($help && !$error)
                <span class="block text-xs text-[#4a4a4a] mt-0.5">{{ $help }}</span>
            @endif
            @if($error)
                <span class="block text-xs text-red-600 mt-0.5">{{ $error }}</span>
            @endif
        </div>
    </label>
</div>