<?php

declare(strict_types=1);

namespace App\PurchaseManager\Domain\Contracts;

use App\PurchaseManager\Domain\ValueObjects\Purchase;

interface PurchaseManagerService
{
    public function addMoneyToPurchase(
        string $indentifier,
        float $amount,
    ): Purchase;
}