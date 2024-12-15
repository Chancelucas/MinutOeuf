<?php

namespace App\Core\Exceptions;

class DatabaseException extends \Exception {
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null) {
        parent::__construct("Erreur de base de données : " . $message, $code, $previous);
    }
}
