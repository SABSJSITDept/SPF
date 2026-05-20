<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sms' => [
        'url' => env('SMS_API_URL', 'http://www.bulksms.saakshisoftware.in/api/mt/SendSMS'),
        'user' => env('SMS_USER', 'JainSangh'),
        'password' => env('SMS_PASSWORD', 'Jain@12'),
        'sender' => env('SMS_SENDER_ID', 'ABSJHO'),
        'channel' => env('SMS_CHANNEL', 'trans'),
        'dcs' => env('SMS_DCS', '0'),
        'flashsms' => env('SMS_FLASHSMS', '0'),
        'route' => env('SMS_ROUTE', '4'),
        'peid' => env('SMS_PEID', '1001071123690830532'),
        'template_id' => env('SMS_TEMPLATE_ID', '1007421822718405594'),
    ],

];
