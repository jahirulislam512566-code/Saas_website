<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByUsername(string $username): ?User
    {
        return $this->model->where('username', $username)->first();
    }

    public function findByRole(string $role): Collection
    {
        return $this->model->whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->get();
    }

    public function getActiveUsers(): Collection
    {
        return $this->model->where('status', 'active')->get();
    }

    public function getInactiveUsers(): Collection
    {
        return $this->model->where('status', 'inactive')->get();
    }

    public function getBannedUsers(): Collection
    {
        return $this->model->where('status', 'banned')->get();
    }

    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = $data['email_verified_at'] ?? null;
        
        return $this->create($data);
    }

    public function updateUser(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $this->update($user, $data);
    }

    public function updateProfile(User $user, array $data): User
    {
        $allowedFields = ['name', 'username', 'bio', 'avatar', 'settings'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));
        
        return $this->update($user, $updateData);
    }

    public function updatePassword(User $user, string $password): User
    {
        return $this->update($user, ['password' => Hash::make($password)]);
    }

    public function verifyEmail(User $user): User
    {
        return $this->update($user, ['email_verified_at' => now()]);
    }

    public function banUser(User $user): User
    {
        return $this->update($user, [
            'status' => 'banned',
            'banned_at' => now(),
        ]);
    }

    public function unbanUser(User $user): User
    {
        return $this->update($user, [
            'status' => 'active',
            'banned_at' => null,
        ]);
    }

    public function activateUser(User $user): User
    {
        return $this->update($user, ['status' => 'active']);
    }

    public function deactivateUser(User $user): User
    {
        return $this->update($user, ['status' => 'inactive']);
    }

    public function getUsersWithSubscriptions(): Collection
    {
        return $this->model->whereHas('subscriptions', function ($query) {
            $query->where('status', 'active');
        })->with('subscriptions.plan')->get();
    }

    public function getUsersWithWebsites(): Collection
    {
        return $this->model->whereHas('websites', function ($query) {
            $query->where('status', 'published');
        })->with('websites')->get();
    }

    public function getRecentUsers(int $limit = 10): Collection
    {
        return $this->model->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    public function getUserStatistics(): array
    {
        return [
            'total' => $this->count(),
            'active' => $this->model->where('status', 'active')->count(),
            'inactive' => $this->model->where('status', 'inactive')->count(),
            'banned' => $this->model->where('status', 'banned')->count(),
            'verified' => $this->model->whereNotNull('email_verified_at')->count(),
            'unverified' => $this->model->whereNull('email_verified_at')->count(),
            'registered_today' => $this->model->whereDate('created_at', today())->count(),
            'registered_this_week' => $this->model->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'registered_this_month' => $this->model->whereMonth('created_at', now()->month)->count(),
        ];
    }

    public function searchUsers(string $searchTerm): Collection
    {
        return $this->model->where(function ($query) use ($searchTerm) {
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('username', 'LIKE', "%{$searchTerm}%");
        })->get();
    }

    public function getUsersByPlan(int $planId): Collection
    {
        return $this->model->where('plan_id', $planId)->get();
    }

    public function getUsersWithoutPlan(): Collection
    {
        return $this->model->whereNull('plan_id')->get();
    }

    public function assignRole(User $user, string $role): User
    {
        $user->assignRole($role);
        return $user->fresh();
    }

    public function removeRole(User $user, string $role): User
    {
        $user->removeRole($role);
        return $user->fresh();
    }

    public function syncRoles(User $user, array $roles): User
    {
        $user->syncRoles($roles);
        return $user->fresh();
    }

    public function getUsersByPermission(string $permission): Collection
    {
        return $this->model->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->get();
    }

    public function getUsersWithExpiringSubscriptions(int $days = 7): Collection
    {
        return $this->model->whereHas('subscriptions', function ($query) use ($days) {
            $query->where('status', 'active')
                  ->whereBetween('end_date', [now(), now()->addDays($days)]);
        })->with('subscriptions.plan')->get();
    }

    public function getUserAnalytics(int $userId): array
    {
        $user = $this->findOrFail($userId);
        
        return [
            'total_websites' => $user->websites()->count(),
            'published_websites' => $user->websites()->where('status', 'published')->count(),
            'total_pages' => $user->pages()->count(),
            'total_forms' => $user->forms()->count(),
            'total_submissions' => $user->formSubmissions()->count(),
            'total_media' => $user->media()->count(),
            'storage_used' => $user->media()->sum('size') / 1024 / 1024, // MB
        ];
    }

    public function deleteUserWithData(User $user): bool
    {
        // Begin transaction
        $this->beginTransaction();
        
        try {
            // Delete user's websites, pages, forms, media, etc.
            $user->websites()->delete();
            $user->pages()->delete();
            $user->forms()->delete();
            $user->media()->delete();
            $user->subscriptions()->delete();
            $user->invoices()->delete();
            $user->payments()->delete();
            
            // Delete user
            $user->delete();
            
            $this->commitTransaction();
            return true;
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw $e;
        }
    }
}