<?php

namespace App\Enums;

enum Permission: string
{
    use EnumTrait;

    // User permissions
    case VIEW_USERS = 'view_users';
    case CREATE_USERS = 'create_users';
    case EDIT_USERS = 'edit_users';
    case DELETE_USERS = 'delete_users';
    case MANAGE_ROLES = 'manage_roles';

    // Content permissions
    case VIEW_CONTENT = 'view_content';
    case CREATE_CONTENT = 'create_content';
    case EDIT_CONTENT = 'edit_content';
    case DELETE_CONTENT = 'delete_content';
    case PUBLISH_CONTENT = 'publish_content';

    // Order permissions
    case VIEW_ORDERS = 'view_orders';
    case CREATE_ORDERS = 'create_orders';
    case EDIT_ORDERS = 'edit_orders';
    case DELETE_ORDERS = 'delete_orders';
    case CANCEL_ORDERS = 'cancel_orders';
    case REFUND_ORDERS = 'refund_orders';

    // Customer permissions
    case VIEW_CUSTOMERS = 'view_customers';
    case CREATE_CUSTOMERS = 'create_customers';
    case EDIT_CUSTOMERS = 'edit_customers';
    case DELETE_CUSTOMERS = 'delete_customers';

    // Project permissions
    case VIEW_PROJECTS = 'view_projects';
    case CREATE_PROJECTS = 'create_projects';
    case EDIT_PROJECTS = 'edit_projects';
    case DELETE_PROJECTS = 'delete_projects';
    case MANAGE_PROJECTS = 'manage_projects';

    // Invoice permissions
    case VIEW_INVOICES = 'view_invoices';
    case CREATE_INVOICES = 'create_invoices';
    case EDIT_INVOICES = 'edit_invoices';
    case DELETE_INVOICES = 'delete_invoices';
    case MARK_PAID = 'mark_paid';
    case MARK_OVERDUE = 'mark_overdue';

    // Ticket permissions
    case VIEW_TICKETS = 'view_tickets';
    case CREATE_TICKETS = 'create_tickets';
    case REPLY_TICKETS = 'reply_tickets';
    case RESOLVE_TICKETS = 'resolve_tickets';
    case CLOSE_TICKETS = 'close_tickets';

    // Admin permissions
    case VIEW_REPORTS = 'view_reports';
    case GENERATE_REPORTS = 'generate_reports';
    case MANAGE_SETTINGS = 'manage_settings';
    case VIEW_LOGS = 'view_logs';
    case ACCESS_DEBUG = 'access_debug';
    case MANAGE_BACKUPS = 'manage_backups';

    /**
     * Get descriptions for each permission
     */
    protected static function descriptions(): array
    {
        return [
            'view_users' => 'View user accounts',
            'create_users' => 'Create new users',
            'edit_users' => 'Edit user accounts',
            'delete_users' => 'Delete user accounts',
            'manage_roles' => 'Manage roles and permissions',
            
            'view_content' => 'View content',
            'create_content' => 'Create new content',
            'edit_content' => 'Edit existing content',
            'delete_content' => 'Delete content',
            'publish_content' => 'Publish content',

            'view_orders' => 'View orders',
            'create_orders' => 'Create new orders',
            'edit_orders' => 'Edit orders',
            'delete_orders' => 'Delete orders',
            'cancel_orders' => 'Cancel orders',
            'refund_orders' => 'Refund orders',

            'view_customers' => 'View customers',
            'create_customers' => 'Create new customers',
            'edit_customers' => 'Edit customers',
            'delete_customers' => 'Delete customers',

            'view_projects' => 'View projects',
            'create_projects' => 'Create new projects',
            'edit_projects' => 'Edit projects',
            'delete_projects' => 'Delete projects',
            'manage_projects' => 'Manage all aspects of projects',

            'view_invoices' => 'View invoices',
            'create_invoices' => 'Create new invoices',
            'edit_invoices' => 'Edit invoices',
            'delete_invoices' => 'Delete invoices',
            'mark_paid' => 'Mark invoices as paid',
            'mark_overdue' => 'Mark invoices as overdue',

            'view_tickets' => 'View support tickets',
            'create_tickets' => 'Create support tickets',
            'reply_tickets' => 'Reply to tickets',
            'resolve_tickets' => 'Resolve tickets',
            'close_tickets' => 'Close tickets',

            'view_reports' => 'View reports',
            'generate_reports' => 'Generate reports',
            'manage_settings' => 'Manage application settings',
            'view_logs' => 'View system logs',
            'access_debug' => 'Access debug tools',
            'manage_backups' => 'Manage backups',
        ];
    }

