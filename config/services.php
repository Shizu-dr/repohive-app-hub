<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'repohive_sms' => [
        'base_url' => env('REPOHIVE_SMS_API_BASE'),
        'token'    => env('REPOHIVE_SMS_API_TOKEN'),
    ],

    'repohive_email' => [
        'base_url' => env('REPOHIVE_EMAIL_API_BASE'),
        'token'    => env('REPOHIVE_EMAIL_API_TOKEN'),
    ],

    'deepseek' => [
        'key'      => env('DEEPSEEK_API_KEY'),
        'base_url' => env('DEEPSEEK_API_BASE'),
    ],

    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI'),
    ],

];
