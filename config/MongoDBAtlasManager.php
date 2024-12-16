<?php

namespace config;

use Exception;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Driver\ServerApi;

class MongoDBAtlasManager
{
  private Client $client;
  private Collection $collection;
  private $database;

  public function __construct()
  {
    $databaseName = "minutoeuf";
    $collectionName = "eggs";
    $uri = 'mongodb+srv://wadyx38:k7NYq73UdTaU9vwH@cluster0.4xafp.mongodb.net/?retryWrites=true&w=majority&tls=true&tlsVersion=1.2&appName=Cluster0&tlsAllowInvalidCertificates=true';

    $apiVersion = new ServerApi(ServerApi::V1);
    $this->client = new Client($uri, [], ['serverApi' => $apiVersion]);

    try {
      $this->client->selectDatabase($databaseName)->command(['ping' => 1]);
      $this->collection = $this->client->selectCollection($databaseName, $collectionName);
      $this->database = $this->client->selectDatabase($databaseName);
    } catch (Exception $e) {
      printf($e->getMessage());
    }
  }

  public function getClient()
  {
    return $this->client;
  }

  public function getDatabase()
  {
    return $this->database;
  }

  public function createDocument(array $data)
  {
    // Ajoutez l'ID relationnel à vos données
    if (isset($data['relational_id'])) {
      $data['relational_id'] = (string) $data['relational_id'];
    }

    $data['click_count'] = 0; // Initialisez le compteur

    try {
      $result = $this->collection->insertOne($data);
      return $result->getInsertedId(); // Retourne l'ObjectId de MongoDB
    } catch (Exception $e) {
      echo "Erreur lors de l'insertion";
    }
  }

  public function readDocuments(array $filter = [], array $options = [])
  {
    try {
      return iterator_to_array($this->collection->find($filter, $options));
    } catch (Exception $e) {
      echo "Erreur lors de la lecture";
    }
  }

  public function updateDocument(array $filter, array $newData)
  {
    try {
      // Effectuer l'update en utilisant les paramètres donnés
      $updateResult = $this->collection->updateOne($filter, $newData);

      // Retourne le nombre de documents modifiés
      return $updateResult->getModifiedCount();
    } catch (Exception $e) {
     echo "Erreur lors de la mise à jour";
    }
  }
}
