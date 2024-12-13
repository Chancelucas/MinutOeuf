<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

// Connexion à MongoDB Atlas
$client = new Client("mongodb+srv://wadyx38:k7NYq73UdTaU9vwH@cluster0.4xafp.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");
$collection = $client->minutoeuf->eggs;

// Données initiales
$initialEggs = [
    [
        'name' => 'À la coque',
        'minutes' => 3,
        'instructions' => "Plongez l'œuf dans l'eau bouillante pendant 3 minutes",
        'image' => null
    ],
    [
        'name' => 'Mollet',
        'minutes' => 6,
        'instructions' => "Plongez l'œuf dans l'eau bouillante pendant 6 minutes",
        'image' => null
    ],
    [
        'name' => 'Dur',
        'minutes' => 9,
        'instructions' => "Plongez l'œuf dans l'eau bouillante pendant 9 minutes",
        'image' => null
    ]
];

// Supprimer les données existantes
$collection->deleteMany([]);

// Insérer les nouvelles données
try {
    $result = $collection->insertMany($initialEggs);
    echo "✅ " . count($initialEggs) . " œufs ont été importés avec succès!\n";
} catch (\Exception $e) {
    echo "❌ Erreur lors de l'importation : " . $e->getMessage() . "\n";
}
