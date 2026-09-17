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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'timeout' => 90,
    ],

    // Dominio base bajo el que se sirven las webs publicadas por subdominio
    // (ver routes/web.php y PublicSiteController). En local usa "localhost"
    // (Chrome resuelve *.localhost a 127.0.0.1 sin tocar nada); en producción
    // debe ser "cvxpress.es" una vez el dominio esté registrado y con DNS
    // comodín (*.cvxpress.es) apuntando al servidor.
    'public_sites' => [
        'domain' => env('PUBLIC_SITES_DOMAIN', 'cvxpress.es'),
    ],

];
