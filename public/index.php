<?php

require_once __DIR__ . '/../vendor/autoload.php';
session_start();

// Charger les routes
require_once __DIR__ . '/../config/init.php';

// Démarrer le routeur
$router = App\Core\Router::getInstance();
$router->dispatch();
