<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user statistics
        $stats = [
            'websites' => $user->websites()->count(),
            'published_websites' => $this->getPublishedWebsitesCount($user),
            'total_pages' => $user->pages()->count(),
            'total_media' => $user->media()->count(),
        ];
        
        // Get recent websites
        $recentWebsites = $user->websites()
            ->latest()
            ->limit(5)
            ->get();
        
        return view('website.account.dashboard', compact('user', 'stats', 'recentWebsites'));
    }

    /**
     * Get published websites count (handles both status and is_published columns)
     */
    private function getPublishedWebsitesCount($user)
    {
        // Try with status column first
        try {
            return $user->websites()
                ->where('status', 'published')
                ->count();
        } catch (\Exception $e) {
            // If status column doesn't exist, try is_published
            try {
                return $user->websites()
                    ->where('is_published', true)
                    ->count();
            } catch (\Exception $e) {
                // If neither exists, return total websites
                return $user->websites()->count();
            }
        }
    }

    /**
     * Display the user profile page.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('website.account.profile', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'business_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    /**
     * Display the user subscriptions.
     */
    public function subscriptions()
    {
        $user = Auth::user();
        $subscriptions = $user->subscriptions()
            ->with('plan')
            ->latest()
            ->get();
        
        $currentSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->with('plan')
            ->first();
        
        return view('website.account.subscriptions', compact('subscriptions', 'currentSubscription'));
    }

    /**
     * Display the user invoices.
     */
    public function invoices()
    {
        $user = Auth::user();
        $invoices = $user->invoices()
            ->latest()
            ->get();
        
        return view('website.account.invoices', compact('invoices'));
    }

    /**
     * Display the user settings.
     */
    public function settings()
    {
        $user = Auth::user();
        return view('website.account.settings', compact('user'));
    }

    /**
     * Update the user settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'timezone' => 'nullable|string|max:50',
            'date_format' => 'nullable|string|max:20',
            'time_format' => 'nullable|string|max:20',
            'language' => 'nullable|string|max:10',
            'currency' => 'nullable|string|max:10',
            'notifications' => 'nullable|boolean',
            'newsletter' => 'nullable|boolean',
        ]);

        $settings = $user->settings ?? [];
        $settings = array_merge($settings, $validated);
        
        $user->update(['settings' => $settings]);

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}