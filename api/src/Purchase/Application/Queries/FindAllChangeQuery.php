<?php

declare(strict_types=1);

namespace App\Purchase\Application\Queries;

use App\Purchase\Domain\Entities\ChangeCollection;
use App\Purchase\Domain\Repositories\ChangeRepository;

class FindAllChangeQuery
{
    public function __construct(
        private ChangeRepository $repository,
    ) {}

    public function execute(): ChangeCollection
    {
        return $this->repository->findAll();
    }
}