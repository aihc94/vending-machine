<?php

declare(strict_types=1);

namespace App\Purchase\Application\Queries;

use App\Purchase\Domain\Entities\PurchaseHistoryCollection;
use App\Purchase\Domain\Repositories\PurchaseHistoryRepository;

class FindAllPurchaseHistoryByIdentifierQuery
{
    public function __construct(
        PurchaseHistoryRepository $purchaseHistoryRepository
    ) {}

    public function execute(string $identifier): PurchaseHistoryCollection
    {
        return $this->purchaseHistoryRepository->findByIdentifier(
            $identifier
        );
    }
}