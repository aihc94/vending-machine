<?php

declare(strict_types=1);

namespace App\Product\Application\Queries;

use App\Product\Domain\Entities\Product;
use App\Product\Domain\Repositories\ProductRepository;

class FindProductByCodeQuery
{
    public function __construct(
        private ProductRepository $repository,
    ) {}

    public function execute(string $code): ?Product
    {
        return $this->repository->findByCode($code);
    }
}