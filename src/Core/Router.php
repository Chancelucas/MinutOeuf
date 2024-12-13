<?php

namespace App\Core;

use App\Controllers\EggPageController;
use App\Controllers\HomeController;

class Router {
    private static ?Router $instance = null;

    private function __construct() {}

    public static function getInstance(): Router {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function dispatch() {
        session_start();
        $uri = $_SERVER['REQUEST_URI'];

        // Normalisation de l'URI
        if (!empty($uri) && $uri != '/' && $uri[-1] === "/") {
            $uri = substr($uri, 0, -1);
            http_response_code(301);
            header('Location: ' . $uri);
            exit;
        }

        // Redirection vers les URLs en minuscules
        $lowerUri = strtolower($uri);
        if ($uri !== $lowerUri) {
            http_response_code(301);
            header('Location: ' . $lowerUri);
            exit;
        }

        // Gestion des fichiers statiques
        if (strpos($uri, '/assets/') === 0) {
            $this->handleStaticFile($uri);
            return;
        }

        // Extraction des paramètres de l'URL
        $params = explode('/', trim($uri, '/'));
        $this->handleRequest($params);
    }

    private function handleRequest(array $params) {
        try {
            if (empty($params[0])) {
                // Page d'accueil
                $controller = new HomeController();
                $controller->index();
                return;
            }

            switch ($params[0]) {
                case 'api':
                    $this->handleApiRoute(array_slice($params, 1));
                    break;

                case 'admin':
                    $this->handleAdminRoute(array_slice($params, 1));
                    break;

                case 'eggs':
                    if (isset($params[1])) {
                        $eggName = urldecode($params[1]);
                        $controller = new EggPageController();
                        $controller->show($eggName);
                    } else {
                        $this->handle404();
                    }
                    break;

                default:
                    $this->handle404();
                    break;
            }
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $this->handle500($th);
        }
    }

    private function handleApiRoute(array $params) {
        if (empty($params)) {
            $this->handle404();
            return;
        }

        $controllerName = ucfirst(array_shift($params)) . 'Controller';
        $controllerClass = 'App\\Controllers\\' . $controllerName;
        
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            $action = isset($params[0]) ? array_shift($params) : 'index';
            
            if (method_exists($controller, $action)) {
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        $this->handle404();
    }

    private function handleAdminRoute(array $params) {
        $controllerName = !empty($params[0]) ? ucfirst(array_shift($params)) : 'Dashboard';
        $controllerClass = 'App\\Controllers\\' . $controllerName . 'Controller';
        
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            $action = isset($params[0]) ? array_shift($params) : 'index';
            
            if (method_exists($controller, $action)) {
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        $this->handle404();
    }

    private function handle404() {
        http_response_code(404);
        require_once ROOT . '/src/Views/errors/404.php';
    }

    private function handle500(\Throwable $error) {
        http_response_code(500);
        require_once ROOT . '/src/Views/errors/500.php';
    }

    private function handleStaticFile(string $uri) {
        $filePath = ROOT . '/public' . $uri;
        if (file_exists($filePath)) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $mimeTypes = [
                'css' => 'text/css',
                'js' => 'application/javascript',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
            ];
            
            if (isset($mimeTypes[$extension])) {
                header('Content-Type: ' . $mimeTypes[$extension]);
            }
            
            readfile($filePath);
            exit;
        }
        
        $this->handle404();
    }
}
