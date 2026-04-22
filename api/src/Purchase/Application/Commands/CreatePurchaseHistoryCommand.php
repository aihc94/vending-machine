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

    public function execute(array $data): PurchaseHistory
    {
        $purchaseHistory = PurchaseHistoryFactory::fromArray($data);

        $this->purchaseHistoryRepository->persist($purchaseHistory);

        return $purchaseHistory;
    }
}