<?php

declare(strict_types=1);

namespace App\Product\Application\Queries;

use App\Product\Domain\Entities\ProductCollection;
use App\Product\Domain\Repositories\ProductRepository;

class FindAllProductsQuery
{
    public function __construct(
        private ProductRepository $repository
    ) {}

    public function execute(): ProductCollection
    {
        return $this->repository->findAll();
    }
}