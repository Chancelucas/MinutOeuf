<?php

return [
    'mongodb' => [
        'uri' => getenv('MONGODB_URI'),
        'database' => getenv('MONGODB_DATABASE'),
        'options' => [
            'retryWrites' => true,
            'w' => 'majority',
            'ssl' => true,
            'tls' => true,
            'tlsAllowInvalidCertificates' => true,
            'serverSelectionTimeoutMS' => 5000,
            'connectTimeoutMS' => 10000
        ]
    ]
];
