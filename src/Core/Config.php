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
                    $key = trim($key);
                    $value = trim($value);
                    self::$config[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }

        // Configuration par défaut
        $defaults = [
            'APP_NAME' => 'MinutOeuf',
            'APP_DEBUG' => false,
            'MONGODB_URI' => 'mongodb://localhost:27017',
            'MONGODB_DATABASE' => 'minutoeuf'
        ];

        foreach ($defaults as $key => $value) {
            if (!isset(self::$config[$key])) {
                self::$config[$key] = getenv($key) ?: $value;
                putenv("$key=" . self::$config[$key]);
            }
        }
    }

    public static function get(string $key, $default = null) {
        return self::$config[$key] ?? getenv($key) ?: $default;
    }

    public static function set(string $key, $value) {
        self::$config[$key] = $value;
        putenv("$key=$value");
    }
}
