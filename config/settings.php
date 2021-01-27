<?php

use Carbon\Carbon;

return [
    'max_reservations_per_month' => 5,

    'start_of_week' => Carbon::FRIDAY,

    'allow_for_exceptions' => true,
    'hours_to_allow_for_exception' => 12,

    'google_api_token' => env('GOOGLE_API_TOKEN', 'AIzaSyAnlZ_bP0GMRy2NTVLqU4tNZQ-J_hYS_xg'),
];