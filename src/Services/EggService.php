<?php

namespace App\Services;

use App\Models\Egg;
use App\Core\Exceptions\DatabaseException;
use App\Core\Exceptions\NotFoundException;

class EggService {
    private Egg $eggModel;

    public function __construct() {
        $this->eggModel = new Egg();
    }

    public function getAllEggs(): array {
        try {
            return $this->eggModel->getAll();
        } catch (\Exception $e) {
            throw new DatabaseException("Impossible de récupérer les œufs", 0, $e);
        }
    }

    public function getEggByType(string $type): array {
        try {
            $egg = $this->eggModel->findByType($type);
            if (!$egg) {
                throw new NotFoundException("Type d'œuf non trouvé : " . $type);
            }
            return $egg;
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new DatabaseException("Impossible de récupérer l'œuf", 0, $e);
        }
    }

    public function resetEggData(): bool {
        try {
            return $this->eggModel->resetData();
        } catch (\Exception $e) {
            throw new DatabaseException("Impossible de réinitialiser les données", 0, $e);
        }
    }
}