    /**
     * Get colors for each permission
     */
    protected static function colors(): array
    {
        return [
            'view_users' => '#28a745',
            'create_users' => '#007bff',
            'edit_users' => '#17a2b8',
            'delete_users' => '#dc3545',
            'manage_roles' => '#6f42c1',

            'view_content' => '#28a745',
            'create_content' => '#007bff',
            'edit_content' => '#17a2b8',
            'delete_content' => '#dc3545',
            'publish_content' => '#ffc107',

            'view_orders' => '#28a745',
            'create_orders' => '#007bff',
            'edit_orders' => '#17a2b8',
            'delete_orders' => '#dc3545',
            'cancel_orders' => '#fd7e14',
            'refund_orders' => '#6c757d',

            'view_customers' => '#28a745',
            'create_customers' => '#007bff',
            'edit_customers' => '#17a2b8',
            'delete_customers' => '#dc3545',

            'view_projects' => '#28a745',
            'create_projects' => '#007bff',
            'edit_projects' => '#17a2b8',
            'delete_projects' => '#dc3545',
            'manage_projects' => '#6f42c1',

            'view_invoices' => '#28a745',
            'create_invoices' => '#007bff',
            'edit_invoices' => '#17a2b8',
            'delete_invoices' => '#dc3545',
            'mark_paid' => '#28a745',
            'mark_overdue' => '#dc3545',

            'view_tickets' => '#28a745',
            'create_tickets' => '#007bff',
            'reply_tickets' => '#17a2b8',
            'resolve_tickets' => '#28a745',
            'close_tickets' => '#6c757d',

            'view_reports' => '#28a745',
            'generate_reports' => '#007bff',
            'manage_settings' => '#6c757d',
            'view_logs' => '#17a2b8',
            'access_debug' => '#fd7e14',
            'manage_backups' => '#6c757d',
        ];
    }

    /**
     * Get icons for each permission
     */
    protected static function icons(): array
    {
        return [
            'view_users' => 'fa-users',
            'create_users' => 'fa-user-plus',
            'edit_users' => 'fa-user-edit',
            'delete_users' => 'fa-user-minus',
            'manage_roles' => 'fa-user-shield',

            'view_content' => 'fa-eye',
            'create_content' => 'fa-plus',
            'edit_content' => 'fa-edit',
            'delete_content' => 'fa-trash',
            'publish_content' => 'fa-check',

            'view_orders' => 'fa-eye',
            'create_orders' => 'fa-plus',
            'edit_orders' => 'fa-edit',
            'delete_orders' => 'fa-trash',
            'cancel_orders' => 'fa-times',
            'refund_orders' => 'fa-undo',

            'view_customers' => 'fa-users',
            'create_customers' => 'fa-user-plus',
            'edit_customers' => 'fa-user-edit',
            'delete_customers' => 'fa-user-minus',

            'view_projects' => 'fa-project-diagram',
            'create_projects' => 'fa-plus',
            'edit_projects' => 'fa-edit',
            'delete_projects' => 'fa-trash',
            'manage_projects' => 'fa-tasks',

            'view_invoices' => 'fa-file-invoice',
            'create_invoices' => 'fa-plus',
            'edit_invoices' => 'fa-edit',
            'delete_invoices' => 'fa-trash',
            'mark_paid' => 'fa-check',
            'mark_overdue' => 'fa-exclamation',

            'view_tickets' => 'fa-ticket-alt',
            'create_tickets' => 'fa-plus',
            'reply_tickets' => 'fa-reply',
            'resolve_tickets' => 'fa-check',
            'close_tickets' => 'fa-times',

            'view_reports' => 'fa-chart-bar',
            'generate_reports' => 'fa-chart-line',
            'manage_settings' => 'fa-cog',
            'view_logs' => 'fa-history',
            'access_debug' => 'fa-bug',
            'manage_backups' => 'fa-database',
        ];
    }

    /**
     * Get category for permission
     */
    public function category(): string
    {
        return match ($this) {
            self::VIEW_USERS, self::CREATE_USERS, self::EDIT_USERS,
            self::DELETE_USERS, self::MANAGE_ROLES => 'User Management',
            
            self::VIEW_CONTENT, self::CREATE_CONTENT, self::EDIT_CONTENT,
            self::DELETE_CONTENT, self::PUBLISH_CONTENT => 'Content Management',
            
            self::VIEW_ORDERS, self::CREATE_ORDERS, self::EDIT_ORDERS,
            self::DELETE_ORDERS, self::CANCEL_ORDERS, self::REFUND_ORDERS => 'Order Management',
            
            self::VIEW_CUSTOMERS, self::CREATE_CUSTOMERS,
            self::EDIT_CUSTOMERS, self::DELETE_CUSTOMERS => 'Customer Management',
            
            self::VIEW_PROJECTS, self::CREATE_PROJECTS, self::EDIT_PROJECTS,
            self::DELETE_PROJECTS, self::MANAGE_PROJECTS => 'Project Management',
            
            self::VIEW_INVOICES, self::CREATE_INVOICES, self::EDIT_INVOICES,
            self::DELETE_INVOICES, self::MARK_PAID, self::MARK_OVERDUE => 'Invoice Management',
            
            self::VIEW_TICKETS, self::CREATE_TICKETS, self::REPLY_TICKETS,
            self::RESOLVE_TICKETS, self::CLOSE_TICKETS => 'Ticket Management',
            
            self::VIEW_REPORTS, self::GENERATE_REPORTS, self::MANAGE_SETTINGS,
            self::VIEW_LOGS, self::ACCESS_DEBUG, self::MANAGE_BACKUPS => 'Administration',
        };
    }
}