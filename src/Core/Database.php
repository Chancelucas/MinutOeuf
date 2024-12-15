<?php

namespace App\Core;

use MongoDB\Client;

class Database {
    private static ?Database $instance = null;
    public $database;

    private function __construct() {
        try {
            error_log("[Database] Initializing database connection");
            
            // Vérifier les variables d'environnement
            error_log("[Database] Checking environment variables");
            error_log("[Database] Available environment variables: " . implode(", ", array_keys($_ENV)));
            
            // Essayer différentes méthodes pour obtenir les variables d'environnement
            $uri = getenv('MONGODB_URI') ?: $_ENV['MONGODB_URI'] ?? $_SERVER['MONGODB_URI'] ?? null;
            $dbName = getenv('MONGODB_DATABASE') ?: $_ENV['MONGODB_DATABASE'] ?? $_SERVER['MONGODB_DATABASE'] ?? null;
            
            error_log("[Database] MONGODB_URI from getenv(): " . (getenv('MONGODB_URI') ? 'set' : 'not set'));
            error_log("[Database] MONGODB_URI from _ENV: " . (isset($_ENV['MONGODB_URI']) ? 'set' : 'not set'));
            error_log("[Database] MONGODB_URI from _SERVER: " . (isset($_SERVER['MONGODB_URI']) ? 'set' : 'not set'));
            
            if (empty($uri)) {
                throw new \Exception("Database URI is missing");
            }
            if (empty($dbName)) {
                throw new \Exception("Database name is missing");
            }

            $options = [
                'retryWrites' => true,
                'w' => 'majority',
                'ssl' => true,
                'tls' => true,
                'tlsAllowInvalidCertificates' => true,
                'serverSelectionTimeoutMS' => 5000,
                'connectTimeoutMS' => 10000
            ];

            error_log("[Database] Connection options: " . json_encode($options));
            error_log("[Database] Attempting to connect with URI: " . preg_replace('/mongodb\+srv:\/\/[^:]+:[^@]+@/', 'mongodb+srv://****:****@', $uri));
            
            $client = new Client($uri, $options);
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
