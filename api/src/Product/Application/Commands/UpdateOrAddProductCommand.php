<?php

declare(strict_types=1);

namespace App\Product\Application\Commands;

use App\Product\Domain\Entities\Product;
use App\Product\Domain\Factories\ProductFactory;
use App\Product\Domain\Repositories\ProductRepository;

class UpdateOrAddProductCommand
{
    public function __construct(
        private ProductRepository $repository,
    ) {}

    public function execute(
        string $code,
        string $name,
        float $price,
        int $quantity
    ): Product
    {
        $product = ProductFactory::fromArray(
            [
                'code' => $code,
                'name' => $name,
                'price' => $price,
                'currency' => Product::PRICE_CURRENCY,
                'quantity' => $quantity,
            ]
        );

        $this->repository->persist($product);

        return $product;
    }
}