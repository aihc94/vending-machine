<?php

declare(strict_types=1);

namespace App\Purchase\Domain\Entities;

class Change
{
    public function __construct(
        private float $amount,
        private int $quantity
    ) {}

    public function amount(): float
    {
        return $this->float;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}