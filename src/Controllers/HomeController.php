<?php

namespace App\Controllers;

use App\Core\BaseEggController;
use App\Models\EggModel;

class HomeController extends BaseEggController
{
    /**
     * Page d'accueil
     */
    public function index()
    {
        $eggs = $this->eggModel->getAllEggs();
        return $this->render('eggs/home', [
            'title' => 'MinutOeuf - Accueil',
            'eggs' => $eggs
        ]);
    }

    /**
     * Récupère les données d'un œuf spécifique (pour l'API)
     */
    public function getEgg($id)
    {
        $egg = $this->eggModel->getEggByName($id);
        
        if (!$egg) {
            http_response_code(404);
            return json_encode(['error' => 'Œuf non trouvé']);
        }

        return json_encode($egg);
    }

    /**
     * Récupère la liste de tous les œufs (pour l'API)
     */
    public function getAllEggs()
    {
        $eggs = $this->eggModel->getAllEggs();
        return json_encode($eggs);
    }
}
