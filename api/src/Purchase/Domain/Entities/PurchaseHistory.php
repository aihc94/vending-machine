<?php

declare(strict_types=1);

namespace App\Purchase\Domain\Entities;

class PurchaseHistory
{
    public function __construct(
        private string $identifier,
        private string $action,
        private float $amount,
        private string $currency,
        private \DateTime $createdAt,
        private \DateTime $updatedAt,
    ) {}
}