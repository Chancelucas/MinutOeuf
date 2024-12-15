<?php

function getEnvVar($name) {
    // Try different ways to get environment variables
    return $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name) ?? null;
}

return [
    'mongodb' => [
        'uri' => getEnvVar('MONGODB_URI'),
        'database' => getEnvVar('MONGODB_DATABASE'),
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
