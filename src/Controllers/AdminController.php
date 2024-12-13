<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\EggModel;
use App\Middleware\AdminMiddleware;

class AdminController extends BaseController {
    private EggModel $eggModel;
    private AdminMiddleware $adminMiddleware;

    public function __construct() {
        $this->eggModel = new EggModel();
        $this->adminMiddleware = new AdminMiddleware();
    }

    public function index() {
        // Vérifier l'authentification admin
        $this->adminMiddleware->handle();

        $eggs = $this->eggModel->getAllEggs();
        return $this->render('admin/index', [
            'title' => 'MinutOeuf - Administration',
            'eggs' => $eggs
        ]);
    }

    public function createEgg() {
        $this->adminMiddleware->handle();
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$this->validateEggData($data)) {
            return $this->json([
                'success' => false,
                'error' => 'Données invalides'
            ]);
        }

        $result = $this->eggModel->createEgg($data);
        return $this->json([
            'success' => $result
        ]);
    }

    public function updateEgg(string $name) {
        $this->adminMiddleware->handle();
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$this->validateEggData($data)) {
            return $this->json([
                'success' => false,
                'error' => 'Données invalides'
            ]);
        }

        $result = $this->eggModel->updateEgg($name, $data);
        return $this->json([
            'success' => $result
        ]);
    }

    public function deleteEgg(string $name) {
        $this->adminMiddleware->handle();
        
        $result = $this->eggModel->deleteEgg($name);
        return $this->json([
            'success' => $result
        ]);
    }

    private function validateEggData(array $data): bool {
        return isset($data['name']) &&
               isset($data['cookingTime']) &&
               isset($data['instructions']) &&
               isset($data['tips']) &&
               is_array($data['instructions']) &&
               is_array($data['tips']);
    }
}
