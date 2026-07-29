{{-- resources/views/components/forms/select.blade.php --}}
@props([
    'name' => '',
    'id' => null,
    'label' => null,
    'options' => [],
    'value' => '',
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'autofocus' => false,
    'error' => null,
    'help' => null,
    'multiple' => false,
    'size' => 'md',
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
    $disabledClass = $disabled ? 'bg-gray-50 cursor-not-allowed' : '';
    $multipleAttr = $multiple ? 'multiple' : '';
    $selectedValue = old($name, $value);
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

    <select
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        id="{{ $id }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $autofocus ? 'autofocus' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        class="w-full rounded-xl border-2 {{ $errorClass }} {{ $sizeClass }} {{ $disabledClass }} transition-all duration-200 outline-none appearance-none bg-white {{ $attributes->get('class') }}"
        {{ $attributes->except(['class', 'name', 'id', 'required', 'disabled', 'autofocus', 'multiple']) }}
    >
        @if($placeholder && !$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $optionValue => $optionLabel)
            @if(is_array($optionLabel))
                <optgroup label="{{ $optionValue }}">
                    @foreach($optionLabel as $subValue => $subLabel)
                        <option value="{{ $subValue }}" {{ (is_array($selectedValue) ? in_array($subValue, $selectedValue) : $selectedValue == $subValue) ? 'selected' : '' }}>
                            {{ $subLabel }}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $optionValue }}" {{ (is_array($selectedValue) ? in_array($optionValue, $selectedValue) : $selectedValue == $optionValue) ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endif
        @endforeach
    </select>

    <div class="relative">
        @if(!$multiple)
            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-[#4a4a4a] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
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