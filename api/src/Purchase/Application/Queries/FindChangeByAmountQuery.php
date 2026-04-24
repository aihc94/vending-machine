<?php

declare(strict_types=1);

namespace App\Purchase\Application\Queries;

use App\Purchase\Domain\Entities\Change;
use App\Purchase\Domain\Repositories\ChangeRepository;

class FindChangeByAmountQuery
{
    public function __construct(
        private ChangeRepository $repository,
    ) {}

    public function execute(float $amount): ?Change
    {
        return $this->repository->findByAmount($amount);
    } 
}