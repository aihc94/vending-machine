<?php

declare(strict_types=1);

namespace App\Purchase\Application\UseCases;

use App\Purchase\Application\DTOs\CurrentPurchaseInformation;

class AddMoneyToPurchaseUseCase
{
    public function execute(
        string $identifier,
        float $amount,
        string $currency,
    ): CurrentPurchaseInformation
    {
        
    }
}