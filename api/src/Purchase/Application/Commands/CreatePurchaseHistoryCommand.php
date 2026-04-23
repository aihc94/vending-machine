<?php

declare(strict_types=1);

namespace App\Purchase\Application\Commands;

use App\Purchase\Domain\Entities\PurchaseHistory;
use App\Purchase\Domain\Factories\PurchaseHistoryFactory;
use App\Purchase\Domain\Repositories\PurchaseHistoryRepository;

class CreatePurchaseHistoryCommand
{
    public function __construct(
        private PurchaseHistoryRepository $purchaseHistoryRepository,
    ) {}

    public function execute(
        string $identifier,
        string $action,
        float $amount,
        string $currency,
    ): PurchaseHistory
    {
        $purchaseHistory = PurchaseHistoryFactory::fromArray(
            [
                'identifier' => $identifier,
                'action' => $action,
                'amount' => $amount,
                'currency' => $currency
            ]
        );

        $this->purchaseHistoryRepository->persist($purchaseHistory);

        return $purchaseHistory;
    }
}