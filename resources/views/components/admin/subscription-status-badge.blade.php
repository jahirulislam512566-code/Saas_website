@props(['status'])

@php
    $statusColors = [
        'active' => 'bg-green-100 text-green-800',
        'trialing' => 'bg-blue-100 text-blue-800',
        'past_due' => 'bg-orange-100 text-orange-800',
        'canceled' => 'bg-red-100 text-red-800',
        'unpaid' => 'bg-red-100 text-red-800',
        'incomplete' => 'bg-gray-100 text-gray-800',
    ];
    $color = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
    $label = ucfirst(str_replace('_', ' ', $status));
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
    {{ $label }}
</span>