<?php

declare(strict_types=1);

namespace App\Purchase\Infrastructure\Persistence\MongoDb;

use App\Purchase\Domain\Entities\PurchaseHistory;
use App\Purchase\Domain\Entities\PurchaseHistoryCollection;
use App\Purchase\Domain\Factories\PurchaseHistoryFactory;
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

        $items = [];

        foreach ($cursor as $document) {
            $items[] = $this->toDomain($document);
        }

        return new PurchaseHistoryCollection($items);
    }

    private function toDomain(array $document): PurchaseHistory
    {
        return PurchaseHistoryFactory::fromArray([
            'identifier' => $document['identifier'],
            'action' => $document['action'],
            'amount' => $document['amount'],
            'currency' => $document['currency'],
            'productName' => $document['product_name'],
            'createdAt' => $document['created_at'],
            'updatedAt' => $document['updated_at'],
        ]);
    }

    private function toDocument(PurchaseHistory $record): array
    {
        return [
            'identifier' => $record->identifier(),
            'action' => $record->action(),
            'amount' => $record->amount(),
            'currency' => $record->currency(),
            'product_name' => $record->productName(),
            'created_at' => $record->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $record->updatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}