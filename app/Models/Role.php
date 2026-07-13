<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'display_name',
        'description',
        'guard_name',
        'is_active',
        'is_default',
        'is_system',
        'is_editable',
        'color',
        'icon',
        'priority',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'is_system' => 'boolean',
        'is_editable' => 'boolean',
        'metadata' => 'json',
    ];

    // Relationships
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role')
            ->withPivot('tenant_id', 'assigned_by', 'assigned_at', 'expires_at', 'metadata')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user')
            ->withPivot('tenant_id', 'assigned_by', 'assigned_at', 'expires_at', 'metadata')
            ->withTimestamps();
    }

    // Check if role has a specific permission
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        return $this->permissions()
            ->where('permission_id', $permission->id)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    // Assign permission to role
    public function assignPermission($permission, $expiresAt = null, $metadata = null)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        $this->permissions()->attach($permission, [
            'assigned_by' => auth()->id(),
            'assigned_at' => now(),
            'expires_at' => $expiresAt,
            'metadata' => $metadata ? json_encode($metadata) : null,
        ]);

        return $this;
    }

    // Remove permission from role
    public function removePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        $this->permissions()->detach($permission);

        return $this;
    }

    // Sync permissions
    public function syncPermissions(array $permissions)
    {
        $permissionIds = collect($permissions)->map(function ($permission) {
            if (is_string($permission)) {
                return Permission::where('name', $permission)->firstOrFail()->id;
            }
            return $permission->id;
        });

        $this->permissions()->sync($permissionIds);

        return $this;
    }
}