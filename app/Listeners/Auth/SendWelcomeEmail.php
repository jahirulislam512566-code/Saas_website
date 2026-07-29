<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserRegistered;
use App\Mail\Auth\WelcomeMail;
use App\Listeners\BaseListener;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail extends BaseListener
{
    public function handle(UserRegistered $event): void
    {
        $this->logStart('UserRegistered', ['user_id' => $event->user->id]);

        try {
            Mail::to($event->user->email)
                ->send(new WelcomeMail($event->user));

            $this->logComplete('UserRegistered', ['user_id' => $event->user->id]);
        } catch (\Exception $e) {
            $this->logError('UserRegistered', $e, ['user_id' => $event->user->id]);
        }
    }
}