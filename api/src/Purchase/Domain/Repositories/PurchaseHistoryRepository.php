<?php

declare(strict_types=1);

namespace App\Purchase\Domain\Repositories;

use App\Purchase\Domain\Entities\PurchaseHistory;

interface PurchaseHistoryRepository
{
    public function persist(PurchaseHistory $record): void;
}