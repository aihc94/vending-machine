<?php

declare(strict_types=1);

namespace App\Purchase\Application\Factories;

use App\Purchase\Application\DTOs\PurchaseClosure;

class PurchaseClosureFactory
{
    static function fromArray(array $data): PurchaseClosure
    {
        return new PurchaseClosure(
            $data['isActionNeeded'],
            $data['moneyFrom'] ?? null,
            $data['returnAmounts'] ?? null,
        );
    }
}