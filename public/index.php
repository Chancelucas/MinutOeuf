<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\ErrorHandler;
use App\Controllers\EggController;
use App\Controllers\AdminController;

// Configuration des gestionnaires d'erreurs
set_error_handler([ErrorHandler::class, 'handleError']);
set_exception_handler([ErrorHandler::class, 'handleException']);

$router = new Router();

// Routes principales
$router->get('/', [EggController::class, 'index']);
$router->get('/egg/:type', [EggController::class, 'show']);

// Routes admin
$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/create', [AdminController::class, 'create']);
$router->post('/admin/create', [AdminController::class, 'create']);
$router->get('/admin/edit/:id', [AdminController::class, 'edit']);
$router->post('/admin/edit/:id', [AdminController::class, 'edit']);
$router->post('/admin/delete/:id', [AdminController::class, 'delete']);

// Route de réinitialisation déplacée vers le contrôleur
$router->get('/reset', [AdminController::class, 'reset']);

$router->dispatch();
