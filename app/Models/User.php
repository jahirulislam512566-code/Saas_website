<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'avatar',
        'company_name',
        'job_title',
        'phone',
        'timezone',
        'language',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'email_verified_at',
        'bio',
        'website',
        'social_links',
        'preferences',
        'metadata',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'social_links' => 'array',
        'preferences' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'avatar_url',
        'full_name',
        'display_name',
        'is_online',
        'role_names',
        'permission_names',
        'formatted_created_at',
        'profile_url',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Get the tenant that owns the user.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the roles assigned to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Get the permissions assigned to the user.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    /**
     * Get the tickets created by the user.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the tickets assigned to the user.
     */
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    /**
     * Get the ticket replies by the user.
     */
    public function ticketReplies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    /**
     * Get the payments made by the user.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the subscriptions of the user.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the active subscription of the user.
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest();
    }

    /**
     * Get the media uploaded by the user.
     */
    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Get the teams the user belongs to.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withPivot('role', 'permissions')->withTimestamps();
    }

    /**
     * Get the teams owned by the user.
     */
    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    /**
     * Get the activity logs for the user.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    /**
     * Get the posts created by the user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the comments created by the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the websites owned by the user.
     */
    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope a query to only include users for a specific tenant.
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
 // ============================================
// ACCESSORS & MUTATORS
// ============================================

/**
 * Mutator for the password.
 */
public function setPasswordAttribute($value)
{
    if (!empty($value)) {
        // Check if the value is already hashed
        if (password_get_info($value)['algo'] === 0) {
            // Not hashed yet - hash it
            $this->attributes['password'] = Hash::make($value);
        } else {
            // Already hashed - store as-is
            $this->attributes['password'] = $value;
        }
    }
}

/**
 * Mutator for the name.
 */
public function setNameAttribute($value)
{
    $this->attributes['name'] = trim($value);
}

/**
 * Mutator for the email.
 */
