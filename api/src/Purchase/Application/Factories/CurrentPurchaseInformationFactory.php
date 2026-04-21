<?php

declare(strict_types=1);

namespace App\Purchase\Domain\Factories;

use App\Purchase\Domain\Entities\PurchaseHistory;

class CurrentPurchaseInformationFactory
{
    static function fromArray(array $data): CurrentPurchaseInformation
    {
        self::validate($data);

        return new CurrentPurchaseInformation(
            $data['identifier'],
            $data['history'] ?? [],
            $data['currentBalance']
        );
    }

    static function validate(array $data): void
    {
        if (
            !isset($data['identifier'])
        ) {
            throw new \Exception('Bad configuration');
        }
    }
}