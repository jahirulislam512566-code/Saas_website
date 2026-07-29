<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class LogoutAction
{
    /**
     * Logout user and revoke tokens
     *
     * @param User $user
     * @return bool
     */
    public function execute(User $user): bool
    {
        // Revoke all tokens for the user
        $user->tokens()->delete();

        Log::info('User logged out', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return true;
    }
}