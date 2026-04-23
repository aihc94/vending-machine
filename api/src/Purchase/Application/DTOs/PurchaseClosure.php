<?php

declare(strict_types=1);

namespace App\Purchase\Application\DTOs;

class PurchaseClosure
{
    public const string MONEY_FROM_CLIENT = 'client';
    public const string MONEY_FROM_CHANGE = 'change';

    public function __construct(
        private bool $isActionNeeded,
        private ?string $moneyFrom = null,
        private ?array $returnAmounts = null,
    ) {}

    public function isActionNeeded(): bool
    {
        return $this->isActionNeeded;
    }

    public function moneyFrom(): ?string
    {
        return $this->moneyFrom;
    }

    public function returnAmounts(): ?array
    {
        return $this->returnAmounts;
    }

    public function toArray(): array
    {
        return [
            'isActionNeeded' => $this->isActionNeeded(),
            'moneyFrom' => $this->moneyFrom(),
            'returnAmounts' => $this->returnAmounts(),
        ];
    }
}