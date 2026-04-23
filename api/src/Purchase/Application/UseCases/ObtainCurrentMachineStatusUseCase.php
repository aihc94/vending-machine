<?php

declare(strict_types=1);

namespace App\Purchase\Application\UseCases;

use App\Product\Application\Queries\FindAllProductsQuery;
use App\Purchase\Application\Queries\FindAllChangeQuery;

class ObtainCurrentMachineStatusUseCase
{
    public function __construct(
        private FindAllProductsQuery $productQuery,
        private FindAllChangeQuery $changeQuery,
    ) {}

    public function execute(): array
    {
        $productCollection = $this->productQuery->execute();
        $changeCollection = $this->changeQuery->execute();

        return [
            'products' => $productCollection,
            'change' => $changeCollection
        ];
    }
}