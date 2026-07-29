@props([
    'multiple' => false,
])

<div x-data="{ openItems: [] }" {{ $attributes }}>
    {{ $slot }}
</div>