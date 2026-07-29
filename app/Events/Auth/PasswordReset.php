<?php

namespace App\Events\Auth;

use App\Models\User;
use App\Events\Event;

class PasswordReset extends Event
{
    public User $user;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    public function eventName(): string
    {
        return 'auth.password_reset';
    }

    public function payload(): array
    {
        return [
            'user_id' => $this->user->id,
            'user_email' => $this->user->email,
            'reset_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}