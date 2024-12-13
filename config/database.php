<?php

return [
    'mongodb' => [
        'uri' => getenv('MONGODB_URI'),
        'database' => getenv('MONGODB_DATABASE')
    ]
];
