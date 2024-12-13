<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function loginPage() {
        if (isset($_SESSION['user'])) {
            header('Location: /admin');
            exit;
        }
        return $this->render('auth/login', [
            'title' => 'MinutOeuf - Connexion'
        ]);
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            return $this->json([
                'success' => false,
                'error' => 'Email et mot de passe requis'
            ]);
        }

        if ($this->userModel->verifyPassword($email, $password)) {
            $_SESSION['user'] = $email;
            $_SESSION['role'] = 'admin';
            
            return $this->json([
                'success' => true,
                'redirect' => '/admin'
            ]);
        }

        return $this->json([
            'success' => false,
            'error' => 'Email ou mot de passe incorrect'
        ]);
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}
