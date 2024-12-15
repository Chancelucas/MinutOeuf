<?php

return [
    'database' => [
        'uri' => getenv('MONGODB_URI') ?: 'mongodb+srv://wadyx38:k7NYq73UdTaU9vwH@cluster0.4xafp.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0&tls=true&authSource=admin',
        'name' => getenv('MONGODB_DATABASE') ?: 'minutoeuf',
    ],
    'app' => [
        'name' => 'MinutOeuf',
        'debug' => true,
        'url' => getenv('APP_URL') ?: 'http://localhost:8080',
    ]
];
