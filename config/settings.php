<?php

return [
    'event' => [
        'allow_for_exceptions' => env('EVENT_ALLOW_EXCEPTION', true),
        'max_reservations_per_period' => env('EVENT_MAX_RESERVATIONS_PER_PERIOD', 1),
    ],

    'mass' => [
        'max_reservations_per_period' => env('MASS_MAX_RESERVATIONS_PER_PERIOD', 1),
    ],

    'vesper' => [
        'max_reservations_per_period' => env('Vesper_MAX_RESERVATIONS_PER_PERIOD', 1),
    ],

    'google_api_token' => env('GOOGLE_API_TOKEN'),

    'light_theme_photo' => env('LIGHT_THEME_PHOTO'),
    'dark_theme_photo' => env('DARK_THEME_PHOTO'),

    'users_limit' => env('CREATE_USERS_LIMIT', 10),

    'allow_users_to_create_accounts' => env('ALLOW_USERS_TO_CREATE_ACCOUNTS', true),

    'full_name_number' => env('FULL_NAME_NUMBER', 3),
];
