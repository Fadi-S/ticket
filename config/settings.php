<?php

use Carbon\Carbon;

return [
    'max_reservations_per_month' => 3,

    'start_of_week' => Carbon::FRIDAY,

    'allow_for_exceptions' => true,
    'hours_to_allow_for_exception' => 16,

    'google_api_token' => env('GOOGLE_API_TOKEN', null),
];