<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;
use config\MongoDBAtlasManager;

try {
    echo "Testing database connection...\n";
    
    // Test direct connection with MongoDBAtlasManager
    echo "Testing MongoDBAtlasManager...\n";
    $manager = new MongoDBAtlasManager();
    $db = $manager->getDatabase();
    $result = $db->command(['ping' => 1]);
    echo "MongoDBAtlasManager connection successful!\n";
    
    // Test connection through Database class
    echo "\nTesting Database class...\n";
    $database = Database::getInstance();
    $db = $database->getDatabase();
    $result = $db->command(['ping' => 1]);
    echo "Database class connection successful!\n";
    
    // Try to read some data
    echo "\nTrying to read data...\n";
    $collection = $db->eggs;
    $documents = $collection->find()->toArray();
    echo "Found " . count($documents) . " documents in eggs collection\n";
    
    echo "\nAll tests passed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
