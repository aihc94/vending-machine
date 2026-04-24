<?php

declare(strict_types=1);

namespace App\StockManager\Application\Commands;

use App\StockManager\Domain\Contracts\StockManagerService;

class UpdateChangeStockCommand
{
    public function __construct(
        private StockManagerService $stockService,
    ) {}

    public function execute(
        float $amount,
        int $quantity,
    ): void
    {
        $this->stockService->updateChangeStock(
            $amount,
            $quantity
        );
    }
}