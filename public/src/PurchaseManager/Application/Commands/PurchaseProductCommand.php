<?php

declare(strict_types=1);

namespace App\PurchaseManager\Application\Commands;

use App\PurchaseManager\Domain\Contracts\PurchaseManagerService;
use App\PurchaseManager\Domain\ValueObjects\Purchase;
use App\Shared\Application\Contracts\SessionClient;

class PurchaseProductCommand
{
    public function __construct(
        private SessionClient $session,
        private PurchaseManagerService $purchaseService,
    ) {}

    public function execute(string $productCode): Purchase
    {
        $identifier = $this->session->get('purchaseId');

        return $this->purchaseService->purchaseProduct($identifier, $productCode);
    }
}