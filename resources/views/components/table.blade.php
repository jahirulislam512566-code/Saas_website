@props([
    'headers' => [],
    'striped' => true,
    'hoverable' => true,
    'bordered' => false,
    'compact' => false,
    'responsive' => true,
])

@php
    $stripedClass = $striped ? 'even:bg-gray-50' : '';
    $hoverClass = $hoverable ? 'hover:bg-gray-100' : '';
    $borderClass = $bordered ? 'border border-gray-200' : '';
    $paddingClass = $compact ? 'px-3 py-2' : 'px-6 py-4';
    
    $classes = trim("min-w-full divide-y divide-gray-200");
@endphp

<div class="{{ $responsive ? 'overflow-x-auto shadow-md rounded-lg' : '' }}">
    <table class="{{ $classes }}" {{ $attributes }}>
        @if(!empty($headers))
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $header)
                        <th scope="col" class="{{ $paddingClass }} text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ $loop->first ? 'rounded-tl-lg' : '' }} {{ $loop->last ? 'rounded-tr-lg' : '' }}">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        
        <tbody class="bg-white divide-y divide-gray-200">
            {{ $slot }}
        </tbody>
    </table>
</div>