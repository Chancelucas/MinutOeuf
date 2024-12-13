<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\UserModel;

try {
    $userModel = new UserModel();
    
    // Supprimer tous les utilisateurs existants
    $userModel->getCollection()->deleteMany([]);
    
    // Créer l'administrateur
    $email = 'admin@minutoeuf.fr';
    $password = 'admin123'; // À changer en production !
    
    if ($userModel->createUser($email, $password)) {
        echo "Administrateur créé avec succès !\n";
        echo "Email: $email\n";
        echo "Mot de passe: $password\n";
    } else {
        echo "Erreur lors de la création de l'administrateur.\n";
    }
} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
