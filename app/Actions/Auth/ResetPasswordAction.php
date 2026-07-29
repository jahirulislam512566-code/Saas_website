<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ResetPasswordAction
{
    /**
     * Reset user's password using token
     *
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $email = $data['email'];
            $token = $data['token'];
            $newPassword = $data['password'];

            // Validate token
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->first();

            if (!$resetRecord) {
                Log::warning('Password reset attempted with invalid token', [
                    'email' => $email,
                    'ip' => request()->ip()
                ]);
                throw new \Exception('Invalid password reset token.');
            }

            // Check if token is expired (default: 60 minutes)
            $expirationMinutes = config('auth.passwords.users.expire', 60);
            $tokenCreatedAt = \Carbon\Carbon::parse($resetRecord->created_at);
            
            if ($tokenCreatedAt->diffInMinutes(now()) > $expirationMinutes) {
                // Delete expired token
                DB::table('password_reset_tokens')->where('email', $email)->delete();
                
                Log::warning('Password reset token expired', [
                    'email' => $email,
                    'ip' => request()->ip()
                ]);
                throw new \Exception('Password reset token has expired. Please request a new one.');
            }

            // Verify token
            if (!hash_equals(hash('sha256', $token), $resetRecord->token)) {
                Log::warning('Password reset attempted with mismatched token', [
                    'email' => $email,
                    'ip' => request()->ip()
                ]);
                throw new \Exception('Invalid password reset token.');
            }

            // Update user password
            $user = User::where('email', $email)->firstOrFail();
            $user->update([
                'password' => Hash::make($newPassword),
                'updated_at' => now(),
            ]);

            // Delete the reset token
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            // Revoke all existing tokens (force re-login)
            $user->tokens()->delete();

            Log::info('Password reset successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip()
            ]);

            return $user;
        });
    }
}