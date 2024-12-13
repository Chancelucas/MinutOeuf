<?php

namespace App\Core;

use MongoDB\Client;
use MongoDB\Database as MongoDatabase;

class Database {
    private static ?Database $instance = null;
    private MongoDatabase $database;

    private function __construct() {
        $config = require __DIR__ . '/../../config/database.php';
        $client = new Client($config['mongodb']['uri']);
        $this->database = $client->{$config['mongodb']['database']};
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
