<?php

namespace App\Core;

class Router {
    private array $routes = [];

    public function get(string $path, $handler): void {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): void {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, $handler): void {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch(): void {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Retirer le préfixe /public si présent
        $uri = preg_replace('/^\/public/', '', $uri);
        
        error_log("URI après nettoyage : " . $uri);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                $params = $this->matchPath($route['path'], $uri);
                if ($params !== false) {
                    error_log("Route trouvée : " . $route['path']);
                    error_log("Paramètres : " . json_encode($params));
                    
                    if (is_array($route['handler'])) {
                        [$controller, $action] = $route['handler'];
                        $controller = new $controller();
                        if (!empty($params)) {
                            call_user_func_array([$controller, $action], $params);
                        } else {
                            $controller->$action();
                        }
                    } elseif (is_callable($route['handler'])) {
                        call_user_func($route['handler'], ...$params);
                    }
                    return;
                }
            }
        }
        
        error_log("Aucune route trouvée pour : " . $uri);
        // Si aucune route ne correspond, afficher la page 404
        http_response_code(404);
        require __DIR__ . '/../Views/404.php';
    }

    private function matchPath(string $routePath, string $uri): false|array {
        // Décoder l'URI pour gérer les caractères spéciaux
        $uri = urldecode($uri);
        
        // S'assurer que l'URI et le routePath commencent par /
        $uri = '/' . ltrim($uri, '/');
        $routePath = '/' . ltrim($routePath, '/');
        
        // Convertir les paramètres de route en pattern regex
        $pattern = preg_replace('/:[^\/]+/', '([^/]+)', $routePath);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';
        
        error_log("Pattern de route : " . $pattern);
        error_log("URI à matcher : " . $uri);
        
        if (preg_match($pattern, $uri, $matches)) {
            error_log("Match trouvé : " . json_encode($matches));
            array_shift($matches); // Enlever le premier élément (match complet)
            return $matches;
        }
        
        error_log("Pas de match trouvé");
        return false;
    }
}
