<?php

declare(strict_types=1);

namespace App\Purchase\Application\Services;

use App\Purchase\Domain\Entities\PurchaseHistory;
use App\Purchase\Domain\Entities\PurchaseHistoryCollection;

class PurchaseBalanceCalculartorService
{
    public function calculateBalance(
        PurchaseHistoryCollection $purchaseHistoryCollection
    ): float 
    {
        $balance = 0;

        foreach ($purchaseHistoryCollection->all() as $purchaseHistory) {
            if (
                $purchaseHistory->action() === PurchaseHistory::ACTION_TYPE_CHARGE
            ) {
                $balance += $purchaseHistory->amount();
            }
            if (
                $purchaseHistory->action() === PurchaseHistory::ACTION_TYPE_PURCHASE
            ) {
                $balance -= $purchaseHistory->amount();
            }
            if (
                $purchaseHistory->action() === PurchaseHistory::ACTION_TYPE_CLOSE
            ) {
                $balance -= $purchaseHistory->amount();
            }
        }

        return $balance;
    }
}