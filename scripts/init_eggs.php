<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Egg;

echo "Starting database initialization...\n";
echo "PHP version: " . PHP_VERSION . "\n";
echo "MongoDB extension version: " . phpversion("mongodb") . "\n";

try {
    $eggModel = new Egg();
    
    // Utiliser la méthode resetData() au lieu d'accéder directement à la collection
    if ($eggModel->resetData()) {
        echo "Initialisation terminée avec succès!\n";
    } else {
        echo "Erreur lors de l'initialisation de la base de données\n";
    }
} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
