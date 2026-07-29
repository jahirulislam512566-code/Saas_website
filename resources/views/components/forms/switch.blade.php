{{-- resources/views/components/forms/switch.blade.php --}}
@props([
    'name' => '',
    'id' => null,
    'label' => null,
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'size' => 'md',
])

@php
    $id = $id ?? $name;
    $sizes = [
        'sm' => 'w-8 h-5',
        'md' => 'w-11 h-6',
        'lg' => 'w-14 h-7',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $dotSizes = [
        'sm' => 'w-3.5 h-3.5',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ];
    $dotSize = $dotSizes[$size] ?? $dotSizes['md'];
    $checkedAttr = old($name, $checked) ? 'checked' : '';
    $errorClass = $error ? 'border-red-500' : 'border-gray-300';
    $disabledClass = $disabled ? 'opacity-60 cursor-not-allowed' : '';
    $bgClass = $checked ? 'bg-[#6c5ce7]' : 'bg-gray-200';
    $dotClass = $checked ? 'translate-x-full' : 'translate-x-0';
@endphp

<div class="w-full">
    <label for="{{ $id }}" class="flex items-start gap-3 cursor-pointer {{ $disabled ? 'cursor-not-allowed opacity-60' : '' }}">
        <div class="relative inline-block">
            <input
                type="checkbox"
                name="{{ $name }}"
                id="{{ $id }}"
                value="1"
                {{ $checkedAttr }}
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                class="sr-only peer"
                {{ $attributes->except(['class', 'name', 'id', 'value', 'required', 'disabled', 'checked']) }}
            >
            <div class="transition-all duration-300 {{ $sizeClass }} {{ $errorClass }} {{ $bgClass }} rounded-full shadow-inner peer-focus:ring-2 peer-focus:ring-offset-2 peer-focus:ring-[#6c5ce7]/20 peer-focus:ring-offset-white {{ $disabledClass }}"></div>
            <div class="transition-all duration-300 absolute top-0.5 left-0.5 {{ $dotSize }} {{ $dotClass }} bg-white rounded-full shadow-md transform"></div>
        </div>
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