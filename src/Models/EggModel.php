<?php

namespace App\Models;

use App\Core\BaseModel;
use MongoDB\Collection;

class EggModel extends BaseModel {
    protected function getCollectionName(): string {
        return 'eggs';
    }

    /**
     * Normalise un nom d'œuf pour la recherche
     */
    private function normalizeName(string $name): string {
        // Convertit en minuscules et supprime les caractères spéciaux
        $normalized = mb_strtolower($name, 'UTF-8');
        $normalized = iconv('UTF-8', 'ASCII//TRANSLIT', $normalized);
        return preg_replace('/[^a-z0-9]/', '', $normalized);
    }

    public function getAllEggs(): array {
        try {
            $cursor = $this->collection->find();
            $eggs = [];
            
            foreach ($cursor as $document) {
                $eggs[] = [
                    'name' => $document->name,
                    'normalizedName' => $this->normalizeName($document->name),
                    'minutes' => $document->minutes,
                    'instructions' => $document->instructions ?? '',
                    'image' => $document->image ?? null
                ];
            }
            
            return $eggs;
        } catch (\Exception $e) {
            error_log("MongoDB Error: " . $e->getMessage());
            return [];
        }
    }

    public function getEggByName(string $name): ?array {
        try {
            $normalizedSearchName = $this->normalizeName($name);
            
            // Récupérer tous les œufs et chercher celui qui correspond
            $eggs = $this->getAllEggs();
            foreach ($eggs as $egg) {
                if ($egg['normalizedName'] === $normalizedSearchName) {
                    return $egg;
                }
            }
            
            return null;
        } catch (\Exception $e) {
            error_log("MongoDB Error: " . $e->getMessage());
            return null;
        }
    }

    public function addEgg(array $eggData): bool {
        try {
            $result = $this->collection->insertOne([
                'name' => $eggData['name'],
                'normalizedName' => $this->normalizeName($eggData['name']),
                'minutes' => $eggData['minutes'],
                'instructions' => $eggData['instructions'] ?? '',
                'image' => $eggData['image'] ?? null
            ]);
            
            return $result->getInsertedCount() > 0;
        } catch (\Exception $e) {
            error_log("MongoDB Error: " . $e->getMessage());
            return false;
        }
    }

    public function updateEgg(string $name, array $eggData): bool {
        try {
            $result = $this->collection->updateOne(
                ['normalizedName' => $this->normalizeName($name)],
                ['$set' => [
                    'name' => $eggData['name'],
                    'normalizedName' => $this->normalizeName($eggData['name']),
                    'minutes' => $eggData['minutes'],
                    'instructions' => $eggData['instructions'] ?? '',
                    'image' => $eggData['image'] ?? null
                ]]
            );
            
            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            error_log("MongoDB Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteEgg(string $name): bool {
        try {
            $result = $this->collection->deleteOne([
                'normalizedName' => $this->normalizeName($name)
            ]);
            
            return $result->getDeletedCount() > 0;
        } catch (\Exception $e) {
            error_log("MongoDB Error: " . $e->getMessage());
            return false;
        }
    }

    public function countEggs(): int {
        try {
            return $this->collection->countDocuments();
        } catch (\Exception $e) {
            error_log("MongoDB Error: " . $e->getMessage());
            return 0;
        }
    }
}
