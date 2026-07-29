<?php

namespace App\Events\Auth;

use App\Models\User;
use App\Events\Event;

class UserLoggedIn extends Event
{
    public User $user;
    public array $session;

    public function __construct(User $user, array $session = [])
    {
        parent::__construct();
        $this->user = $user;
        $this->session = $session;
    }

    public function eventName(): string
    {
        return 'auth.user_logged_in';
    }

    public function payload(): array
    {
        return [
            'user_id' => $this->user->id,
            'user_email' => $this->user->email,
            'user_name' => $this->user->name,
            'session_id' => $this->session['id'] ?? null,
            'login_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}