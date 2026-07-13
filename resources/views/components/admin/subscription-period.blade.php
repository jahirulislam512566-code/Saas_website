@props(['subscription'])

<div class="text-sm">
    <div class="text-gray-900">
        {{ $subscription->current_period_start?->format('M d, Y') }}
    </div>
    <div class="text-gray-500 text-xs">
        to {{ $subscription->current_period_end?->format('M d, Y') }}
    </div>
    @if($subscription->current_period_end)
        @php
            $daysLeft = now()->diffInDays($subscription->current_period_end, false);
        @endphp
        @if($daysLeft < 0)
            <span class="text-xs text-red-600 font-medium">(Expired)</span>
        @elseif($daysLeft <= 7)
            <span class="text-xs text-orange-600 font-medium">({{ $daysLeft }} days left)</span>
        @else
            <span class="text-xs text-gray-500">({{ $daysLeft }} days left)</span>
        @endif
    @endif
</div>