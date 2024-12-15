<?php

return [
    'database' => [
        'uri' => getenv('MONGODB_URI'),
        'name' => getenv('MONGODB_DATABASE'),
    ],
    'app' => [
        'name' => 'MinutOeuf',
        'debug' => getenv('APP_DEBUG') === 'true',
        'env' => getenv('APP_ENV') ?: 'production',
        'url' => getenv('APP_URL'),
    ]
];
