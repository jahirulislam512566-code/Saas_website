<?php

namespace App\Events\Auth;

use App\Models\User;
use App\Events\Event;

class UserRegistered extends Event
{
    public User $user;
    public array $data;

    public function __construct(User $user, array $data = [])
    {
        parent::__construct();
        $this->user = $user;
        $this->data = $data;
    }

    public function eventName(): string
    {
        return 'auth.user_registered';
    }

    public function payload(): array
    {
        return [
            'user_id' => $this->user->id,
            'user_email' => $this->user->email,
            'user_name' => $this->user->name,
            'registration_data' => $this->data,
            'registered_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}