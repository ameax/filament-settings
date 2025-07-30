<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Settings Definitions
    |--------------------------------------------------------------------------
    |
    | Define your settings groups and their settings here.
    | Each group can have multiple settings with various types and validations.
    |
    */
    'definitions' => [
        // Example group
        'general' => [
            'label' => 'General Settings',
            'icon' => 'heroicon-o-cog',
            'order' => 1,
            'settings' => [
                'app.name' => [
                    'label' => 'Application Name',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'tab' => 'general',
                    'order' => 1,
                    'helper' => 'The name of your application',
                    'placeholder' => 'My App',
                    'default' => env('APP_NAME', 'Laravel'),
                ],
                'app.email' => [
                    'label' => 'Contact Email',
                    'type' => 'email',
                    'validation' => 'required|email|max:255',
                    'tab' => 'general',
                    'order' => 2,
                    'helper' => 'Main contact email address',
                    'placeholder' => 'contact@example.com',
                ],
                'app.timezone' => [
                    'label' => 'Timezone',
                    'type' => 'select',
                    'validation' => 'required|timezone',
                    'tab' => 'general',
                    'order' => 3,
                    'options' => [
                        'UTC' => 'UTC',
                        'Europe/Berlin' => 'Europe/Berlin',
                        'America/New_York' => 'America/New York',
                        // Add more as needed
                    ],
                    'default' => 'UTC',
                ],
            ],
        ],
    ],
];