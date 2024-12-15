<?php

namespace App\Controllers;

use App\Core\Database;
use MongoDB\BSON\ObjectId;

class EggPageController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    private function slugify($text) {
        // Remplace les caractères accentués par leurs équivalents non accentués
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
        // Convertit en minuscules
        $text = strtolower($text);
        // Remplace tous les caractères non alphanumériques par un tiret
        $text = preg_replace('/[^a-z0-9]/', '-', $text);
        // Supprime les tirets multiples
        $text = preg_replace('/-+/', '-', $text);
        // Supprime les tirets au début et à la fin
        return trim($text, '-');
    }

    public function index() {
        // Récupérer tous les œufs et les convertir en tableau PHP
        $cursor = $this->db->getCollection('eggs')->find();
        $eggs = [];
        foreach ($cursor as $document) {
            $eggs[] = (array)$document;
        }
        
        // Charger la vue avec les œufs
        require __DIR__ . '/../Views/eggs/index.php';
    }

    public function show($type) {
        // Rechercher tous les œufs
        $cursor = $this->db->getCollection('eggs')->find();
        $eggs = [];
        foreach ($cursor as $document) {
            $eggs[] = (array)$document;
        }
        
        // Trouver l'œuf correspondant en comparant les slugs
        $egg = null;
        foreach ($eggs as $currentEgg) {
            if ($this->slugify($currentEgg['type']) === $type) {
                $egg = $currentEgg;
                break;
            }
        }

        if (!$egg) {
            header("HTTP/1.0 404 Not Found");
            require __DIR__ . '/../Views/404.php';
            return;
        }

        require __DIR__ . '/../Views/eggs/show.php';
    }
}
