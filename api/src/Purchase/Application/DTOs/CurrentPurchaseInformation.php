<?php

declare(strict_types=1);

namespace App\Purchase\Application\DTOs;

use App\Product\Domain\Entities\Product;
use App\Purchase\Domain\Entities\PurchaseHistoryCollection;

class CurrentPurchaseInformation
{
    public const string MONEY_FROM_CLIENT = 'client';
    public const string MONEY_FROM_CHANGE = 'change';

    public function __construct(
        private string $identifier,
        private PurchaseHistoryCollection $history,
        private float $currentBalance,
        private ?Product $productBeingPurchased = null,
        private array $changeToReturn = [],
        private string $moneyFrom = self::MONEY_FROM_CHANGE,
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

    public function productBeingPurchased(): ?Product
    {
        return $this->productBeingPurchased;
    }

    public function changeToReturn(): array
    {
        return $this->changeToReturn;
    }

    public function moneyFrom(): string 
    {
        return $this->moneyFrom;
    }

    public function toArray(): array
    {
        return [
            'identifier' => $this->identifier(),
            'history' => $this->history()->toArray(),
            'currentBalance' => $this->currentBalance(),
            'productBeingPurchased' => $this->productBeingPurchased(),
            'changeToReturn' => $this->changeToReturn(),
            'moneyFrom' => $this->moneyFrom(),
        ];
    }
}