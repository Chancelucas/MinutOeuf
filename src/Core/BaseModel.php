<?php

namespace App\Core;

use MongoDB\Collection;

abstract class BaseModel {
    protected Collection $collection;

    public function __construct() {
        $database = Database::getInstance()->getDatabase();
        $this->collection = $database->{$this->getCollectionName()};
    }

    abstract protected function getCollectionName(): string;

    public function getCollection(): Collection {
        return $this->collection;
    }

    public function findAll(): array {
        return $this->collection->find()->toArray();
    }

    public function findOne(array $filter): ?array {
        $result = $this->collection->findOne($filter);
        return $result ? (array)$result : null;
    }

    public function create(array $data): bool {
        $result = $this->collection->insertOne($data);
        return $result->getInsertedCount() > 0;
    }

    public function update(array $filter, array $data): bool {
        $result = $this->collection->updateOne($filter, ['$set' => $data]);
        return $result->getModifiedCount() > 0;
    }

    public function delete(array $filter): bool {
        $result = $this->collection->deleteOne($filter);
        return $result->getDeletedCount() > 0;
    }
}
