<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is authenticated
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        // Super admin has all permissions
        if ($admin->role === 'super_admin') {
            return $next($request);
        }

        // Check if admin has required permissions
        if (!$this->hasPermission($admin, $request)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }

    /**
     * Check if admin has permission for the request.
     */
    protected function hasPermission($admin, Request $request): bool
    {
        // Super admin already handled above
        $routeName = $request->route()->getName();
        
        // Define role-based permissions
        $rolePermissions = [
            'admin' => [
                'admin.dashboard.*',
                'admin.users.*',
                'admin.roles.*',
                'admin.settings.*',
                'admin.profile.*',
                'admin.posts.*',
                'admin.categories.*',
                'admin.pages.*',
            ],
            'manager' => [
                'admin.dashboard.*',
                'admin.users.*',
                'admin.profile.*',
                'admin.posts.*',
                'admin.categories.*',
            ],
        ];

        $allowedRoutes = $rolePermissions[$admin->role] ?? [];

        foreach ($allowedRoutes as $pattern) {
            $pattern = str_replace('*', '.*', $pattern);
            if (preg_match('/^' . $pattern . '$/', $routeName)) {
                return true;
            }
        }

        return false;
    }
}