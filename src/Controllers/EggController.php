<?php

namespace App\Controllers;

use App\Services\EggService;
use App\Core\Exceptions\DatabaseException;
use App\Core\Exceptions\NotFoundException;

class EggController {
    private EggService $eggService;

    public function __construct() {
        $this->eggService = new EggService();
    }

    public function index(): void {
        try {
            $eggs = $this->eggService->getAllEggs();
            require dirname(__DIR__) . '/Views/eggs/index.php';
        } catch (DatabaseException $e) {
            error_log($e->getMessage());
            require dirname(__DIR__) . '/Views/errors/500.php';
        }
    }

    public function show(string $type): void {
        try {
            $egg = $this->eggService->getEggByType(urldecode($type));
            require dirname(__DIR__) . '/Views/eggs/show.php';
        } catch (NotFoundException $e) {
            require dirname(__DIR__) . '/Views/errors/404.php';
        } catch (DatabaseException $e) {
            error_log($e->getMessage());
            require dirname(__DIR__) . '/Views/errors/500.php';
        }
    }

    public function api() {
        header('Content-Type: application/json');
        $eggs = $this->eggService->getAllEggs();
        echo json_encode($eggs);
    }
}
