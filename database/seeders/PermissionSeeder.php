<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'group' => 'Dashboard', 'module' => 'dashboard'],
            ['name' => 'dashboard.analytics', 'display_name' => 'View Analytics', 'group' => 'Dashboard', 'module' => 'dashboard'],
            
            // Users
            ['name' => 'users.view', 'display_name' => 'View Users', 'group' => 'Users', 'module' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'group' => 'Users', 'module' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'group' => 'Users', 'module' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'group' => 'Users', 'module' => 'users'],
            
            // Roles
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'group' => 'Roles', 'module' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'group' => 'Roles', 'module' => 'roles'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Roles', 'group' => 'Roles', 'module' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'group' => 'Roles', 'module' => 'roles'],
            
            // Posts
            ['name' => 'posts.view', 'display_name' => 'View Posts', 'group' => 'Content', 'module' => 'posts'],
            ['name' => 'posts.create', 'display_name' => 'Create Posts', 'group' => 'Content', 'module' => 'posts'],
            ['name' => 'posts.edit', 'display_name' => 'Edit Posts', 'group' => 'Content', 'module' => 'posts'],
            ['name' => 'posts.delete', 'display_name' => 'Delete Posts', 'group' => 'Content', 'module' => 'posts'],
            
            // Settings
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'group' => 'Settings', 'module' => 'settings'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'group' => 'Settings', 'module' => 'settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}