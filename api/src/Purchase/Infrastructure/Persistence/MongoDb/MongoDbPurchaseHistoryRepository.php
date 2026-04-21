<?php

declare(strict_types=1);

namespace App\Purchase\Infrastructure\Persistence\Mongodb;

use App\Purchase\Domain\Entities\PurchaseHistory;
use MongoDB\Client;
use MongoDB\Collection;

class MongoDbPurchaseHistoryRepository
{
    public const string MONGO_URL = 'mongodb://localhost:27017';
    public const string MONGO_DATABASE = 'vending_machine';
    public const string MONGO_TABLE = 'purchase_history';

    private Collection $collection;

    public function __construct() {
        $client = new Client(self::MONGO_URL);

        $database = $client->selectDatabase(self::MONGO_DATABASE);

        $collection = $database->selectCollection(self::MONGO_TABLE);

        $this->collection = $collection;
    }

    public function persist(PurchaseHistory $record): void
    {
        $this->collection->insertOne(
            $this->toDocument($record)
        );
    }

    public function findAllByIdentifier(string $identifier): PurchaseHistoryCollection
    {
        $cursor = $this->collection->find([
            'identifier' => $identifier
        ]);
    }
}