<?php

namespace App\Middleware;

class AdminMiddleware {
    public function handle() {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
    }
}
