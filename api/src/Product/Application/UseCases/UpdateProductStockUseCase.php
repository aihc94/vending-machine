<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases;

use App\Product\Application\Commands\UpdateOrAddProductCommand;
use App\Product\Application\Queries\FindProductByCodeQuery;

class UpdateProductStockUseCase
{
    public function __construct(
        private FindProductByCodeQuery $productQuery,
        private UpdateOrAddProductCommand $productCommand,
    ) {}  
    
    public function execute(
        string $code,
        string $name,
        float $price,
        int $quantity,
    ): void
    {
        $product = $this->productQuery->execute($code);

        if (isset($product)) {
            $quantity += $product->quantity();
        }

        $this->productCommand->execute(
            code: $code,
            name: $name,
            price: $price,
            quantity: $quantity
        );
    }
}