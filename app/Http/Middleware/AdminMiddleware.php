<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Use admin guard
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access the admin panel.');
        }

        $user = Auth::guard('admin')->user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Your account has been deactivated.');
        }

        // Optional: Role/Permission check
        $hasAdminAccess = $user->roles->contains(fn($role) => 
            in_array($role->slug ?? $role->name, ['admin', 'super-admin'])
        ) || $user->hasPermission('access-admin-panel');

        if (!$hasAdminAccess) {
            abort(403, 'You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
}