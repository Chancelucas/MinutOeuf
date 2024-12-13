<?php

namespace App\Models;

use MongoDB\Client;
use MongoDB\Collection;

class EggModel {
    private Collection $collection;

    public function __construct() {
        // Connexion à MongoDB Atlas
        $client = new Client("mongodb+srv://wadyx38:k7NYq73UdTaU9vwH@cluster0.4xafp.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");
        $this->collection = $client->minutoeuf->eggs;
    }

    public function getAllEggs(): array {
        try {
            $cursor = $this->collection->find();
            $eggs = [];
            
            foreach ($cursor as $document) {
                $eggs[] = [
                    'name' => $document->name,
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

    public function getEggByName(string $name) {
        try {
            return $this->collection->findOne(['name' => $name]);
        } catch (\Exception $e) {
            error_log("MongoDB Error: " . $e->getMessage());
            return null;
        }
    }

    public function createEgg(array $eggData): bool {
        try {
            $result = $this->collection->insertOne($eggData);
            return $result->getInsertedCount() > 0;
        } catch (\Exception $e) {
            error_log("MongoDB Error: " . $e->getMessage());
            return false;
        }
    }

    public function updateEgg(string $name, array $eggData): bool {
        try {
            $result = $this->collection->updateOne(
                ['name' => $name],
                ['$set' => $eggData]
            );
            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            error_log("MongoDB Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteEgg(string $name): bool {
        try {
            $result = $this->collection->deleteOne(['name' => $name]);
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
