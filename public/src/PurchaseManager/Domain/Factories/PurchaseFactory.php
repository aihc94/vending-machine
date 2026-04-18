<?php

declare(strict_types=1);

namespace App\PurchaseManager\Domain\Factories;

use App\PurchaseManager\Domain\ValueObjects\Purchase;
use Exception;

class PurchaseFactory
{
    static function fromArray(array $data): Purchase
    {
        self::validate($data);

        return new Purchase(
            $data['identifier'],
            $data['totalAmount'] ?? 0,
            (isset($data['restartPurchase'])  && $data['restartPurchase'] === true) ? 
            true : false
        );
    }

    static function validate(array $data): void
    {
        if (empty($data['identifier'])) {
            throw new Exception('Bad configuration');
        }
    }
}