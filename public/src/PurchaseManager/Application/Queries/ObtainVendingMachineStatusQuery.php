<?php

declare(strict_types=1);

namespace App\PurchaseManager\Application\Queries;

use App\PurchaseManager\Domain\Contracts\PurchaseManagerService;

class ObtainVendingMachineStatusQuery
{
    public function __construct(
        private PurchaseManagerService $purchaseService,
    ) {}

    public function execute(): array
    {
        return $this->purchaseService->obtainMachineStatus();
    }
}