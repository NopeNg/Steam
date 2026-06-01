<?php

use App\Models\Player;

return [
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'player'), // Đổi mặc định sang player
        'passwords' => env('AUTH_PASSWORD_BROKER', 'players'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'players', // Dùng luôn provider players cho web
        ],
        'player' => [
            'driver' => 'session',
            'provider' => 'players',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        'players' => [
            'driver' => 'eloquent',
            'model' => Player::class,
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    'passwords' => [
        'players' => [
            'provider' => 'players',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];
