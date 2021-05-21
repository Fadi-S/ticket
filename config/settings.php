<?php

return [
    'event' => [
        'allow_for_exceptions' => true,
        'max_reservations_per_period' => 1,
    ],

    'mass' => [
        'max_reservations_per_period' => 2,
    ],

    'vesper' => [
        'max_reservations_per_period' => 1,
    ],

    'google_api_token' => env('GOOGLE_API_TOKEN', null),

    'light_theme_photo' => env('LIGHT_THEME_PHOTO', '/images/stmary_jesus-500-min.jpg'),
    'dark_theme_photo' => env('DARK_THEME_PHOTO', '/images/stmary_jesus-500-min.jpg'),

    'users_limit' => 10,

    'allow_users_to_create_accounts' => env('ALLOW_USERS_TO_CREATE_ACCOUNTS', true),
];
