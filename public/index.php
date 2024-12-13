<?php

// Activer l'affichage des erreurs pour le développement
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Définir le mode debug
define('APP_DEBUG', true);

// Définir le chemin racine
define('ROOT', dirname(__DIR__));

// Inclure l'autoloader de Composer
require_once ROOT . '/vendor/autoload.php';

// Inclure l'autoloader personnalisé
require_once ROOT . '/src/Core/Autoloader.php';
App\Core\Autoloader::register();

// Charger la configuration
App\Core\Config::load();

// Démarrer le routeur
$router = App\Core\Router::getInstance();
$router->dispatch();
