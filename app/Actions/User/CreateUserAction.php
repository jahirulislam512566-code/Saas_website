<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CreateUserAction
{
    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Check if email exists
            if (User::where('email', $data['email'])->exists()) {
                throw new \Exception('Email already exists.');
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'] ?? null,
                'company' => $data['company'] ?? null,
                'job_title' => $data['job_title'] ?? null,
                'department' => $data['department'] ?? null,
                'timezone' => $data['timezone'] ?? 'UTC',
                'language' => $data['language'] ?? 'en',
                'is_active' => $data['is_active'] ?? true,
                'email_verified_at' => $data['email_verified'] ?? false ? now() : null,
                'created_by' => auth()->id(),
            ]);

            // Assign roles
            if (isset($data['roles']) && is_array($data['roles'])) {
                $user->assignRole($data['roles']);
            }

            // Assign permissions
            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $user->givePermissionTo($data['permissions']);
            }

            Log::info('User created', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $data['roles'] ?? [],
                'created_by' => auth()->id()
            ]);

            return $user->load(['roles', 'permissions']);
        });
    }
}