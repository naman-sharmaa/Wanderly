<?php

return [

    'default' => env('MAIL_MAILER', 'log'),

    'mailers' => [

        'log' => [
            'transport' => 'log',
            'channel'   => env('MAIL_LOG_CHANNEL'),
        ],

        'smtp' => [
            'transport'  => 'smtp',
            'scheme'     => env('MAIL_SCHEME'),
            'url'        => env('MAIL_URL'),
            'host'       => env('MAIL_HOST', '127.0.0.1'),
            'port'       => env('MAIL_PORT', 2525),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username'   => env('MAIL_USERNAME'),
            'password'   => env('MAIL_PASSWORD'),
            'timeout'    => null,
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path'      => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'array' => [
            'transport' => 'array',
        ],

    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@travelplanner.ai'),
        'name'    => env('MAIL_FROM_NAME', 'Wanderly'),
    ],

];
