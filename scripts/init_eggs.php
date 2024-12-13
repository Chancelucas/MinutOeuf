<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\EggModel;

try {
    $eggModel = new EggModel();

    // Supprimer toutes les données existantes
    $eggModel->getCollection()->deleteMany([]);

    // Données des œufs
    $eggs = [
        [
            'name' => 'À la coque',
            'cookingTime' => 3,
            'instructions' => [
                'Portez l\'eau à ébullition',
                'Plongez délicatement l\'œuf dans l\'eau bouillante',
                'Laissez cuire pendant 3 minutes',
                'Sortez l\'œuf et placez-le dans un coquetier'
            ],
            'tips' => [
                'Utilisez des œufs à température ambiante',
                'Ajoutez une pincée de sel dans l\'eau',
                'Pour arrêter la cuisson, plongez l\'œuf dans l\'eau froide quelques secondes'
            ]
        ],
        [
            'name' => 'Mollet',
            'cookingTime' => 6,
            'instructions' => [
                'Portez l\'eau à ébullition',
                'Plongez délicatement l\'œuf dans l\'eau bouillante',
                'Laissez cuire pendant 6 minutes',
                'Plongez l\'œuf dans l\'eau froide pour arrêter la cuisson'
            ],
            'tips' => [
                'Utilisez des œufs à température ambiante',
                'Le jaune doit être coulant et le blanc ferme',
                'Idéal pour les salades'
            ]
        ],
        [
            'name' => 'Dur',
            'cookingTime' => 9,
            'instructions' => [
                'Portez l\'eau à ébullition',
                'Plongez délicatement l\'œuf dans l\'eau bouillante',
                'Laissez cuire pendant 9 minutes',
                'Plongez l\'œuf dans l\'eau froide pour arrêter la cuisson'
            ],
            'tips' => [
                'Parfait pour les pique-niques',
                'Pour éviter le cercle verdâtre autour du jaune, ne dépassez pas le temps de cuisson',
                'Se conserve une semaine au réfrigérateur'
            ]
        ],
        [
            'name' => 'Poché',
            'cookingTime' => 3,
            'instructions' => [
                'Portez l\'eau à frémissement',
                'Créez un tourbillon dans l\'eau',
                'Cassez l\'œuf dans un ramequin',
                'Versez délicatement l\'œuf au centre du tourbillon',
                'Laissez cuire 3 minutes'
            ],
            'tips' => [
                'Ajoutez un filet de vinaigre dans l\'eau',
                'L\'eau ne doit pas bouillir, juste frémir',
                'Utilisez des œufs très frais'
            ]
        ],
        [
            'name' => 'Au plat',
            'cookingTime' => 3,
            'instructions' => [
                'Faites chauffer une poêle à feu moyen',
                'Ajoutez une noisette de beurre',
                'Cassez l\'œuf dans la poêle',
                'Couvrez et laissez cuire 3 minutes'
            ],
            'tips' => [
                'Le blanc doit être cuit mais le jaune coulant',
                'Salez et poivrez à la fin de la cuisson',
                'Pour un œuf plus cuit, prolongez la cuisson d\'une minute'
            ]
        ]
    ];

    // Insérer les données
    $success = 0;
    foreach ($eggs as $egg) {
        if ($eggModel->create($egg)) {
            $success++;
        }
    }

    echo "Données insérées avec succès !\n";
    echo "$success œufs ajoutés sur " . count($eggs) . " total.\n";

} catch (\Exception $e) {
    echo "Erreur lors de l'initialisation : " . $e->getMessage() . "\n";
    exit(1);
}
