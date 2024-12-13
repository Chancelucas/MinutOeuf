<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\EggModel;

class AdminController extends BaseController {
    private EggModel $eggModel;

    public function __construct() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        
        $this->eggModel = new EggModel();
    }

    public function index() {
        $eggs = $this->eggModel->findAll();
        return $this->render('admin/index', [
            'title' => 'MinutOeuf - Administration',
            'eggs' => $eggs
        ]);
    }

    public function createEgg() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$this->validateEggData($data)) {
            return $this->json([
                'success' => false,
                'error' => 'Données invalides'
            ]);
        }

        $result = $this->eggModel->create($data);
        return $this->json([
            'success' => $result
        ]);
    }

    public function updateEgg(string $name) {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$this->validateEggData($data)) {
            return $this->json([
                'success' => false,
                'error' => 'Données invalides'
            ]);
        }

        $result = $this->eggModel->update(['name' => $name], $data);
        return $this->json([
            'success' => $result
        ]);
    }

    public function deleteEgg(string $name) {
        $result = $this->eggModel->delete(['name' => $name]);
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
