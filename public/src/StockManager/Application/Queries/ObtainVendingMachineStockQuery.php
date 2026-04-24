<?php

declare(strict_types=1);

namespace App\StockManager\Application\Queries;

use App\StockManager\Domain\Contracts\StockManagerService;

class ObtainVendingMachineStockQuery
{
    public function __construct(
        private StockManagerService $stockService,
    ) {}

    public function execute(): array
    {
        return $this->stockService->obtainMachineStock();
    }
}