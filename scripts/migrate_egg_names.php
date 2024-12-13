<?php

define('ROOT', dirname(__DIR__));
require_once ROOT . '/vendor/autoload.php';

use App\Core\Database;
use MongoDB\Client;

echo "Début de la migration des noms d'œufs...\n";

try {
    // Chargement des variables d'environnement
    $dotenv = \Dotenv\Dotenv::createImmutable(ROOT);
    $dotenv->load();

    // Connexion à la base de données
    $mongoUri = $_ENV['MONGODB_URI'] ?? 'mongodb://localhost:27017';
    $dbName = $_ENV['MONGODB_DATABASE'] ?? 'minutoeuf';
    
    $client = new Client($mongoUri);
    $database = $client->selectDatabase($dbName);
    $collection = $database->eggs;

    // Fonction de normalisation (copie de celle dans EggModel)
    function normalizeName(string $name): string {
        $normalized = mb_strtolower($name, 'UTF-8');
        $normalized = iconv('UTF-8', 'ASCII//TRANSLIT', $normalized);
        return preg_replace('/[^a-z0-9]/', '', $normalized);
    }

    // Récupération de tous les œufs
    $cursor = $collection->find();
    $updateCount = 0;
    $errorCount = 0;

    foreach ($cursor as $egg) {
        try {
            // Création du nom normalisé
            $normalizedName = normalizeName($egg->name);
            
            // Mise à jour du document
            $result = $collection->updateOne(
                ['_id' => $egg->_id],
                ['$set' => ['normalizedName' => $normalizedName]]
            );

            if ($result->getModifiedCount() > 0) {
                echo "Mise à jour réussie pour : {$egg->name} -> {$normalizedName}\n";
                $updateCount++;
            }
        } catch (Exception $e) {
            echo "Erreur lors de la mise à jour de {$egg->name} : {$e->getMessage()}\n";
            $errorCount++;
        }
    }

    echo "\nMigration terminée !\n";
    echo "Œufs mis à jour : {$updateCount}\n";
    echo "Erreurs rencontrées : {$errorCount}\n";

} catch (Exception $e) {
    echo "Erreur fatale : " . $e->getMessage() . "\n";
    exit(1);
}
