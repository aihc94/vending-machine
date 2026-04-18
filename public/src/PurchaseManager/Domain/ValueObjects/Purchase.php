<?php

declare(strict_types=1);

namespace App\PurchaseManager\Domain\ValueObjects;

class Purchase
{
    public function __construct(
        private string $identifier,
        private float $totalAmount = 0,
        private bool $restartPurchase = false,
    ) {}

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function totalAmount(): float
    {
        return $this->totalAmount;
    }

    public function restartPurchase(): bool
    {
        return $this->restartPurchase;
    }

    public function toArray(): array
    {
        return [
            'identifier' => $this->identifier(),
            'totalAmount' => $this->totalAmount(),
            'restartPurchase' => $this->restartPurchase(),
        ];
    }
}