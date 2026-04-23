<?php

declare(strict_types=1);

namespace App\Purchase\Application\DTOs;

use App\Product\Domain\Entities\Product;
use App\Purchase\Domain\Entities\PurchaseHistoryCollection;

class CurrentPurchaseInformation
{
    public function __construct(
        private string $identifier,
        private PurchaseHistoryCollection $history,
        private float $currentBalance,
        private ?Product $productBeingPurchased = null,
        private array $changeToReturn = [],
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

    public function productBeingPurchased(): Product
    {
        return $this->productBeingPurchased;
    }

    public function changeToReturn(): array
    {
        return $this->changeToReturn;
    }
}