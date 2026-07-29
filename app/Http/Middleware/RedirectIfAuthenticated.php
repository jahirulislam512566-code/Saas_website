<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // If user is admin, redirect to admin dashboard
                if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin')) {
                    return redirect()->route('admin.dashboard');
                }
                
                // All other users go to homepage
                return redirect('/');
            }
        }

        return $next($request);
    }
}