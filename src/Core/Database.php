<?php

namespace App\Core;

use MongoDB\Client;

class Database {
    private static ?Database $instance = null;
    public $database;

    private function __construct() {
        try {
            error_log("[Database] Initializing database connection");
            
            // Charger la configuration
            $config = require __DIR__ . '/../../config/database.php';
            $mongoConfig = $config['mongodb'];
            
            // Vérifier les variables d'environnement
            error_log("[Database] Checking environment variables");
            $uri = $mongoConfig['uri'];
            $dbName = $mongoConfig['database'];
            
            error_log("[Database] MONGODB_URI: " . ($uri ? 'set' : 'not set'));
            error_log("[Database] MONGODB_DATABASE: " . ($dbName ? 'set' : 'not set'));
            error_log("[Database] Connection options: " . json_encode($mongoConfig['options']));
            
            if (empty($uri)) {
                throw new \Exception("Database URI is missing");
            }
            if (empty($dbName)) {
                throw new \Exception("Database name is missing");
            }

            error_log("[Database] Attempting to connect with URI: " . preg_replace('/mongodb\+srv:\/\/[^:]+:[^@]+@/', 'mongodb+srv://****:****@', $uri));
            
            $client = new Client($uri, $mongoConfig['options']);
            $this->database = $client->selectDatabase($dbName);
            
            // Test the connection
            error_log("[Database] Testing connection with ping command");
            $result = $this->database->command(['ping' => 1]);
            error_log("[Database] Ping result: " . json_encode($result));
            error_log("[Database] Connection successful");
            
        } catch (\Exception $e) {
            error_log("[Database] Connection error: " . $e->getMessage());
            error_log("[Database] Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
