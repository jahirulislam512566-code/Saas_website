<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordWithTokenAction
{
    /**
     * Reset password using Laravel's Password Broker
     *
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function execute(array $data): string
    {
        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

                // Revoke all tokens
                $user->tokens()->delete();

                Log::info('Password reset via broker', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => request()->ip()
                ]);
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new \Exception(trans($status));
        }

        return $status;
    }
}