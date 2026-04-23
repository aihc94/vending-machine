<?php

declare(strict_types=1);

namespace App\Purchase\Domain\Factories;

use App\Purchase\Domain\Entities\Change;

class ChangeFactory
{
    static function fromArray(array $data): Change
    {
        return new Change(
            (float)$data['amount'],
            $data['currency'],
            (int)$data['quantity']
        );
    }
}