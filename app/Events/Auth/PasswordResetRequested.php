<?php

namespace App\Events\Auth;

use App\Models\User;
use App\Events\Event;

class PasswordResetRequested extends Event
{
    public User $user;
    public string $token;

    public function __construct(User $user, string $token)
    {
        parent::__construct();
        $this->user = $user;
        $this->token = $token;
    }

    public function eventName(): string
    {
        return 'auth.password_reset_requested';
    }

    public function payload(): array
    {
        return [
            'user_id' => $this->user->id,
            'user_email' => $this->user->email,
            'token' => $this->token,
            'requested_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}