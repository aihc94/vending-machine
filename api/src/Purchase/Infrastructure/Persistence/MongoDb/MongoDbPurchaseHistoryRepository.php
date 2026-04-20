<?php

declare(strict_types=1);

namespace App\Purchase\Infrastructure\Persistence\Mongodb;

use App\Purchase\Domain\Entities\PurchaseHistory;

class MongoDbPurchaseHistoryRepository
{
    public function persist(PurchaseHistory $record): void
    {
        
    }
}