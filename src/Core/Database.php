<?php

namespace App\Core;

use MongoDB\Client;

class Database {
    private static ?Database $instance = null;
    public $database;

    private function __construct() {
        try {
            error_log("Initializing database connection");
            $config = require __DIR__ . '/../../config/config.php';
            
            error_log("Database config: " . json_encode($config['database']));
            
            if (empty($config['database']['uri']) || empty($config['database']['name'])) {
                throw new \Exception("Database configuration is incomplete");
            }

            $client = new Client($config['database']['uri']);
            $this->database = $client->selectDatabase($config['database']['name']);
            
            // Test the connection
            $this->database->command(['ping' => 1]);
            error_log("Database connection successful");
        } catch (\Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
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
