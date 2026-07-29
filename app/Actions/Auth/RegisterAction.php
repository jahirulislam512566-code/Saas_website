<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterAction
{
    /**
     * Register a new user
     *
     * @param array $data
     * @return User
     */
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'email_verified_at' => config('auth.require_verification') ? null : now(),
            ]);

            // Assign default role if exists
            if (config('auth.default_role')) {
                $user->assignRole(config('auth.default_role'));
            }

            Log::info('New user registered', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return $user;
        });
    }
}