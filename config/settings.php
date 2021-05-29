<?php

return [
    'google_api_token' => env('GOOGLE_API_TOKEN'),
    'google_site_key' => env('GOOGLE_SITE_KEY'),
    'google_private_site_key' => env('PRIVATE_GOOGLE_SITE_KEY'),

    'light_theme_photo' => env('LIGHT_THEME_PHOTO'),
    'dark_theme_photo' => env('DARK_THEME_PHOTO'),

    'users_limit' => env('CREATE_USERS_LIMIT', 10),

    'allow_users_to_create_accounts' => env('ALLOW_USERS_TO_CREATE_ACCOUNTS', true),

    'full_name_number' => env('FULL_NAME_NUMBER', 3),

    'arabic_name_only' => env('ARABIC_NAME_ONLY', false),

    'logo' => env('LOGO', false),
];
