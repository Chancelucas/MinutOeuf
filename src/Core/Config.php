<?php

namespace App\Core;

class Config {
    private static array $config = [];

    public static function load() {
        // Charger les variables d'environnement depuis le fichier .env
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    list($key, $value) = explode('=', $line, 2);
                    self::$config[trim($key)] = trim($value);
                }
            }
        }

        // Configuration par défaut
        self::$config = array_merge([
            'mongodb_uri' => getenv('MONGODB_URI') ?: 'mongodb://localhost:27017',
            'app_name' => 'MinutOeuf',
            'debug' => false
        ], self::$config);
    }

    public static function get(string $key, $default = null) {
        return self::$config[$key] ?? $default;
    }

    public static function set(string $key, $value) {
        self::$config[$key] = $value;
    }
}
