<?php
// database/seeders/PermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Tenant;

class PermissionSeeder extends Seeder
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

        $this->command->info('🌱 Starting Permission Seeding...');

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

        $count = 0;

        foreach ($permissionGroups as $group => $perms) {
            foreach ($perms as $name => $displayName) {
                Permission::updateOrCreate(
                    ['tenant_id' => $tenant->id, 'name' => $name],
                    [
                        'display_name' => $displayName,
                        'slug' => str_replace('_', '-', $name),
                        'description' => $displayName . ' permission',
                        'group' => $group,
                        'guard_name' => 'web',
                        'is_active' => true,
                        'is_system' => false,
                        'is_editable' => true,
                        'priority' => 0,
                    ]
                );
                $count++;
            }
        }

        $this->command->info("✅ {$count} Permissions seeded successfully!");
    }
}