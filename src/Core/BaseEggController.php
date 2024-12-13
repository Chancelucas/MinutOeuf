<?php

namespace App\Core;

use App\Models\EggModel;

abstract class BaseEggController extends BaseController {
    protected EggModel $eggModel;

    public function __construct() {
        $this->eggModel = new EggModel();
    }

    /**
     * Obtient le type d'œuf à partir du nom du contrôleur
     */
    protected function getEggType(): string {
        $className = basename(str_replace('\\', '/', get_class($this)));
        return strtolower(str_replace('PageController', '', $className));
    }

    /**
     * Page par défaut pour un type d'œuf
     */
    public function index() {
        $eggType = $this->getEggType();
        $egg = $this->eggModel->getEggByName($eggType);
        
        if (!$egg) {
            header('Location: /');
            exit;
        }

        return $this->render('eggs/show', [
            'title' => 'MinutOeuf - ' . $egg['name'],
            'egg' => $egg,
            'currentEgg' => $egg['name']
        ]);
    }
}
