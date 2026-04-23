<?php

declare(strict_types=1);

namespace App\Purchase\Infrastructure\Persistence\MongoDb;

use App\Purchase\Domain\Entities\Change;
use App\Purchase\Domain\Entities\ChangeCollection;
use App\Purchase\Domain\Factories\ChangeFactory;
use App\Purchase\Domain\Repositories\ChangeRepository;
use MongoDB\Model\BSONDocument;
use MongoDB\Client;
use MongoDB\Collection;

class MongoDbChangeRepository implements ChangeRepository
{
    //This valiues must be provided by the ServiceProvider by the construct with env variables
    public const string MONGO_URL = 'mongodb://admin:notYourProblem@database:27017';
    public const string MONGO_DATABASE = 'vending_machine';
    public const string MONGO_TABLE = 'change';

    private Collection $collection;

    public function __construct() {
        $client = new Client(self::MONGO_URL);

        $database = $client->selectDatabase(self::MONGO_DATABASE);

        $collection = $database->selectCollection(self::MONGO_TABLE);

        $this->collection = $collection;
    }

    public function persist(Change $record): void
    {
        $this->collection->updateOne(
            ['amount' => $record->amount()],
            ['$set' => $this->toDocument($record)],
            ['upsert' => true]
        );
    }

    public function findByAmount(float $amount): Change
    {
        $result = $this->collection->findOne([
            'amount' => $amount
        ]);

        return $this->toDomain($result);
    }

    public function findAll(): ChangeCollection
    {
        $cursor = $this->collection->find();

        $items = [];

        foreach ($cursor as $document) {
            $items[] = $this->toDomain($document);
        }

        return new ChangeCollection($items);
    }

    private function toDomain(BSONDocument $document): Change
    {
        return ChangeFactory::fromArray([
            'amount' => $document['amount'],
            'currency' => $document['currency'],
            'quantity' => $document['quantity'],
        ]);
    }

    private function toDocument(Change $record): array
    {
        return [
            'amount' => $record->amount(),
            'currency' => $record->currency(),
            'quantity' => $record->quantity(),
        ];
    }
}