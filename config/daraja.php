<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Safaricom Daraja API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Safaricom M-Pesa Daraja API integration.
    | Get your credentials from: https://developer.safaricom.co.ke/
    |
    */

    'environment' => env('DARAJJA_ENVIRONMENT', 'sandbox'), // 'sandbox' or 'production'

    'consumer_key' => env('DARAJJA_CONSUMER_KEY', ''),
    
    'consumer_secret' => env('DARAJJA_CONSUMER_SECRET', ''),

    'short_code' => env('DARAJJA_SHORT_CODE', ''),

    'passkey' => env('DARAJJA_PASSKEY', ''),

    'callback_url' => env('DARAJJA_CALLBACK_URL', env('APP_URL') . '/api/mpesa/callback'),

    /*
    |--------------------------------------------------------------------------
    | STK Push Configuration
    |--------------------------------------------------------------------------
    */
    'stk_push' => [
        'timeout' => 30, // Timeout in seconds for STK Push
    ],
];

