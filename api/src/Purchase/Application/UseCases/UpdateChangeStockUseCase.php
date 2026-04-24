<?php

declare(strict_types=1);

namespace App\Purchase\Application\UseCases;

use App\Purchase\Application\Commands\UpdateOrAddChangeCommand;
use App\Purchase\Application\Queries\FindChangeByAmountQuery;

class UpdateChangeStockUseCase
{
    public function __construct(
        private FindChangeByAmountQuery $changeQuery,
        private UpdateOrAddChangeCommand $changeCommand,
    ) {}  
    
    public function execute(
        float $amount,
        int $quantity,
    ): void
    {
        $change = $this->changeQuery->execute($amount);

        if (isset($change)) {
            $quantity += $change->quantity();
        }

        $this->changeCommand->execute(
            amount: $amount,
            quantity: $quantity
        );
    }
}