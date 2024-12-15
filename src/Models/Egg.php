<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Exceptions\DatabaseException;
use MongoDB\BSON\ObjectId;

class Egg {
    protected $db;
    protected $collection = 'eggs';

    public function __construct() {
        try {
            error_log("[Egg] Initializing Egg model");
            $this->db = Database::getInstance();
            error_log("[Egg] Database instance obtained successfully");
        } catch (\Exception $e) {
            error_log("[Egg] Error in constructor: " . $e->getMessage());
            error_log("[Egg] Stack trace: " . $e->getTraceAsString());
            throw new DatabaseException("Erreur de connexion à la base de données", 0, $e);
        }
    }

    public function getCollection() {
        return $this->collection;
    }

    public function getAll(): array {
        try {
            error_log("[Egg] Getting all eggs");
            if (!$this->db || !$this->db->database) {
                error_log("[Egg] Database connection is invalid");
                throw new DatabaseException("La connexion à la base de données n'est pas valide");
            }

            error_log("[Egg] Selecting collection: " . $this->collection);
            $cursor = $this->db->database->selectCollection($this->collection)->find();
            $eggs = iterator_to_array($cursor);
            
            error_log("[Egg] Found " . count($eggs) . " eggs");
            
            // Convertir les documents MongoDB en tableaux PHP
            $result = array_map(function($egg) {
                $eggArray = (array)$egg;
                if (isset($eggArray['_id']) && $eggArray['_id'] instanceof ObjectId) {
                    $eggArray['_id'] = (string)$eggArray['_id'];
                }
                return $eggArray;
            }, $eggs);

            error_log("[Egg] Successfully retrieved eggs");
            return $result;
        } catch (\Exception $e) {
            error_log("[Egg] Error in getAll: " . $e->getMessage());
            error_log("[Egg] Stack trace: " . $e->getTraceAsString());
            throw new DatabaseException("Erreur lors de la récupération des œufs", 0, $e);
        }
    }

    public function findByType(string $type): ?array {
        try {
            error_log("[Egg] Finding egg by type: " . $type);
            if (!$this->db || !$this->db->database) {
                error_log("[Egg] Database connection is invalid");
                throw new DatabaseException("La connexion à la base de données n'est pas valide");
            }

            error_log("[Egg] Selecting collection: " . $this->collection);
            $result = $this->db->database->selectCollection($this->collection)->findOne(['type' => $type]);
            
            if ($result === null) {
                error_log("[Egg] Egg not found");
                return null;
            }

            error_log("[Egg] Egg found");
            // Convertir le document MongoDB en tableau PHP
            $eggArray = (array)$result;
            if (isset($eggArray['_id']) && $eggArray['_id'] instanceof ObjectId) {
                $eggArray['_id'] = (string)$eggArray['_id'];
            }

            return $eggArray;
        } catch (\Exception $e) {
            error_log("[Egg] Error in findByType: " . $e->getMessage());
            error_log("[Egg] Stack trace: " . $e->getTraceAsString());
            throw new DatabaseException("Erreur lors de la recherche de l'œuf", 0, $e);
        }
    }

    public function findById($id): ?array {
        try {
            error_log("[Egg] Finding egg by id: " . $id);
            $result = $this->db->database->selectCollection($this->collection)->findOne([
                '_id' => new ObjectId($id)
            ]);
            if ($result === null) {
                error_log("[Egg] Egg not found");
                return null;
            }
            error_log("[Egg] Egg found");
            // Convertir le document MongoDB en tableau PHP
            $eggArray = (array)$result;
            if (isset($eggArray['_id']) && $eggArray['_id'] instanceof ObjectId) {
                $eggArray['_id'] = (string)$eggArray['_id'];
            }
            return $eggArray;
        } catch (\Exception $e) {
            error_log("[Egg] Error in findById: " . $e->getMessage());
            error_log("[Egg] Stack trace: " . $e->getTraceAsString());
            throw new DatabaseException("Erreur lors de la recherche de l'œuf", 0, $e);
        }
    }

