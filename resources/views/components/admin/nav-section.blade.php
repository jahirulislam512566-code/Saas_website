@props(['title'])

<div class="pt-4 pb-2">
    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
        {{ $title }}
    </p>
    <div class="mt-1 space-y-1">
        {{ $slot }}
    </div>
</div>