@props(['status'])

@php
    $variants = [
        'active' => 'bg-green-100 text-green-800',
        'inactive' => 'bg-gray-100 text-gray-800',
        'pending' => 'bg-yellow-100 text-yellow-800',
        'canceled' => 'bg-red-100 text-red-800',
        'trialing' => 'bg-blue-100 text-blue-800',
        'past_due' => 'bg-orange-100 text-orange-800',
        'unpaid' => 'bg-red-100 text-red-800',
        'open' => 'bg-blue-100 text-blue-800',
        'in_progress' => 'bg-yellow-100 text-yellow-800',
        'resolved' => 'bg-green-100 text-green-800',
        'closed' => 'bg-gray-100 text-gray-800',
        'published' => 'bg-green-100 text-green-800',
        'draft' => 'bg-gray-100 text-gray-800',
        'archived' => 'bg-red-100 text-red-800',
    ];
    
    $color = $variants[$status] ?? 'bg-gray-100 text-gray-800';
    $label = ucfirst(str_replace('_', ' ', $status));
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
    {{ $label }}
</span>