    public function create($data): ?string {
        try {
            error_log("[Egg] Creating new egg");
            $result = $this->db->database->selectCollection($this->collection)->insertOne($data);
            error_log("[Egg] Egg created: " . json_encode($data));
            return (string)$result->getInsertedId();
        } catch (\Exception $e) {
            error_log("[Egg] Error in create: " . $e->getMessage());
            error_log("[Egg] Stack trace: " . $e->getTraceAsString());
            throw new DatabaseException("Erreur lors de la création de l'œuf", 0, $e);
        }
    }

    public function update($id, $data): bool {
        try {
            error_log("[Egg] Updating egg: " . $id);
            $result = $this->db->database->selectCollection($this->collection)->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $data]
            );
            error_log("[Egg] Egg updated: " . json_encode($data));
            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            error_log("[Egg] Error in update: " . $e->getMessage());
            error_log("[Egg] Stack trace: " . $e->getTraceAsString());
            throw new DatabaseException("Erreur lors de la mise à jour de l'œuf", 0, $e);
        }
    }

    public function delete($id): bool {
        try {
            error_log("[Egg] Deleting egg: " . $id);
            $result = $this->db->database->selectCollection($this->collection)->deleteOne(
                ['_id' => new ObjectId($id)]
            );
            error_log("[Egg] Egg deleted: " . $id);
            return $result->getDeletedCount() > 0;
        } catch (\Exception $e) {
            error_log("[Egg] Error in delete: " . $e->getMessage());
            error_log("[Egg] Stack trace: " . $e->getTraceAsString());
            throw new DatabaseException("Erreur lors de la suppression de l'œuf", 0, $e);
        }
    }

    public function resetData(): bool {
        try {
            error_log("[Egg] Resetting data");
            if (!$this->db || !$this->db->database) {
                error_log("[Egg] Database connection is invalid");
                throw new DatabaseException("La connexion à la base de données n'est pas valide");
            }

            error_log("[Egg] Deleting all existing data");
            // Supprime toutes les données existantes
            $this->db->database->selectCollection($this->collection)->deleteMany([]);
            
            // Données par défaut
            $defaultData = [
                [
                    'type' => 'À la coque',
                    'cookingTime' => 3,
                    'description' => 'Œuf cuit dans sa coquille avec un blanc coagulé et un jaune liquide.',
                    'instructions' => [
                        'Plonger l\'œuf dans l\'eau bouillante',
                        'Cuire pendant 3 minutes',
                        'Retirer et servir immédiatement'
                    ]
                ],
                [
                    'type' => 'Mollet',
                    'cookingTime' => 6,
                    'description' => 'Œuf mollet avec un blanc ferme et un jaune encore un peu coulant.',
                    'instructions' => [
                        'Plonger l\'œuf dans l\'eau bouillante',
                        'Cuire pendant 6 minutes',
                        'Refroidir légèrement et écaler'
                    ]
                ],
                [
                    'type' => 'Dur',
                    'cookingTime' => 9,
                    'description' => 'Œuf dur avec blanc et jaune complètement cuits.',
                    'instructions' => [
                        'Plonger l\'œuf dans l\'eau bouillante',
                        'Cuire pendant 9 minutes',
                        'Refroidir dans l\'eau froide et écaler'
                    ]
                ],
                [
                    'type' => 'Poché',
                    'cookingTime' => 3,
                    'description' => 'Œuf poché avec blanc enveloppant et jaune coulant.',
                    'instructions' => [
                        'Créer un tourbillon dans l\'eau frémissante',
                        'Casser l\'œuf au centre',
                        'Cuire pendant 3 minutes'
                    ]
                ],
                [
                    'type' => 'Au plat',
                    'cookingTime' => 3,
                    'description' => 'Œuf au plat avec jaune coulant et blanc cuit.',
                    'instructions' => [
                        'Chauffer la poêle à feu moyen',
                        'Casser l\'œuf délicatement',
                        'Cuire pendant 3 minutes'
                    ]
                ]
            ];
            
            error_log("[Egg] Inserting default data");
            $result = $this->db->database->selectCollection($this->collection)->insertMany($defaultData);
            error_log("[Egg] Data inserted successfully: " . json_encode($result->getInsertedIds()));
            
            return true;
        } catch (\Exception $e) {
            error_log("[Egg] Error in resetData: " . $e->getMessage());
            error_log("[Egg] Stack trace: " . $e->getTraceAsString());
            throw new DatabaseException("Erreur lors de la réinitialisation des données", 0, $e);
        }
    }
}
