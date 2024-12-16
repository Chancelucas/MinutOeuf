<?php

namespace App\Core;

use config\MongoDBAtlasManager;

class Database {
    private static ?Database $instance = null;
    public $database;
    private MongoDBAtlasManager $mongoManager;

    private function __construct() {
        try {
            error_log("[Database] Initializing database connection");
            
            $this->mongoManager = new MongoDBAtlasManager();
            $this->database = $this->mongoManager->getDatabase();
            
            // Test the connection
            error_log("[Database] Testing connection with ping command");
            $this->database->command(['ping' => 1]);
            error_log("[Database] Connection successful");
            
        } catch (\Exception $e) {
            error_log("[Database] Connection error: " . $e->getMessage());
            throw $e;
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDatabase() {
        return $this->database;
    }
}
