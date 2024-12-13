<?php

namespace App\Controllers;

use App\Core\BaseEggController;

class EggPageController extends BaseEggController {
    /**
     * Page d'accueil
     */
    public function home() {
        $eggs = $this->eggModel->getAllEggs();
        return $this->render('eggs/home', [
            'title' => 'MinutOeuf - Accueil',
            'eggs' => $eggs
        ]);
    }

    /**
     * Affiche les détails d'un œuf
     */
    public function show(string $eggName) {
        $egg = $this->eggModel->getEggByName($eggName);
        
        if (!$egg) {
            $this->handle404();
            return;
        }

        return $this->render('eggs/show', [
            'title' => 'MinutOeuf - ' . $egg['name'],
            'egg' => $egg,
            'currentEgg' => $egg['normalizedName'] // Utiliser le nom normalisé pour la comparaison
        ]);
    }

    /**
     * Gestion de la page 404
     */
    private function handle404() {
        http_response_code(404);
        require_once ROOT . '/src/Views/errors/404.php';
    }
}
