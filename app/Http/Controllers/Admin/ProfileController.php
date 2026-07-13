<?php
// app/Http/Controllers/Admin/ProfileController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        // Update user data
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('admin.profile.edit')
                        ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the password change form.
     */
    public function password()
    {
        return view('admin.profile.password');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('admin.profile.password')
                        ->with('success', 'Password updated successfully!');
    }

    /**
     * Show the user's activity log.
     */
    public function activity()
    {
        $user = Auth::user();
        $activities = $user->activities()->latest()->paginate(20);
        
        return view('admin.profile.activity', compact('activities'));
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
            $user->save();
        }

        return redirect()->route('admin.profile.edit')
                        ->with('success', 'Avatar deleted successfully!');
    }

    /**
     * Show the user's notifications.
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(20);
        $unreadCount = $user->unreadNotifications()->count();

        return view('admin.profile.notifications', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read.
     */
    public function markNotificationRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications()->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     */
    public function deleteNotification($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted.');
    }

    /**
     * Show the two-factor authentication setup.
     */
    public function twoFactor()
    {
        $user = Auth::user();
        return view('admin.profile.two-factor', compact('user'));
    }

    /**
     * Enable two-factor authentication.
     */
    public function enableTwoFactor(Request $request)
    {
        $user = Auth::user();
        
        // Generate secret key
        $secret = $this->generateTwoFactorSecret();
        
        // Generate QR code URL
        $qrCodeUrl = $this->generateQRCodeUrl($user->email, $secret);
        
        // Store secret in session temporarily
        session(['2fa_secret' => $secret]);
        
        return view('admin.profile.two-factor-confirm', compact('qrCodeUrl', 'secret'));
    }

    /**
     * Confirm and enable two-factor authentication.
     */
    public function confirmTwoFactor(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $secret = session('2fa_secret');
        
        if (!$secret) {
            return redirect()->route('admin.profile.two-factor')
                            ->with('error', 'Two-factor setup expired. Please try again.');
        }

        // Verify the code (simplified - use a proper 2FA library in production)
        $user = Auth::user();
        $user->two_factor_secret = encrypt($secret);
        $user->two_factor_enabled = true;
        $user->save();

        session()->forget('2fa_secret');

        return redirect()->route('admin.profile.two-factor')
                        ->with('success', 'Two-factor authentication enabled successfully!');
    }

    /**
     * Disable two-factor authentication.
     */
    public function disableTwoFactor(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user->two_factor_secret = null;
        $user->two_factor_enabled = false;
        $user->two_factor_recovery_codes = null;
        $user->save();

        return redirect()->route('admin.profile.two-factor')
                        ->with('success', 'Two-factor authentication disabled.');
    }

    /**
     * Show the account deletion confirmation.
     */
    public function deleteAccount()
    {
        return view('admin.profile.delete-account');
    }

    /**
     * Delete the user's account.
     */
    public function destroyAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => ['required', 'current_password'],
            'confirmation' => ['required', 'string', 'in:DELETE'],
        ]);

        // Delete avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Perform any cleanup tasks
        // - Delete user's posts
        // - Delete user's comments
        // - etc.

        // Delete the user
        $user->delete();

        // Logout the user
        Auth::logout();

        return redirect()->route('home')->with('success', 'Your account has been deleted.');
    }

    // ========== Helper Methods ==========

    /**
     * Generate a two-factor authentication secret.
     */
    private function generateTwoFactorSecret()
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * Generate a QR code URL for two-factor authentication.
     */
    private function generateQRCodeUrl($email, $secret)
    {
        $appName = config('app.name');
        $label = "{$appName}:{$email}";
        $issuer = $appName;
        
        return "otpauth://totp/{$label}?secret={$secret}&issuer={$issuer}";
    }
}