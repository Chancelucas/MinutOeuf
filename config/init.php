<?php

use App\Core\Router;
use App\Controllers\EggController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\DashboardController;

$router = Router::getInstance();

// Pages publiques
$router->get('/', [EggController::class, 'index']);
$router->get('/api/eggs', [EggController::class, 'getAllEggs']);
$router->get('/api/eggs/{name}', [EggController::class, 'getEggByName']);

// Authentification
$router->get('/login', [AuthController::class, 'loginPage']);
$router->post('/auth/login', [AuthController::class, 'login']);
$router->get('/auth/logout', [AuthController::class, 'logout']);

// Administration
$router->get('/admin', [DashboardController::class, 'index']);
$router->post('/admin/eggs', [AdminController::class, 'createEgg']);
$router->post('/admin/eggs/{name}', [AdminController::class, 'updateEgg']); 
$router->post('/admin/eggs/{name}/delete', [AdminController::class, 'deleteEgg']); 
