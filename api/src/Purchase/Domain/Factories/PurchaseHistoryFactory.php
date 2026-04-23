<?php

declare(strict_types=1);

namespace App\Purchase\Domain\Factories;

use App\Purchase\Domain\Entities\PurchaseHistory;

class PurchaseHistoryFactory
{
    static function fromArray(array $data): PurchaseHistory
    {
        self::validate($data);

        return new PurchaseHistory(
            $data['identifier'],
            $data['action'],
            $data['amount'],
            $data['currency'],
            $data['productCode'] ?? null,
            isset($data['createdAt']) ? 
                new \DateTime($data['createdAt']) : new \DateTime(),
            isset($data['updatedAt']) ?
                new \DateTime($data['updatedAt']) : new \DateTime(),
        );
    }

    static function validate(array $data): void
    {
        if (
            !isset($data['identifier']) ||
            !isset($data['action']) ||
            !isset($data['amount']) ||
            !isset($data['currency'])
        ) {
            throw new \Exception('Bad configuration');
        }
    }
}