<?php

namespace App\Core;

class ErrorHandler {
    public static function handleException(\Throwable $exception): void {
        error_log($exception->getMessage());
        
        if (filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN)) {
            echo "<h1>Une erreur est survenue</h1>";
            echo "<p>" . $exception->getMessage() . "</p>";
            echo "<pre>" . $exception->getTraceAsString() . "</pre>";
        } else {
            require dirname(__DIR__) . '/Views/errors/500.php';
        }
    }

    public static function handleError($errno, $errstr, $errfile, $errline): bool {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
