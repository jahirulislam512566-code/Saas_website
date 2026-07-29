<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Has all permissions and full system access',
                'is_active' => true,
                'is_default' => false,
                'is_system' => true,
                'is_editable' => false,
                'color' => '#ef4444',
                'icon' => 'fa-crown',
                'priority' => 100,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Has most permissions and can manage users',
                'is_active' => true,
                'is_default' => false,
                'is_system' => true,
                'is_editable' => false,
                'color' => '#6366f1',
                'icon' => 'fa-user-shield',
                'priority' => 90,
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Can manage content and users with limited permissions',
                'is_active' => true,
                'is_default' => false,
                'is_system' => false,
                'is_editable' => true,
                'color' => '#8b5cf6',
                'icon' => 'fa-user-tie',
                'priority' => 70,
            ],
            [
                'name' => 'editor',
                'display_name' => 'Editor',
                'description' => 'Can create and manage content',
                'is_active' => true,
                'is_default' => false,
                'is_system' => false,
                'is_editable' => true,
                'color' => '#3b82f6',
                'icon' => 'fa-edit',
                'priority' => 50,
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Default user role with basic permissions',
                'is_active' => true,
                'is_default' => true,
                'is_system' => true,
                'is_editable' => false,
                'color' => '#6b7280',
                'icon' => 'fa-user',
                'priority' => 10,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}