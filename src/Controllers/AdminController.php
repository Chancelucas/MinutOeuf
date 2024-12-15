<?php

namespace App\Controllers;

use App\Models\Egg;

class AdminController {
    private $eggModel;

    public function __construct() {
        $this->eggModel = new Egg();
    }

    public function index() {
        $eggs = $this->eggModel->getAll();
        require __DIR__ . '/../Views/admin/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'type' => $_POST['type'],
                'cookingTime' => (int)$_POST['cookingTime'],
                'description' => $_POST['description'],
                'instructions' => explode("\n", $_POST['instructions'])
            ];

            $this->eggModel->create($data);
            header('Location: /admin');
            exit;
        }

        require __DIR__ . '/../Views/admin/create.php';
    }

    public function edit($id) {
        $egg = $this->eggModel->findById($id);
        
        if (!$egg) {
            header('Location: /admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'type' => $_POST['type'],
                'cookingTime' => (int)$_POST['cookingTime'],
                'description' => $_POST['description'],
                'instructions' => explode("\n", $_POST['instructions'])
            ];

            $this->eggModel->update($id, $data);
            header('Location: /admin');
            exit;
        }

        require __DIR__ . '/../Views/admin/edit.php';
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->eggModel->delete($id);
        }
        header('Location: /admin');
        exit;
    }
}
