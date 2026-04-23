<?php

declare(strict_types=1);

namespace App\Product\Domain\Entities;

class Product
{
    public const string PRICE_CURRENCY = 'EUR';

    public function __construct(
        private string $code,
        private string $name,
        private float $price,
        private string $currency,
        private int $quantity,
    ) {}

    public function code(): string
    {
        return $this->code;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
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
            'code' => $this->code(),
            'name' => $this->name(),
            'price' => $this->price(),
            'currency' => $this->currency(),
            'quantity' => $this->quantity(),
        ];
    }
}