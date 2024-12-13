<?php

namespace App\Middleware;

class AdminMiddleware {
    public function handle() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
    }
}
