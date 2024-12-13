<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\EggModel;
use App\Middleware\AdminMiddleware;

class DashboardController extends BaseController {
    private EggModel $eggModel;
    private AdminMiddleware $adminMiddleware;

    public function __construct() {
        $this->eggModel = new EggModel();
        $this->adminMiddleware = new AdminMiddleware();
    }

    public function index() {
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
}
