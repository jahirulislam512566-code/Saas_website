<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserLoggedIn;
use App\Listeners\BaseListener;

class UpdateLastLogin extends BaseListener
{
    public function handle(UserLoggedIn $event): void
    {
        $this->logStart('UserLoggedIn', ['user_id' => $event->user->id]);

        try {
            $event->user->update([
                'last_login_at' => now(),
                'last_login_ip' => request()->ip(),
                'last_login_user_agent' => request()->userAgent(),
            ]);

            $this->logComplete('UserLoggedIn', ['user_id' => $event->user->id]);
        } catch (\Exception $e) {
            $this->logError('UserLoggedIn', $e, ['user_id' => $event->user->id]);
        }
    }
}