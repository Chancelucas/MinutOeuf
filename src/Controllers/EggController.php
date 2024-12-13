<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\EggModel;

class EggController extends BaseController {
    private EggModel $eggModel;

    public function __construct() {
        $this->eggModel = new EggModel();
    }

    /**
     * Récupère la liste des œufs pour la navbar
     */
    public function getNavbarEggs(): array
    {
        try {
            return $this->eggModel->getAllEggs();
        } catch (\Exception $e) {
            error_log('Erreur lors de la récupération des œufs : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * API : Récupère tous les œufs
     */
    public function getAllEggs() {
        try {
            $eggs = $this->eggModel->getAllEggs();
            if (!$eggs) {
                return $this->json([
                    'success' => false,
                    'error' => 'Aucun œuf trouvé'
                ], 404);
            }
            return $this->json([
                'success' => true,
                'data' => $eggs
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API : Récupère un œuf par son nom
     */
    public function getEggByName(string $name) {
        try {
            $egg = $this->eggModel->getEggByName($name);
            if (!$egg) {
                return $this->json([
                    'success' => false,
                    'error' => 'Œuf non trouvé'
                ], 404);
            }
            return $this->json([
                'success' => true,
                'data' => $egg
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }
}
