<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateMiddleware
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
        // Check if user is being impersonated
        if (session()->has('impersonate')) {
            $originalUserId = session('impersonate');
            $user = Auth::user();

            // Store impersonation info in view composer
            view()->share('isImpersonating', true);
            view()->share('impersonatorName', $user?->name);
            
            // Add impersonation notice
            $request->attributes->set('is_impersonating', true);
        }

        return $next($request);
    }
}