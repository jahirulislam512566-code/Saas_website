<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveSubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has active subscription
        $subscription = $user->getCurrentSubscription();

        if (!$subscription || !$subscription->isActive()) {
            return redirect()->route('plans')
                ->with('error', 'You need an active subscription to access this feature.');
        }

        // Check if subscription is expiring soon (within 7 days)
        if ($subscription->current_period_end && 
            $subscription->current_period_end->diffInDays(now()) <= 7) {
            session()->flash('warning', 'Your subscription will expire in ' . 
                $subscription->current_period_end->diffInDays(now()) . ' days. Please renew.');
        }

        return $next($request);
    }
}