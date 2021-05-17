<?php

return [
    'max_reservations_per_month' => 1,

    'allow_for_exceptions' => true,
    'hours_to_allow_for_exception' => 16,

    'google_api_token' => env('GOOGLE_API_TOKEN', null),

    'light_theme_photo' => env('LIGHT_THEME_PHOTO', '/images/resurrection-500-min.jpg'),
    'dark_theme_photo' => env('DARK_THEME_PHOTO', '/images/resurrection-500-min.jpg'),
];
