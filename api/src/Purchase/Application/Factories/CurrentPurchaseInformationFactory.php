<?php

declare(strict_types=1);

namespace App\Purchase\Application\Factories;

use App\Purchase\Application\DTOs\CurrentPurchaseInformation;

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