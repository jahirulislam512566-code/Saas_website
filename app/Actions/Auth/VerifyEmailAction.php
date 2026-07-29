<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class VerifyEmailAction
{
    /**
     * Send email verification link
     *
     * @param User $user
     * @return bool
     */
    public function sendVerification(User $user): bool
    {
        // Check if user already verified
        if ($user->hasVerifiedEmail()) {
            Log::info('Email verification attempted for already verified user', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            return true;
        }

        // Generate verification URL
        $verificationUrl = $this->generateVerificationUrl($user);

        // Send verification email
        Mail::send('emails.verify-email', [
            'user' => $user,
            'verificationUrl' => $verificationUrl
        ], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Verify Your Email Address')
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });

        Log::info('Email verification link sent', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip()
        ]);

        return true;
    }

    /**
     * Verify user's email
     *
     * @param string $id
     * @param string $hash
     * @return User
     * @throws \Exception
     */
    public function verify(string $id, string $hash): User
    {
        return DB::transaction(function () use ($id, $hash) {
            $user = User::findOrFail($id);

            // Check if already verified
            if ($user->hasVerifiedEmail()) {
                Log::info('Email already verified', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                return $user;
            }

            // Verify hash matches
            if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
                Log::warning('Invalid email verification hash', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => request()->ip()
                ]);
                throw new \Exception('Invalid verification link.');
            }

            // Mark email as verified
            $user->markEmailAsVerified();

            Log::info('Email verified successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip()
            ]);

            return $user;
        });
    }

    /**
     * Resend verification email
     *
     * @param User $user
     * @return bool
     */
    public function resendVerification(User $user): bool
    {
        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            Log::info('Resend verification attempted for already verified user', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            throw new \Exception('Email already verified.');
        }

        // Check rate limiting (allow resend after 5 minutes)
        $lastSent = $user->email_verification_sent_at ?? null;
        if ($lastSent && \Carbon\Carbon::parse($lastSent)->diffInMinutes(now()) < 5) {
            throw new \Exception('Please wait 5 minutes before requesting a new verification link.');
        }

        // Update last sent timestamp
        $user->update([
            'email_verification_sent_at' => now()
        ]);

        // Send verification
        return $this->sendVerification($user);
    }

    /**
     * Generate verification URL
     *
     * @param User $user
     * @return string
     */
    private function generateVerificationUrl(User $user): string
    {
        $frontendUrl = config('app.frontend_url', config('app.url'));
        $id = $user->getKey();
        $hash = sha1($user->getEmailForVerification());

        // Create signed URL
        $signedUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $id,
                'hash' => $hash,
            ]
        );

        // Convert to frontend URL if using SPA
        if (config('app.frontend_url')) {
            $signedUrl = str_replace(
                config('app.url') . '/api',
                config('app.frontend_url'),
                $signedUrl
            );
        }

        return $signedUrl;
    }
}