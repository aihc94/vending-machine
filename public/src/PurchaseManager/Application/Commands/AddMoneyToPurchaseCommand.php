<?php

declare(strict_types=1);

namespace App\PurchaseManager\Application\UseCases;

use App\PurchaseManager\Domain\Exceptions\AmountNotValidException;
use App\PurchaseManager\Domain\ValueObjects\Purchase;
use App\Shared\Application\Contracts\SessionClient;

class AddMoneyToPurchaseCommand
{
    public function __construct(
        private SessionClient $session
    ) {}

    public function execute(float $money): Purchase
    {
        $this->validateAmount($money);
    }

    private function validateAmount(float $money): void 
    {
        if (!in_array($money, Purchase::VALID_AMOUNTS)) {
            throw new AmountNotValidException('Not valid amount');
        }
    }
}