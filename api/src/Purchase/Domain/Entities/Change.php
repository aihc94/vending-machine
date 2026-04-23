<?php

declare(strict_types=1);

namespace App\Purchase\Domain\Entities;

class Change
{
    public const string CURRENCY = 'EUR';

    public function __construct(
        private float $amount,
        private string $currency,
        private int $quantity
    ) {}

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount(),
            'currency' => $this->currency(),
            'quantity' => $this->quantity(),
        ];
    }
}