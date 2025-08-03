<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Prelude API Key
    |--------------------------------------------------------------------------
    |
    | Your Prelude API key. You can find this in your Prelude dashboard.
    | It's recommended to store this in your .env file.
    |
    */
    'api_key' => env('PRELUDE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Prelude Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Prelude API. This should typically not be changed
    | unless you're using a custom Prelude instance.
    |
    */
    'base_url' => env('PRELUDE_BASE_URL', 'https://api.prelude.so'),

    /*
    |--------------------------------------------------------------------------
    | Default Options
    |--------------------------------------------------------------------------
    |
    | Default options that will be applied to all Prelude SDK operations.
    |
    */
    'defaults' => [
        'retry_attempts' => env('PRELUDE_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('PRELUDE_RETRY_DELAY', 1000), // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout for API requests in seconds.
    |
    */
    'timeout' => env('PRELUDE_TIMEOUT', 30),
];