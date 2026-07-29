<?php

use App\Models\User;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_DEFAULT_GUARD', 'web'),
        'passwords' => env('AUTH_DEFAULT_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Here you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'api' => [
            'driver' => 'sanctum',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => User::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset Settings
    |--------------------------------------------------------------------------
    |
    | Here you may specify the password reset settings for your application,
    | including the table that holds the reset tokens and the expiration
    | time. You can also configure the throttle limit for reset requests.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => env('AUTH_PASSWORD_RESET_EXPIRE', 60),
            'throttle' => env('AUTH_PASSWORD_RESET_THROTTLE', 60),
        ],

        'admins' => [
            'provider' => 'admins',
            'table' => 'admin_password_reset_tokens',
            'expire' => env('AUTH_ADMIN_PASSWORD_RESET_EXPIRE', 60),
            'throttle' => env('AUTH_ADMIN_PASSWORD_RESET_THROTTLE', 60),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

    /*
    |--------------------------------------------------------------------------
    | Redirect Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines where users should be redirected after authentication,
    | registration, and logout.
    |
    */

    'redirects' => [
        /*
        |--------------------------------------------------------------------------
        | Default Redirects - ALL USERS GO TO HOMEPAGE
        |--------------------------------------------------------------------------
        */

        'after_login' => env('AUTH_REDIRECT_AFTER_LOGIN', '/'), // Homepage
        'after_register' => env('AUTH_REDIRECT_AFTER_REGISTER', '/'), // Homepage
        'after_logout' => env('AUTH_REDIRECT_AFTER_LOGOUT', '/'), // Homepage
        'after_verification' => env('AUTH_REDIRECT_AFTER_VERIFICATION', '/'), // Homepage

        /*
        |--------------------------------------------------------------------------
        | Role-Based Redirects (Only for admin/manager roles)
        |--------------------------------------------------------------------------
        */

        'roles' => [
            'super_admin' => '/super-admin/dashboard',
            'admin' => '/admin/dashboard',
            'manager' => '/manager/dashboard',
            // All other users (editor, customer, vendor, etc.) go to homepage
        ],

        /*
        |--------------------------------------------------------------------------
        | Excluded Routes from Redirect
        |--------------------------------------------------------------------------
        */

        'excluded_routes' => [
            'login',
            'register',
            'password.*',
            'verify.*',
            'confirm.*',
            'logout',
            'auth.*',
        ],
    ],

];