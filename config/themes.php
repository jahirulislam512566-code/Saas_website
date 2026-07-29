<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Active Theme
    |--------------------------------------------------------------------------
    |
    | This value determines which theme is currently active for the website.
    | Available options: default, restaurant, portfolio, agency, gym, school
    |
    */
    'active' => env('ACTIVE_THEME', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Theme Configurations
    |--------------------------------------------------------------------------
    |
    | Each theme can have its own configuration settings.
    |
    */
    'themes' => [
        'default' => [
            'name' => 'Default',
            'description' => 'Default website theme',
            'author' => 'Buildora',
            'version' => '1.0.0',
        ],
        'restaurant' => [
            'name' => 'Restaurant',
            'description' => 'Restaurant theme with menu and reservation system',
            'author' => 'Buildora',
            'version' => '1.0.0',
        ],
        'portfolio' => [
            'name' => 'Portfolio',
            'description' => 'Portfolio theme for creative professionals',
            'author' => 'Buildora',
            'version' => '1.0.0',
        ],
        'agency' => [
            'name' => 'Agency',
            'description' => 'Agency theme for digital agencies',
            'author' => 'Buildora',
            'version' => '1.0.0',
        ],
        'gym' => [
            'name' => 'Gym',
            'description' => 'Gym theme with class schedules and membership',
            'author' => 'Buildora',
            'version' => '1.0.0',
        ],
        'school' => [
            'name' => 'School',
            'description' => 'School theme with course management',
            'author' => 'Buildora',
            'version' => '1.0.0',
        ],
    ],
];