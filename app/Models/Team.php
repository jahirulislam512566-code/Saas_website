<?php
// app/Models/Team.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'owner_id',
        'name',
        'slug',
        'description',
        'avatar',
        'settings',
        'is_active',
        'max_members',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'max_members' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'members_count',
        'avatar_url',
        'is_full',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members')
                    ->withPivot('role', 'permissions', 'joined_at')
                    ->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class);
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhere('slug', 'LIKE', "%{$search}%");
        });
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getMembersCountAttribute(): int
    {
        return $this->members()->count();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            return Storage::disk('public')->url($this->avatar);
        }

        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&size=200&background=6366f1&color=fff";
    }

    public function getIsFullAttribute(): bool
    {
        if ($this->max_members === null) {
            return false;
        }
        return $this->members_count >= $this->max_members;
    }

    // ============================================
    // CUSTOM METHODS
    // ============================================

    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function hasMember(User $user): bool
    {
        return $this->isMember($user);
    }

    public function addMember(User $user, string $role = 'member', array $permissions = []): self
    {
        $this->members()->attach($user->id, [
            'role' => $role,
            'permissions' => $permissions,
            'joined_at' => now(),
        ]);

        return $this;
    }

    public function removeMember(User $user): self
    {
        $this->members()->detach($user->id);
        return $this;
    }

    public function updateMemberRole(User $user, string $role): self
    {
        $this->members()->updateExistingPivot($user->id, ['role' => $role]);
        return $this;
    }

    public function updateMemberPermissions(User $user, array $permissions): self
    {
        $this->members()->updateExistingPivot($user->id, ['permissions' => $permissions]);
        return $this;
    }

    public function getMemberRole(User $user): ?string
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        return $member ? $member->pivot->role : null;
    }

    public function getMemberPermissions(User $user): array
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        return $member ? $member->pivot->permissions ?? [] : [];
    }

    public function hasMemberPermission(User $user, string $permission): bool
    {
        $permissions = $this->getMemberPermissions($user);
        return in_array($permission, $permissions);
    }
}