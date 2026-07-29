<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserObserver extends BaseObserver
{
    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
    {
        // Generate UUID if not set
        if (empty($user->uuid)) {
            $user->uuid = (string) Str::uuid();
        }

        // Set default language if not set
        if (empty($user->language)) {
            $user->language = config('app.locale', 'en');
        }

        // Set default timezone if not set
        if (empty($user->timezone)) {
            $user->timezone = config('app.timezone', 'UTC');
        }

        $this->log('creating', $user);
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Clear user cache
        Cache::forget('users_count');
        
        // Create default user preferences (if preferences relationship exists)
        if (method_exists($user, 'preferences')) {
            try {
                $user->preferences()->create([
                    'notifications' => [
                        'email' => true,
                        'sms' => false,
                        'push' => true,
                    ],
                    'theme' => 'light',
                ]);
            } catch (\Exception $e) {
                Log::warning('Could not create user preferences', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->log('created', $user);
    }

    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        // Log changes
        $changes = $user->getDirty();
        
        if (!empty($changes)) {
            $this->log('updating', $user, [
                'old' => $user->getOriginal(),
                'new' => $changes,
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Clear user cache
        Cache::forget("user_{$user->id}");
        Cache::forget("user_{$user->email}");

        $this->log('updated', $user);
    }

    /**
     * Handle the User "deleting" event.
     */
    public function deleting(User $user): void
    {
        // Check if user can be deleted
        if ($user->id === 1) {
            throw new \Exception('Cannot delete the super administrator.');
        }

        $this->log('deleting', $user);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        // Clear user cache
        Cache::forget("user_{$user->id}");
        Cache::forget('users_count');

        // Log the deletion
        Log::warning('User deleted', [
            'user_id' => $user->id,
            'email' => $user->email,
            'deleted_by' => $this->getUserId(),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $this->log('restored', $user);
    }

    /**
     * Handle the User "forceDeleted" event.
     */
    public function forceDeleted(User $user): void
    {
        $this->log('forceDeleted', $user);
        
        Log::warning('User permanently deleted', [
            'user_id' => $user->id,
            'email' => $user->email,
            'deleted_by' => $this->getUserId(),
        ]);
    }
}