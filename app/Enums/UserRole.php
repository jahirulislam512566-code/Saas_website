<?php

namespace App\Enums;

enum UserRole: string
{
    use EnumTrait;

    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case EDITOR = 'editor';
    case AUTHOR = 'author';
    case CONTRIBUTOR = 'contributor';
    case USER = 'user';
    case CUSTOMER = 'customer';
    case DEVELOPER = 'developer';
    case DESIGNER = 'designer';
    case SUPPORT = 'support';

    /**
     * Get descriptions for each role
     */
    protected static function descriptions(): array
    {
        return [
            'super_admin' => 'Has unrestricted access to everything',
            'admin' => 'Has administrative access',
            'manager' => 'Manages teams and projects',
            'editor' => 'Can edit and publish content',
            'author' => 'Can create and edit own content',
            'contributor' => 'Can contribute content',
            'user' => 'Standard user access',
            'customer' => 'Customer access',
            'developer' => 'Developer access',
            'designer' => 'Designer access',
            'support' => 'Support access',
        ];
    }

    /**
     * Get colors for each role
     */
    protected static function colors(): array
    {
        return [
            'super_admin' => '#dc3545', // Danger
            'admin' => '#28a745',       // Success
            'manager' => '#17a2b8',     // Info
            'editor' => '#007bff',      // Primary
            'author' => '#6f42c1',      // Purple
            'contributor' => '#fd7e14', // Orange
            'user' => '#6c757d',        // Secondary
            'customer' => '#20c997',    // Teal
            'developer' => '#007bff',   // Primary
            'designer' => '#e83e8c',    // Pink
            'support' => '#28a745',     // Success
        ];
    }

    /**
     * Get icons for each role
     */
    protected static function icons(): array
    {
        return [
            'super_admin' => 'fa-crown',
            'admin' => 'fa-user-shield',
            'manager' => 'fa-user-tie',
            'editor' => 'fa-edit',
            'author' => 'fa-user-edit',
            'contributor' => 'fa-user-plus',
            'user' => 'fa-user',
            'customer' => 'fa-user',
            'developer' => 'fa-code',
            'designer' => 'fa-paint-brush',
            'support' => 'fa-headset',
        ];
    }

    /**
     * Get permissions for each role
     */
    public function permissions(): array
    {
        return match ($this) {
            self::SUPER_ADMIN => ['*'],
            self::ADMIN => ['manage_users', 'manage_roles', 'view_reports', 'manage_settings'],
            self::MANAGER => ['manage_projects', 'manage_team', 'view_reports'],
            self::EDITOR => ['create_content', 'edit_content', 'publish_content', 'delete_content'],
            self::AUTHOR => ['create_content', 'edit_own_content'],
            self::CONTRIBUTOR => ['create_content'],
            self::USER => ['view_content'],
            self::CUSTOMER => ['view_profile', 'place_orders', 'view_orders'],
            self::DEVELOPER => ['view_tickets', 'manage_tickets', 'view_projects'],
            self::DESIGNER => ['view_projects', 'create_designs'],
            self::SUPPORT => ['view_tickets', 'reply_tickets', 'resolve_tickets'],
        };
    }

    /**
     * Check if role is admin level
     */
    public function isAdmin(): bool
    {
        return in_array($this, [self::SUPER_ADMIN, self::ADMIN, self::MANAGER]);
    }

    /**
     * Check if role can manage content
     */
    public function canManageContent(): bool
    {
        return in_array($this, [
            self::EDITOR,
            self::AUTHOR,
            self::CONTRIBUTOR,
            self::ADMIN,
            self::SUPER_ADMIN,
        ]);
    }
}