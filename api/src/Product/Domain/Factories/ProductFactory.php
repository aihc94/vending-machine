<?php

declare(strict_types=1);

namespace App\Product\Domain\Factories;

use App\Product\Domain\Entities\Product;

class ProductFactory
{
    static function fromArray(array $data): Product
    {
        return new Product(
            $data['code'],
            $data['name'],
            $data['price'],
            $data['currency'],
            $data['quantity']
        );
    }
}