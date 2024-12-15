<?php

require __DIR__ . '/../vendor/autoload.php';

echo "Testing MongoDB connection...\n";

try {
    // Afficher les variables d'environnement (masquer les informations sensibles)
    $uri = getenv('MONGODB_URI');
    $dbName = getenv('MONGODB_DATABASE');
    
    if (empty($uri) || empty($dbName)) {
        throw new Exception("Missing required environment variables");
    }
    
    $maskedUri = preg_replace('/mongodb\+srv:\/\/[^:]+:[^@]+@/', 'mongodb+srv://****:****@', $uri);
    echo "MONGODB_URI: " . $maskedUri . "\n";
    echo "MONGODB_DATABASE: " . $dbName . "\n";
    echo "APP_ENV: " . getenv('APP_ENV') . "\n";
    echo "APP_DEBUG: " . getenv('APP_DEBUG') . "\n";
    echo "APP_URL: " . getenv('APP_URL') . "\n";

    // Options de connexion MongoDB
    $options = [
        'retryWrites' => true,
        'w' => 'majority',
        'ssl' => true,
        'tls' => true,
        'tlsAllowInvalidCertificates' => true,
        'serverSelectionTimeoutMS' => 5000,
        'connectTimeoutMS' => 10000
    ];

    echo "Connection options: " . json_encode($options) . "\n";

    // Tester la connexion MongoDB
    $client = new MongoDB\Client($uri, $options);
    $database = $client->selectDatabase($dbName);
    
    // Tester une commande ping
    $result = $database->command(['ping' => 1]);
    echo "Ping successful: " . json_encode($result) . "\n";
    
    // Lister les collections
    $collections = $database->listCollections();
    echo "Collections in database:\n";
    foreach ($collections as $collection) {
        echo "- " . $collection->getName() . "\n";
    }
    
    // Compter les documents dans la collection eggs
    $count = $database->eggs->countDocuments();
    echo "Number of documents in eggs collection: " . $count . "\n";
    
    echo "MongoDB connection test successful!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
