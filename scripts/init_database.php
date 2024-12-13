<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

// Connexion à MongoDB
$mongoClient = new Client("mongodb+srv://wadyx38:k7NYq73UdTaU9vwH@cluster0.4xafp.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");
$database = $mongoClient->minutoeuf;
$collection = $database->eggs;

// Données des œufs
$eggs = [
    [
        'name' => 'À la coque',
        'cookingTime' => 3,
        'instructions' => [
            'Portez l\'eau à ébullition',
            'Plongez l\'œuf délicatement dans l\'eau',
            'Laissez cuire pendant 3 minutes',
            'Retirez l\'œuf et placez-le dans un coquetier'
        ],
        'tips' => [
            'Utilisez des œufs à température ambiante',
            'Commencez à chronométrer dès que l\'eau bout',
            'Servez immédiatement avec des mouillettes'
        ]
    ],
    [
        'name' => 'Mollet',
        'cookingTime' => 6,
        'instructions' => [
            'Portez l\'eau à ébullition',
            'Plongez l\'œuf délicatement dans l\'eau',
            'Laissez cuire pendant 6 minutes',
            'Plongez l\'œuf dans l\'eau froide pour arrêter la cuisson'
        ],
        'tips' => [
            'Le blanc doit être ferme mais le jaune crémeux',
            'L\'eau froide facilite l\'écalage',
            'Parfait pour les salades'
        ]
    ],
    [
        'name' => 'Dur',
        'cookingTime' => 10,
        'instructions' => [
            'Portez l\'eau à ébullition',
            'Plongez l\'œuf délicatement dans l\'eau',
            'Laissez cuire pendant 10 minutes',
            'Plongez l\'œuf dans l\'eau froide'
        ],
        'tips' => [
            'Idéal pour la préparation à l\'avance',
            'Peut se conserver plusieurs jours au réfrigérateur',
            'Parfait pour les sandwichs et salades'
        ]
    ],
    [
        'name' => 'Poché',
        'cookingTime' => 3,
        'instructions' => [
            'Portez l\'eau à frémissement',
            'Créez un tourbillon dans l\'eau',
            'Cassez l\'œuf au centre du tourbillon',
            'Laissez cuire 3 minutes'
        ],
        'tips' => [
            'Ajoutez un peu de vinaigre dans l\'eau',
            'L\'œuf doit être très frais',
            'Le blanc doit envelopper le jaune'
        ]
    ],
    [
        'name' => 'Au plat',
        'cookingTime' => 3,
        'instructions' => [
            'Chauffez une poêle à feu moyen',
            'Ajoutez une noix de beurre',
            'Cassez l\'œuf dans la poêle',
            'Cuisez 3 minutes'
        ],
        'tips' => [
            'Le jaune doit rester coulant',
            'Salez et poivrez à la fin',
            'Servir sur du pain grillé'
        ]
    ]
];

// Suppression des données existantes
$collection->deleteMany([]);

// Insertion des nouvelles données
$result = $collection->insertMany($eggs);

echo "Base de données initialisée avec succès !\n";
echo count($result->getInsertedIds()) . " œufs ont été ajoutés.\n";
