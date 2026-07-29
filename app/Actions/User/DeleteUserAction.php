<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteUserAction
{
    /**
     * Delete a user
     *
     * @param User $user
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function execute(User $user, array $data = []): bool
    {
        return DB::transaction(function () use ($user, $data) {
            // Prevent deleting self
            if ($user->id === auth()->id()) {
                throw new \Exception('Cannot delete your own account.');
            }

            // Check if user has active relationships
            if ($this->hasActiveRelationships($user) && !($data['force'] ?? false)) {
                throw new \Exception('User has active relationships. Use force=true to override.');
            }

            $email = $user->email;
            $name = $user->name;

            // Handle related data
            if ($data['transfer_data'] ?? false) {
                $this->transferRelatedData($user, $data['transfer_to_user_id'] ?? null);
            }

            if ($data['delete_data'] ?? false) {
                $this->deleteRelatedData($user);
            }

            if ($data['force'] ?? false) {
                $user->forceDelete();
                Log::warning('User permanently deleted', [
                    'user_id' => $user->id,
                    'email' => $email,
                    'name' => $name,
                    'deleted_by' => auth()->id()
                ]);
            } else {
                $user->delete();
                Log::info('User soft deleted', [
                    'user_id' => $user->id,
                    'email' => $email,
                    'name' => $name,
                    'deleted_by' => auth()->id()
                ]);
            }

            return true;
        });
    }

    private function hasActiveRelationships(User $user): bool
    {
        return $user->projects()->where('status', 'in_progress')->exists() ||
               $user->tickets()->where('status', 'open')->exists() ||
               $user->orders()->where('status', 'pending')->exists();
    }

    private function transferRelatedData(User $user, ?int $transferToUserId): void
    {
        if ($transferToUserId) {
            $targetUser = User::find($transferToUserId);
            
            if ($targetUser) {
                // Transfer projects
                $user->projects()->update(['user_id' => $targetUser->id]);
                
                // Transfer tickets
                $user->tickets()->update(['assigned_to' => $targetUser->id]);
            }
        }
    }

    private function deleteRelatedData(User $user): void
    {
        // Delete or archive related data
        $user->projects()->delete();
        $user->tickets()->delete();
        $user->tokens()->delete();
    }
}