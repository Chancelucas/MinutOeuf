<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\EggModel;
use App\Middleware\AdminMiddleware;

class DashboardController extends BaseController
{
    private EggModel $eggModel;
    private AdminMiddleware $adminMiddleware;

    public function __construct()
    {
        $this->eggModel = new EggModel();
        $this->adminMiddleware = new AdminMiddleware();
    }

    /**
     * Affiche le tableau de bord administrateur
     */
    public function index()
    {
        // Vérifier l'authentification admin
        $this->adminMiddleware->handle();

        // Récupérer les statistiques
        $totalEggs = $this->eggModel->countEggs();
        $eggs = $this->eggModel->getAllEggs();

        // Rendre la vue du tableau de bord
        return $this->render('admin/dashboard', [
            'title' => 'MinutOeuf - Tableau de bord',
            'totalEggs' => $totalEggs,
            'eggs' => $eggs,
            'user' => $_SESSION['user']
        ]);
    }

    /**
     * Ajoute un nouvel œuf
     */
    public function addEgg()
    {
        $this->adminMiddleware->handle();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return json_encode(['error' => 'Méthode non autorisée']);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            http_response_code(400);
            return json_encode(['error' => 'Données invalides']);
        }

        $result = $this->eggModel->createEgg($data);
        
        if ($result) {
            return json_encode(['success' => true]);
        } else {
            http_response_code(500);
            return json_encode(['error' => 'Erreur lors de l\'ajout de l\'œuf']);
        }
    }

    /**
     * Supprime un œuf
     */
    public function deleteEgg($name)
    {
        $this->adminMiddleware->handle();

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            return json_encode(['error' => 'Méthode non autorisée']);
        }

        $result = $this->eggModel->deleteEgg($name);
        
        if ($result) {
            return json_encode(['success' => true]);
        } else {
            http_response_code(500);
            return json_encode(['error' => 'Erreur lors de la suppression de l\'œuf']);
        }
    }
}
