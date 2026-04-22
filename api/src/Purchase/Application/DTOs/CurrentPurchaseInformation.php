<?php

declare(strict_types=1);

namespace App\Purchase\Application\DTOs;

use App\Purchase\Domain\Entities\PurchaseHistoryCollection;

class CurrentPurchaseInformation
{
    public function __construct(
        private string $identifier,
        private PurchaseHistoryCollection $history,
        private float $currentBalance
    ) {}

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function history(): PurchaseHistoryCollection
    {
        return $this->history;
    }

    public function currentBalance(): float
    {
        return $this->currentBalance;
    }
}