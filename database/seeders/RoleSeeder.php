<?php
// database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Get the default tenant
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $tenant = Tenant::create([
                'name' => 'Default Tenant',
                'subdomain' => 'default',
                'domain' => 'default.localhost',
                'email' => 'admin@example.com',
                'is_active' => true,
                'status' => 'active',
            ]);
        }

        $this->command->info('🌱 Starting Role and Permission Seeding...');

        // ============ TRUNCATE TABLES FIRST (Optional - Remove if you want to keep existing data) ============
        // Uncomment this if you want to clear all existing roles and permissions
        // DB::table('role_has_permissions')->truncate();
        // DB::table('model_has_roles')->truncate();
        // DB::table('roles')->truncate();
        // DB::table('permissions')->truncate();
        
        // ============ CREATE ROLES (using updateOrCreate to avoid duplicates) ============
        
        // 1. Super Admin Role
        $superAdminRole = Role::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'super_admin'],
            [
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with all permissions',
                'guard_name' => 'web',
                'is_default' => false,
                'is_system' => true,
            ]
        );
        $this->command->info('✅ Super Admin Role ready');

        // 2. Admin Role
        $adminRole = Role::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'Full access within the tenant',
                'guard_name' => 'web',
                'is_default' => false,
                'is_system' => true,
            ]
        );
        $this->command->info('✅ Admin Role ready');

        // 3. Manager Role
        $managerRole = Role::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'manager'],
            [
                'display_name' => 'Manager',
                'description' => 'Manage users and content',
                'guard_name' => 'web',
                'is_default' => false,
                'is_system' => true,
            ]
        );
        $this->command->info('✅ Manager Role ready');

        // 4. Editor Role
        $editorRole = Role::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'editor'],
            [
                'display_name' => 'Editor',
                'description' => 'Create and edit content',
                'guard_name' => 'web',
                'is_default' => false,
                'is_system' => true,
            ]
        );
        $this->command->info('✅ Editor Role ready');

        // 5. User Role (Default)
        $userRole = Role::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'user'],
            [
                'display_name' => 'User',
                'description' => 'Standard user with limited permissions',
                'guard_name' => 'web',
                'is_default' => true,
                'is_system' => true,
            ]
        );
        $this->command->info('✅ User Role ready');

        // 6. Guest Role
        $guestRole = Role::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'guest'],
            [
                'display_name' => 'Guest',
                'description' => 'Read-only access',
                'guard_name' => 'web',
                'is_default' => false,
                'is_system' => true,
            ]
        );
        $this->command->info('✅ Guest Role ready');

        // 7. Support Agent Role
        $supportRole = Role::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'support_agent'],
            [
                'display_name' => 'Support Agent',
                'description' => 'Handle support tickets',
                'guard_name' => 'web',
                'is_default' => false,
                'is_system' => true,
            ]
        );
        $this->command->info('✅ Support Agent Role ready');

        // 8. Accountant Role
        $accountantRole = Role::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'accountant'],
            [
                'display_name' => 'Accountant',
                'description' => 'Manage billing and invoices',
                'guard_name' => 'web',
                'is_default' => false,
                'is_system' => true,
            ]
        );
        $this->command->info('✅ Accountant Role ready');

        // ============ CREATE PERMISSIONS (using updateOrCreate) ============
        
        $permissionGroups = [
            'dashboard' => [
                'view_dashboard' => 'View Dashboard',
            ],
            'users' => [
                'view_users' => 'View Users',
                'create_users' => 'Create Users',
                'edit_users' => 'Edit Users',
                'delete_users' => 'Delete Users',
                'manage_roles' => 'Manage Roles',
            ],
            'content' => [
                'view_content' => 'View Content',
                'create_content' => 'Create Content',
                'edit_content' => 'Edit Content',
                'delete_content' => 'Delete Content',
                'publish_content' => 'Publish Content',
            ],
            'portfolio' => [
                'view_portfolio' => 'View Portfolio',
                'create_portfolio' => 'Create Portfolio Items',
                'edit_portfolio' => 'Edit Portfolio Items',
                'delete_portfolio' => 'Delete Portfolio Items',
            ],
            'services' => [
                'view_services' => 'View Services',
                'create_services' => 'Create Services',
                'edit_services' => 'Edit Services',
                'delete_services' => 'Delete Services',
            ],
            'blog' => [
                'view_blog' => 'View Blog',
                'create_blog' => 'Create Blog Posts',
                'edit_blog' => 'Edit Blog Posts',
                'delete_blog' => 'Delete Blog Posts',
                'manage_comments' => 'Manage Comments',
            ],
            'support' => [
                'view_tickets' => 'View Tickets',
                'create_tickets' => 'Create Tickets',
                'edit_tickets' => 'Edit Tickets',
                'delete_tickets' => 'Delete Tickets',
                'resolve_tickets' => 'Resolve Tickets',
            ],
            'payments' => [
                'view_payments' => 'View Payments',
                'create_payments' => 'Create Payments',
                'edit_payments' => 'Edit Payments',
                'delete_payments' => 'Delete Payments',
                'refund_payments' => 'Refund Payments',
            ],
            'subscriptions' => [
                'view_subscriptions' => 'View Subscriptions',
                'create_subscriptions' => 'Create Subscriptions',
                'edit_subscriptions' => 'Edit Subscriptions',
                'delete_subscriptions' => 'Delete Subscriptions',
                'cancel_subscriptions' => 'Cancel Subscriptions',
            ],
            'media' => [
                'view_media' => 'View Media',
                'upload_media' => 'Upload Media',
                'edit_media' => 'Edit Media',
                'delete_media' => 'Delete Media',
            ],
            'settings' => [
                'view_settings' => 'View Settings',
                'edit_settings' => 'Edit Settings',
                'manage_tenant' => 'Manage Tenant',
            ],
            'reports' => [
                'view_reports' => 'View Reports',
                'export_reports' => 'Export Reports',
            ],
        ];

        foreach ($permissionGroups as $group => $perms) {
            foreach ($perms as $name => $displayName) {
                Permission::updateOrCreate(
                    ['tenant_id' => $tenant->id, 'name' => $name],
                    [
                        'display_name' => $displayName,
                        'group' => $group,
                        'guard_name' => 'web',
                    ]
                );
            }
        }
        $this->command->info('✅ Permissions ready');

        // ============ ASSIGN PERMISSIONS TO ROLES ============

        // Super Admin: All permissions
        $superAdminRole->permissions()->sync(Permission::pluck('id'));
        $this->command->info('✅ Assigned all permissions to Super Admin');

        // Admin: All permissions except system-level
        $adminPermissions = Permission::whereNotIn('name', [
            'manage_tenant',
            'delete_users',
        ])->pluck('id');
        $adminRole->permissions()->sync($adminPermissions);
        $this->command->info('✅ Assigned permissions to Admin');

        // Manager: Content, Portfolio, Services, Blog permissions
        $managerPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_content', 'create_content', 'edit_content', 'delete_content', 'publish_content',
            'view_portfolio', 'create_portfolio', 'edit_portfolio', 'delete_portfolio',
            'view_services', 'create_services', 'edit_services', 'delete_services',
            'view_blog', 'create_blog', 'edit_blog', 'delete_blog', 'manage_comments',
            'view_media', 'upload_media', 'edit_media', 'delete_media',
            'view_users', 'create_users', 'edit_users',
            'view_reports', 'export_reports',
        ])->pluck('id');
        $managerRole->permissions()->sync($managerPermissions);
        $this->command->info('✅ Assigned permissions to Manager');

        // Editor: Content only
        $editorPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_content', 'create_content', 'edit_content',
            'view_portfolio', 'create_portfolio', 'edit_portfolio',
            'view_services', 'create_services', 'edit_services',
            'view_blog', 'create_blog', 'edit_blog',
            'view_media', 'upload_media', 'edit_media',
        ])->pluck('id');
        $editorRole->permissions()->sync($editorPermissions);
        $this->command->info('✅ Assigned permissions to Editor');

        // Support Agent: Support and basic permissions
        $supportPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_tickets', 'create_tickets', 'edit_tickets', 'resolve_tickets',
            'view_users',
            'view_content',
            'view_media',
        ])->pluck('id');
        $supportRole->permissions()->sync($supportPermissions);
        $this->command->info('✅ Assigned permissions to Support Agent');

        // Accountant: Payments and subscriptions
        $accountantPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_payments', 'create_payments', 'edit_payments', 'refund_payments',
            'view_subscriptions', 'create_subscriptions', 'edit_subscriptions', 'cancel_subscriptions',
            'view_reports', 'export_reports',
            'view_users',
        ])->pluck('id');
        $accountantRole->permissions()->sync($accountantPermissions);
        $this->command->info('✅ Assigned permissions to Accountant');

        // User: Basic permissions
        $userPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_content',
            'view_portfolio',
            'view_services',
            'view_blog',
            'view_media',
            'create_tickets',
            'view_subscriptions',
        ])->pluck('id');
        $userRole->permissions()->sync($userPermissions);
        $this->command->info('✅ Assigned permissions to User');

        // ============ CREATE USERS WITH ROLES (using updateOrCreate) ============

        // 1. Super Admin User
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $adminUser->roles()->sync([$superAdminRole->id]);
        $this->command->info('✅ Super Admin User ready');

        // 2. Admin User
        $adminUser2 = User::updateOrCreate(
            ['email' => 'admin2@example.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $adminUser2->roles()->sync([$adminRole->id]);
        $this->command->info('✅ Admin User ready');

        // 3. Manager User
        $managerUser = User::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Manager User',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $managerUser->roles()->sync([$managerRole->id]);
        $this->command->info('✅ Manager User ready');

        // 4. Editor User
        $editorUser = User::updateOrCreate(
            ['email' => 'editor@example.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Editor User',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $editorUser->roles()->sync([$editorRole->id]);
        $this->command->info('✅ Editor User ready');

        // 5. Support Agent User
        $supportUser = User::updateOrCreate(
            ['email' => 'support@example.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Support Agent',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $supportUser->roles()->sync([$supportRole->id]);
        $this->command->info('✅ Support Agent User ready');

        // 6. Accountant User
        $accountantUser = User::updateOrCreate(
            ['email' => 'accountant@example.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Accountant User',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $accountantUser->roles()->sync([$accountantRole->id]);
        $this->command->info('✅ Accountant User ready');

        // 7. Regular User
        $regularUser = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $regularUser->roles()->sync([$userRole->id]);
        $this->command->info('✅ Regular User ready');

        // 8. Demo User
        $demoUser = User::updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $demoUser->roles()->sync([$userRole->id]);
        $this->command->info('✅ Demo User ready');

        // ============ SUMMARY ============
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('✅ ROLE AND PERMISSION SEEDING COMPLETE!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('📋 Created/Updated Roles:');
        $this->command->info('  - Super Admin');
        $this->command->info('  - Admin');
        $this->command->info('  - Accountant');
        $this->command->info('  - Manager');
        $this->command->info('  - Support Agent');
        $this->command->info('  - Editor');
        $this->command->info('  - User (Default)');
        $this->command->info('  - Guest');
        $this->command->info('');
        $this->command->info('👤 Test Users (password: password):');
        $this->command->info('  🔑 admin@example.com   - Super Admin');
        $this->command->info('  🔑 admin2@example.com  - Admin');
        $this->command->info('  🔑 manager@example.com - Manager');
        $this->command->info('  🔑 editor@example.com  - Editor');
        $this->command->info('  🔑 support@example.com - Support Agent');
        $this->command->info('  🔑 accountant@example.com - Accountant');
        $this->command->info('  🔑 user@example.com    - Regular User');
        $this->command->info('  🔑 demo@example.com    - Demo User');
        $this->command->info('========================================');
    }
}