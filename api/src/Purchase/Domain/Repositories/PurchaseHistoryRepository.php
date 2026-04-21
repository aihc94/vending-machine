<?php

declare(strict_types=1);

namespace App\Purchase\Domain\Repositories;

use App\Purchase\Domain\Entities\PurchaseHistory;
use App\Purchase\Domain\Entities\PurchaseHistoryCollection;

interface PurchaseHistoryRepository
{
    public function persist(PurchaseHistory $record): void;

    public function findAllByIdentifier(string $identifier): PurchaseHistoryCollection;
}