public function setEmailAttribute($value)
{
    $this->attributes['email'] = strtolower(trim($value));
}
    /**
     * Scope a query to only include users with a specific role.
     */
    public function scopeWithRole($query, $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            if (is_string($role)) {
                $q->where('name', $role);
            } else {
                $q->where('id', $role);
            }
        });
    }

    /**
     * Scope a query to only include users with any of the given roles.
     */
    public function scopeWithAnyRole($query, array $roles)
    {
        return $query->whereHas('roles', function ($q) use ($roles) {
            $q->whereIn('name', $roles);
        });
    }

    /**
     * Scope a query to only include users who are online (last activity within 5 minutes).
     */
    public function scopeOnline($query)
    {
        return $query->where('last_login_at', '>=', now()->subMinutes(5));
    }

    /**
     * Scope a query to only include users who have been inactive for a while.
     */
    public function scopeInactiveForDays($query, $days)
    {
        return $query->where('last_login_at', '<=', now()->subDays($days));
    }

    /**
     * Scope a query to search users by name or email.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('company_name', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope a query to order by most recent activity.
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('last_login_at', 'desc')->orderBy('created_at', 'desc');
    }

    // ============================================
    // ACCESSORS & MUTATORS
    // ============================================

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            // Check if it's a full URL or storage path
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            return Storage::disk('public')->url($this->avatar);
        }

        // Generate a default avatar using UI Avatars API
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=6366f1&color=fff";
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get the user's display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? $this->email;
    }

    /**
     * Get the user's initials.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }
        return substr($initials, 0, 2);
    }

    /**
     * Check if the user is online.
     */
    public function getIsOnlineAttribute(): bool
    {
        return $this->last_login_at && $this->last_login_at >= now()->subMinutes(5);
    }

    /**
     * Get the user's role names as a string.
     */
    public function getRoleNamesAttribute(): string
    {
        return $this->roles->pluck('name')->implode(', ');
    }

    /**
     * Get the user's permission names as a string.
     */
    public function getPermissionNamesAttribute(): string
    {
        $permissions = collect();
        foreach ($this->roles as $role) {
            $permissions = $permissions->merge($role->permissions->pluck('name'));
        }
        return $permissions->unique()->implode(', ');
    }

    /**
     * Get formatted created date.
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('M d, Y');
    }

    /**
     * Get the user's profile URL.
     */
    public function getProfileUrlAttribute(): string
    {
        return route('admin.users.show', $this);
    }

    /**
     * Get the user's subscription status.
     */
    public function getSubscriptionStatusAttribute(): string
    {
        $activeSubscription = $this->activeSubscription;
        if ($activeSubscription) {
            return 'active';
        }
        return $this->subscriptions()->where('status', 'canceled')->exists() ? 'canceled' : 'none';
    }

    /**
     * Get the user's subscription plan name.
     */
    public function getSubscriptionPlanAttribute(): ?string
    {
        $activeSubscription = $this->activeSubscription;
        if ($activeSubscription && $activeSubscription->plan) {
            return $activeSubscription->plan->name;
        }
        return null;
    }

    /**
     * Mutator for the password.
     */
   

    /**
     * Mutator for the name.
     */
   

    /**
     * Mutator for the email.
     */
    

    // ============================================
    // CUSTOM METHODS
    // ============================================

    /**
     * Check if the user has a specific role.
     */
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        if ($role instanceof \Illuminate\Support\Collection) {
            return !!$role->intersect($this->roles)->count();
        }
        if (is_array($role)) {
            return !!collect($role)->intersect($this->roles->pluck('name'))->count();
        }
        return false;
    }

    /**
     * Check if the user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }

    /**
     * Check if the user has all of the given roles.
     */
    public function hasAllRoles(array $roles): bool
    {
        $userRoles = $this->roles->pluck('name');
        return collect($roles)->diff($userRoles)->isEmpty();
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission($permission): bool
    {
        // Check direct permissions
        if ($this->permissions->contains('name', $permission)) {
            return true;
        }

        // Check permissions through roles
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super-admin');
    }

    /**
     * Check if the user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Check if the user is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activate the user.
     */
    public function activate(): self
    {
        $this->update(['is_active' => true]);
        return $this;
    }

    /**
     * Deactivate the user.
     */
    public function deactivate(): self
    {
        $this->update(['is_active' => false]);
        return $this;
    }

    /**
     * Toggle the user's active status.
     */
    public function toggleActive(): self
    {
        $this->update(['is_active' => !$this->is_active]);
        return $this;
    }

    /**
     * Record a login.
     */
    public function recordLogin(Request $request): self
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);
        return $this;
    }

    /**
     * Get the user's full address.
     */
    public function getFullAddressAttribute(): ?string
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->zip_code,
            $this->country,
        ]);
        return !empty($parts) ? implode(', ', $parts) : null;
    }

    /**
     * Sync roles and log changes.
     */
    public function syncRolesWithLogging(array $roles, string $action = 'updated'): self
    {
        $oldRoles = $this->roles->pluck('id')->toArray();
        $this->roles()->sync($roles);
        $newRoles = $this->roles->pluck('id')->toArray();

        if ($oldRoles !== $newRoles) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'subject_type' => self::class,
                'subject_id' => $this->id,
                'action' => $action,
                'description' => "Updated roles for user: {$this->name}",
                'properties' => [
                    'old_roles' => $oldRoles,
                    'new_roles' => $newRoles,
                ],
            ]);
        }

        return $this;
    }

    /**
     * Get the user's notification preferences.
     */
    public function getNotificationPreference($key, $default = null)
    {
        return $this->preferences['notifications'][$key] ?? $default;
    }

    /**
     * Set the user's notification preference.
     */
    public function setNotificationPreference($key, $value): self
    {
        $preferences = $this->preferences ?? [];
        $preferences['notifications'][$key] = $value;
        $this->update(['preferences' => $preferences]);
        return $this;
    }

    /**
     * Get the user's cache key.
     */
    public function getCacheKey(): string
    {
        return 'user_' . $this->id . '_' . $this->updated_at->timestamp;
    }

    /**
     * Invalidate the user's cache.
     */
    public function invalidateCache(): self
    {
        Cache::forget($this->getCacheKey());
        return $this;
    }

    // ============================================
    // ADMIN HELPERS
    // ============================================

    /**
     * Get the user's dashboard stats.
     */
    public function getStats(): array
    {
        return [
            'total_posts' => $this->posts()->count(),
            'total_comments' => $this->comments()->count(),
            'total_tickets' => $this->tickets()->count(),
            'total_payments' => $this->payments()->count(),
            'total_subscriptions' => $this->subscriptions()->count(),
            'total_websites' => $this->websites()->count(),
            'total_media' => $this->media()->count(),
            'total_activities' => $this->activities()->count(),
        ];
    }

    /**
     * Get the user's recent activity.
     */
    public function getRecentActivity($limit = 5)
    {
        return $this->activities()->latest()->limit($limit)->get();
    }
}