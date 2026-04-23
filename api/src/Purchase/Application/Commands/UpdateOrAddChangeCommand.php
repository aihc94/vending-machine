<?php

declare(strict_types=1);

namespace App\Purchase\Application\Commands;

use App\Purchase\Domain\Entities\Change;
use App\Purchase\Domain\Factories\ChangeFactory;
use App\Purchase\Domain\Repositories\ChangeRepository;

class UpdateOrAddChangeCommand
{
    public function __construct(
        private ChangeRepository $repository,
    ) {}

    public function execute(
        float $amount,
        int $quantity,
    ): Change
    {
        $change = ChangeFactory::fromArray(
            [
                'amount' => $amount,
                'currency' => Change::CURRENCY,
                'quantity' => $quantity
            ]
        );

        $this->repository->persist($change);

        return $change;
    }
}