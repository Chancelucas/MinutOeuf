<?php

namespace App\Core;

use App\Core\BaseEggController;

class EggPageControllerFactory {
    public static function create(string $eggType): BaseEggController {
        $controllerClass = 'App\\Controllers\\' . ucfirst($eggType) . 'PageController';
        
        if (!class_exists($controllerClass)) {
            // Création dynamique de la classe du contrôleur
            $code = "namespace App\\Controllers;\n\n";
            $code .= "use App\\Core\\BaseEggController;\n\n";
            $code .= "class " . ucfirst($eggType) . "PageController extends BaseEggController {}\n";
            
            eval($code);
        }
        
        return new $controllerClass();
    }
}
