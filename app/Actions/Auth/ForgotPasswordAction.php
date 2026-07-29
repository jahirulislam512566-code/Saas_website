<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordAction
{
    /**
     * Send password reset link to user's email
     *
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function execute(array $data): string
    {
        $email = $data['email'];

        // Check if user exists
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            Log::warning('Password reset requested for non-existent email', [
                'email' => $email,
                'ip' => request()->ip()
            ]);
            throw new \Exception('We could not find a user with that email address.');
        }

        // Generate reset token
        $token = $this->createResetToken($user);

        // Send reset email
        $this->sendResetEmail($user, $token);

        Log::info('Password reset link sent', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip()
        ]);

        return $token;
    }

    /**
     * Create password reset token
     *
     * @param User $user
     * @return string
     */
    private function createResetToken(User $user): string
    {
        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => hash('sha256', $token),
                'created_at' => now()
            ]
        );

        return $token;
    }

    /**
     * Send password reset email
     *
     * @param User $user
     * @param string $token
     * @return void
     */
    private function sendResetEmail(User $user, string $token): void
    {
        $frontendUrl = config('app.frontend_url', config('app.url'));
        $resetUrl = "{$frontendUrl}/reset-password?token={$token}&email=" . urlencode($user->email);

        Mail::send('emails.password-reset', [
            'user' => $user,
            'resetUrl' => $resetUrl,
            'token' => $token
        ], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Reset Your Password')
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });
    }
}