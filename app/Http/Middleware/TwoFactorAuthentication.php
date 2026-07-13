<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthentication
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

        // Check if 2FA is required and not verified
        if ($user && $user->two_factor_secret && !session('2fa_verified')) {
            return redirect()->route('2fa.verify')
                ->with('error', 'Please verify your two-factor authentication.');
        }

        return $next($request);
    }
}