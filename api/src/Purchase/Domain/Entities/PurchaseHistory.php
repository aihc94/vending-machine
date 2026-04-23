<?php

declare(strict_types=1);

namespace App\Purchase\Domain\Entities;

class PurchaseHistory
{
    public const string ACTION_TYPE_CHARGE = 'charge';
    public const string ACTION_TYPE_PURCHASE = 'purchase';
    public const string ACTION_TYPE_CLOSE = 'close';
    public const string ACTION_TYPE_CLOSE_NO_PURCHASE = 'close_without_purchase';

    public function __construct(
        private string $identifier,
        private string $action,
        private float $amount,
        private string $currency,
        private ?string $productCode,
        private \DateTime $createdAt,
        private \DateTime $updatedAt,
    ) {}

    public function identifier(): string
    {
        return $this->identifier;
    }
    
    public function action(): string
    {
        return $this->action;
    }

    public function amount(): float
    {
        return $this->amount;
    }
    
    public function currency(): string
    {
        return $this->currency;
    }

    public function productCode(): ?string
    {
        return $this->productCode;
    }

    public function createdAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'identifier' => $this->identifier(),
            'action' => $this->action(),
            'amount' => $this->amount(),
            'currency' => $this->currency(),
            'productCode' => $this->productCode(),
            'createdAt' => $this->createdAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}