<?php

namespace App\Core;

use MongoDB\Client;
use MongoDB\Database as MongoDatabase;
use App\Core\Config;

class Database {
    private static ?Database $instance = null;
    private MongoDatabase $database;

    private function __construct() {
        $uri = Config::get('MONGODB_URI');
        $dbName = Config::get('MONGODB_DATABASE');
        
        if (empty($uri) || empty($dbName)) {
            throw new \RuntimeException('MongoDB configuration is missing. Please check your .env file.');
        }

        $client = new Client($uri);
        $this->database = $client->$dbName;
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDatabase(): MongoDatabase {
        return $this->database;
    }
}
