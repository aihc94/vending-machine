<?php

declare(strict_types=1);

namespace App\PurchaseManager\Application\UseCases;

use App\PurchaseManager\Domain\ValueObjects\Purchase;
use App\Shared\Application\Contracts\SessionClient;

class ClosePurchaseUseCase
{
    public function __construct(
        private SessionClient $session
    ) {}

    public function execute(Purchase $purchase): void
    {
        $this->session->remove('purchaseId');
    }
}