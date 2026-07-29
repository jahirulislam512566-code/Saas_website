<?php

namespace App\Jobs\Email;

use App\Jobs\BaseJob;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailJob extends BaseJob
{
    public User $user;
    public string $password;

    public function __construct(User $user, string $password = '')
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function handle(): void
    {
        $this->logStart();

        try {
            Mail::to($this->user->email)->send(new WelcomeMail($this->user, $this->password));

            Log::info('Welcome email sent', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
            ]);

            $this->logComplete();

        } catch (\Exception $e) {
            $this->logError($e);
            throw $e;
        }
    }

    protected function getJobData(): array
    {
        return [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
        ];
    }
}