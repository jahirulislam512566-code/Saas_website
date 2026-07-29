<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginRedirectService
{
    /**
     * Determine the redirect path after login.
     */
    public function getRedirectPath(Request $request, User $user): string
    {
        // Check if there's an intended URL (from previous middleware)
        $intended = Session::get('url.intended');

        // If intended URL exists and is not an auth page, use it
        if ($intended && !$this->isAuthPage($intended)) {
            return $intended;
        }

        // Check for role-based redirects
        if ($redirect = $this->getRoleBasedRedirect($user)) {
            return $redirect;
        }

        // Check for user-specific redirect
        if ($redirect = $this->getUserSpecificRedirect($user)) {
            return $redirect;
        }

        // Check if user is new (recently registered)
        if ($user->wasRecentlyCreated) {
            return $this->getNewUserRedirect($user);
        }

        // Check if user has verified email
        if (!$user->hasVerifiedEmail()) {
            return route('verification.notice');
        }

        // Default fallback
        return $this->getDefaultRedirect($user);
    }

    /**
     * Get role-based redirect path.
     */
    protected function getRoleBasedRedirect(User $user): ?string
    {
        // Check for admin role
        if ($user->hasRole('admin')) {
            return route('admin.dashboard');
        }

        // Check for manager role
        if ($user->hasRole('manager')) {
            return route('admin.manager.dashboard');
        }

        // Check for editor role
        if ($user->hasRole('editor')) {
            return route('editor.dashboard');
        }

        // Check for vendor role
        if ($user->hasRole('vendor')) {
            return route('vendor.dashboard');
        }

        return null;
    }

    /**
     * Get user-specific redirect path.
     */
    protected function getUserSpecificRedirect(User $user): ?string
    {
        // Check if user has a stored redirect preference
        if (isset($user->settings) && isset($user->settings['redirect_after_login'])) {
            return $user->settings['redirect_after_login'];
        }

        // Check database column if exists
        if (property_exists($user, 'redirect_after_login') && $user->redirect_after_login) {
            return $user->redirect_after_login;
        }

        return null;
    }

    /**
     * Get redirect for new users.
     */
    protected function getNewUserRedirect(User $user): string
    {
        // Redirect new users to welcome page or onboarding
        return route('welcome');
    }

    /**
     * Get default redirect path.
     */
    protected function getDefaultRedirect(User $user): string
    {
        // Get from config with fallback
        return config('auth.redirects.after_login', '/dashboard');
    }

    /**
     * Check if URL is an authentication page.
     */
    protected function isAuthPage(string $url): bool
    {
        $authRoutes = config('auth.redirects.excluded_routes', [
            'login',
            'register',
            'password',
            'verify',
            'confirm',
            'logout',
            'auth',
            '2fa',
            'email/verify',
        ]);

        foreach ($authRoutes as $route) {
            if (str_contains($url, $route)) {
                return true;
            }
        }

        return false;
    }
}