<?php

namespace App\Core;

class BaseController {
    protected function render($view, $data = []) {
        // Extraire les données pour qu'elles soient disponibles dans la vue
        extract($data);
        
        // Capturer le contenu de la vue
        ob_start();
        include __DIR__ . "/../Views/{$view}.php";
        $content = ob_get_clean();
        
        // Rendre le layout avec les mêmes données
        extract($data);
        include __DIR__ . "/../Views/layouts/default.php";
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function error($message, $code = 404) {
        http_response_code($code);
        return $this->json([
            'success' => false,
            'error' => $message
        ]);
    }
}
