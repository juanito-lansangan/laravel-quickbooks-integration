<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'quickbooks' => [
        'auth_mode' => env('QBO_AUTH_MODE', 'oauth2'),
        'client_id' => env('QBO_CLIENT_ID',''),
        'client_secret' => env('QBO_CLIENT_SECRET', ''),
        'base_url' => env('QBO_BASE_URL', 'https://sandbox-quickbooks.api.intuit.com'),
        'auth_url' => env('QBO_AUTH_URL', 'https://appcenter.intuit.com/connect/oauth2'),
        'token_url' => env('QBO_ACCESS_TOKEN_URL', 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer'),
        'response_type' => 'code',
        'scope' => env('QBO_SCOPE', 'com.intuit.quickbooks.accounting'),
        'grant_type' => env('QBO_AUTH_GRANT_TYPE', 'authorization_code'),
        'redirect_uri' => env('QBO_REDIRECT_URI','http://localhost:8091/qbo-callback')
    ]

];
