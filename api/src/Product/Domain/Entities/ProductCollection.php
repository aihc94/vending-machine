<?php

declare(strict_types=1);

namespace App\Product\Domain\Entities;

class ProductCollection
{
    private array $productArray;

    public function __construct(array $productArray) {
        foreach ($productArray as $product) {
            if (!$product instanceof Product) {
                throw new \InvalidArgumentException();
            }
        }

        $this->productArray = $productArray;
    }
    
    /** @return Product[] */
    public function all(): array {
        return $this->changeArray;
    }
}