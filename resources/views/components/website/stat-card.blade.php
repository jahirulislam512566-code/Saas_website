{{-- resources/views/components/website/stat-card.blade.php --}}
@props([
    'number' => '0',
    'label' => 'Stat Label',
    'icon' => null,
    'change' => null,
    'changeType' => 'neutral'
])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    @if($icon)
        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 mb-3">
            <i class="fas {{ $icon }}"></i>
        </div>
    @endif
    <p class="text-3xl font-bold text-gray-900">{{ $number }}</p>
    <p class="text-sm text-gray-600 mt-1">{{ $label }}</p>
    @if($change)
        <p class="text-xs mt-2 {{ $changeType === 'positive' ? 'text-green-600' : ($changeType === 'negative' ? 'text-red-600' : 'text-gray-500') }}">
            {{ $change }}
        </p>
    @endif
</div>