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
            $this->db = Database::getInstance();
            error_log("Database connection established successfully in Egg model");
        } catch (\Exception $e) {
            error_log("Error connecting to database in Egg model: " . $e->getMessage());
            throw new DatabaseException("Erreur de connexion à la base de données", 0, $e);
        }
    }

    public function getCollection() {
        return $this->collection;
    }

    public function getAll(): array {
        try {
            if (!$this->db || !$this->db->database) {
                throw new DatabaseException("La connexion à la base de données n'est pas valide");
            }

            $cursor = $this->db->database->selectCollection($this->collection)->find();
            $eggs = iterator_to_array($cursor);
            
            // Convertir les documents MongoDB en tableaux PHP
            $result = array_map(function($egg) {
                $eggArray = (array)$egg;
                // Convertir l'ID MongoDB en string
                if (isset($eggArray['_id']) && $eggArray['_id'] instanceof ObjectId) {
                    $eggArray['_id'] = (string)$eggArray['_id'];
                }
                return $eggArray;
            }, $eggs);

            error_log("Nombre d'œufs trouvés : " . count($result));
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans getAll(): " . $e->getMessage());
            throw new DatabaseException("Erreur lors de la récupération des œufs", 0, $e);
        }
    }

    public function findByType(string $type): ?array {
        try {
            if (!$this->db || !$this->db->database) {
                throw new DatabaseException("La connexion à la base de données n'est pas valide");
            }

            $result = $this->db->database->selectCollection($this->collection)->findOne(['type' => $type]);
            
            if ($result === null) {
                return null;
            }

            // Convertir le document MongoDB en tableau PHP
            $eggArray = (array)$result;
            if (isset($eggArray['_id']) && $eggArray['_id'] instanceof ObjectId) {
                $eggArray['_id'] = (string)$eggArray['_id'];
            }

            return $eggArray;
        } catch (\Exception $e) {
            error_log("Erreur dans findByType(): " . $e->getMessage());
            throw new DatabaseException("Erreur lors de la recherche de l'œuf", 0, $e);
        }
    }

    public function findById($id): ?array {
        try {
            $result = $this->db->database->selectCollection($this->collection)->findOne([
                '_id' => new ObjectId($id)
            ]);
            if ($result === null) {
                return null;
            }
            // Convertir le document MongoDB en tableau PHP
            $eggArray = (array)$result;
            if (isset($eggArray['_id']) && $eggArray['_id'] instanceof ObjectId) {
                $eggArray['_id'] = (string)$eggArray['_id'];
            }
            return $eggArray;
        } catch (\Exception $e) {
            error_log("Erreur dans findById(): " . $e->getMessage());
            throw new DatabaseException("Erreur lors de la recherche de l'œuf", 0, $e);
        }
    }

    public function create($data): ?string {
        try {
            $result = $this->db->database->selectCollection($this->collection)->insertOne($data);
            error_log("Œuf créé : " . json_encode($data));
            return (string)$result->getInsertedId();
        } catch (\Exception $e) {
            error_log("Erreur dans create(): " . $e->getMessage());
            throw new DatabaseException("Erreur lors de la création de l'œuf", 0, $e);
        }
    }

    public function update($id, $data): bool {
        try {
            $result = $this->db->database->selectCollection($this->collection)->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $data]
            );
            error_log("Œuf mis à jour : " . json_encode($data));
            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            error_log("Erreur dans update(): " . $e->getMessage());
            throw new DatabaseException("Erreur lors de la mise à jour de l'œuf", 0, $e);
        }
    }

    public function delete($id): bool {
        try {
            $result = $this->db->database->selectCollection($this->collection)->deleteOne(
                ['_id' => new ObjectId($id)]
            );
            error_log("Œuf supprimé : " . $id);
            return $result->getDeletedCount() > 0;
        } catch (\Exception $e) {
            error_log("Erreur dans delete(): " . $e->getMessage());
            throw new DatabaseException("Erreur lors de la suppression de l'œuf", 0, $e);
        }
    }

    public function resetData(): bool {
        try {
            if (!$this->db || !$this->db->database) {
                throw new DatabaseException("La connexion à la base de données n'est pas valide");
            }

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
            
            $result = $this->db->database->selectCollection($this->collection)->insertMany($defaultData);
            error_log("Données insérées avec succès : " . json_encode($result->getInsertedIds()));
            
            return true;
        } catch (\Exception $e) {
            error_log("Erreur dans resetData(): " . $e->getMessage());
            throw new DatabaseException("Erreur lors de la réinitialisation des données", 0, $e);
        }
    }
}
