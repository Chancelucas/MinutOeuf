<?php

namespace App\Core;

class Autoloader
{
    static function register()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    static function autoload($class)
    {
        // Ne gérer que les classes de notre application
        if (strpos($class, 'App\\') !== 0) {
            return false;
        }

        // Retirer le namespace de base (App)
        $class = str_replace('App\\', '', $class);
        
        // Convertir les namespace en séparateurs de dossiers
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        
        // Chemin vers le dossier src
        $file = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $class . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            return true;
        }

        return false;
    }
}
