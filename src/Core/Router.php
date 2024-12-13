<?php

namespace App\Core;

class Router {
    private array $routes = [];
    private static ?Router $instance = null;

    private function __construct() {}

    public static function getInstance(): Router {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addRoute(string $method, string $path, array $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function get(string $path, array $handler) {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array $handler) {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, array $handler) {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, array $handler) {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function convertPathToRegex(string $path): string {
        return '#^' . preg_replace('/\{([a-zA-Z]+)\}/', '([^/]+)', $path) . '$#';
    }

    private function handleStaticFile(string $uri) {
        $filePath = __DIR__ . '/../../public' . $uri;
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
                'mp3' => 'audio/mpeg'
            ];
            
            if (isset($mimeTypes[$extension])) {
                header('Content-Type: ' . $mimeTypes[$extension]);
            }
            
            readfile($filePath);
            exit;
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    }

    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Support for PUT and DELETE methods via POST
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        // Handle static files
        if (strpos($uri, '/assets/') === 0) {
            $this->handleStaticFile($uri);
            return;
        }

        foreach ($this->routes as $route) {
            $pattern = $this->convertPathToRegex($route['path']);
            
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                
                $controller = new $route['handler'][0]();
                $action = $route['handler'][1];
                
                return call_user_func_array([$controller, $action], $matches);
            }
        }

        // 404 Not Found
        header("HTTP/1.0 404 Not Found");
        echo '404 Not Found';
    }
}
