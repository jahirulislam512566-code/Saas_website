{{-- resources/views/components/admin/avatar.blade.php --}}
@props([
    'src' => null,
    'name' => null,
    'size' => 'md',
    'fallback' => true
])

@php
    $sizes = [
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-10 h-10 text-base',
        'lg' => 'w-12 h-12 text-lg',
        'xl' => 'w-14 h-14 text-xl',
    ];
    
    $sizeClass = $sizes[$size] ?? 'w-10 h-10 text-base';
    
    // Get initials from name
    $initials = '';
    if ($name) {
        $parts = explode(' ', $name);
        $initials = substr($parts[0], 0, 1);
        if (isset($parts[1])) {
            $initials .= substr($parts[1], 0, 1);
        }
        $initials = strtoupper($initials);
    }
@endphp

<div class="avatar {{ $sizeClass }} rounded-full overflow-hidden bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold {{ $attributes->get('class') }}">
    @if($src)
        <img src="{{ $src }}" alt="{{ $name ?? 'Avatar' }}" class="w-full h-full object-cover">
    @elseif($fallback && $initials)
        <span>{{ $initials }}</span>
    @else
        <i class="fas fa-user"></i>
    @endif
</div>

@push('styles')
<style>
.avatar {
    flex-shrink: 0;
}
.avatar img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
@endpush