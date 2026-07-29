<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Redirect Paths
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'login' => '/dashboard',
        'register' => '/dashboard',
        'logout' => '/',
        'verification' => '/dashboard',
        'password_reset' => '/dashboard',
    ],

    /*
    |--------------------------------------------------------------------------
    | Role-Based Redirect Paths
    |--------------------------------------------------------------------------
    */

    'roles' => [
        'super_admin' => '/super-admin/dashboard',
        'admin' => '/admin/dashboard',
        'manager' => '/manager/dashboard',
        'editor' => '/editor/dashboard',
        'customer' => '/customer/dashboard',
        'vendor' => '/vendor/dashboard',
        'user' => '/dashboard',
    ],

    /*
    |--------------------------------------------------------------------------
    | Excluded Routes (won't redirect back to these)
    |--------------------------------------------------------------------------
    */

    'excluded_routes' => [
        'login',
        'register',
        'password.*',
        'password.reset',
        'verify.*',
        'verification.*',
        'confirm.*',
        'confirmation.*',
        'logout',
        'auth.*',
        'social.*',
        '2fa.*',
        'email.*',
        'sanctum.*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Redirect
    |--------------------------------------------------------------------------
    */

    'fallback' => '/',
];