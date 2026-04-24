<?php

declare(strict_types=1);

namespace App\StockManager\Application\Commands;

use App\StockManager\Domain\Contracts\StockManagerService;

class UpdateProductStockCommand
{
    public function __construct(
        private StockManagerService $stockService,
    ) {}

    public function execute(
        string $code,
        string $name,
        float $price,
        int $quantity,
    ): void
    {
        $this->stockService->updateProductStock(
            $code,
            $name,
            $price,
            $quantity
        );
    }
}