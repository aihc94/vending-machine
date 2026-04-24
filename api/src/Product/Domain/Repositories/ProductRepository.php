<?php

declare(strict_types=1);

namespace App\Product\Domain\Repositories;

use App\Product\Domain\Entities\Product;
use App\Product\Domain\Entities\ProductCollection;

interface ProductRepository
{
    public function persist(Product $product): void;
    public function findByCode(string $code): ?Product;
    public function findAll(): ProductCollection;
}