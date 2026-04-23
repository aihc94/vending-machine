<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Persistence\MongoDb;

use App\Product\Domain\Entities\Product;
use App\Product\Domain\Entities\ProductCollection;
use App\Product\Domain\Factories\ProductFactory;
use App\Product\Domain\Repositories\ProductRepository;
use MongoDB\Model\BSONDocument;
use MongoDB\Client;
use MongoDB\Collection;

class MongoDbProductRepository implements ProductRepository
{
    //This valiues must be provided by the ServiceProvider by the construct with env variables
    public const string MONGO_URL = 'mongodb://admin:notYourProblem@database:27017';
    public const string MONGO_DATABASE = 'vending_machine';
    public const string MONGO_TABLE = 'product';

    private Collection $collection;

    public function __construct() {
        $client = new Client(self::MONGO_URL);

        $database = $client->selectDatabase(self::MONGO_DATABASE);

        $collection = $database->selectCollection(self::MONGO_TABLE);

        $this->collection = $collection;
    }

    public function persist(Product $record): void
    {
        $this->collection->updateOne(
            ['code' => $record->code()],
            ['$set' => $this->toDocument($record)],
            ['upsert' => true]
        );
    }

    public function findByCode(string $code): Product
    {
        $result = $this->collection->findOne([
            'code' => $code
        ]);

        return $this->toDomain($result);
    }

    public function findAll(): ProductCollection
    {
        $cursor = $this->collection->find();

        $items = [];

        foreach ($cursor as $document) {
            $items[] = $this->toDomain($document);
        }

        return new ProductCollection($items);
    }

    private function toDomain(BSONDocument $document): Product
    {
        return ProductFactory::fromArray([
            'code' => $document['code'],
            'name' => $document['name'],
            'price' => $document['price'],
            'currency' => $document['currency'],
            'quantity' => $document['quantity'],
        ]);
    }

    private function toDocument(Product $record): array
    {
        return [
            'code' => $record->code(),
            'name' => $record->name(),
            'price' => $record->price(),
            'currency' => $record->currency(),
            'quantity' => $record->quantity(),
        ];
    }
}