<?php

declare(strict_types=1);

namespace App\Purchase\Domain\Entities;

class PurchaseHistoryCollection
{
    private array $purchaseHistoryArray;

    public function __construct(array $purchaseHistoryArray) {
        foreach ($purchaseHistoryArray as $purchaseHistory) {
            if (!$purchaseHistory instanceof PurchaseHistory) {
                throw new \InvalidArgumentException();
            }
        }

        $this->purchaseHistoryArray = $purchaseHistoryArray;
    }
    
    /** @return PurchaseHistory[] */
    public function all(): array {
        return $this->purchaseHistoryArray;
    }

    public function toArray(): array
    {
        $arrayToReturn = [];
        foreach ($this->purchaseHistoryArray as $purchaseHistory) {
            $arrayToReturn[] = $purchaseHistory->toArray();
        }

        return $arrayToReturn;
    }
}