<?php

declare(strict_types=1);

namespace App\PurchaseManager\Application\UseCases;

use App\PurchaseManager\Domain\Contracts\PurchaseManagerService;
use App\PurchaseManager\Domain\ValueObjects\Purchase;
use App\Shared\Application\Contracts\SessionClient;

class ClosePurchaseUseCase
{
    public function __construct(
        private SessionClient $session,
        private PurchaseManagerService $purchaseService,
    ) {}

    public function execute(): array
    {
        $purchaseId = $this->session->get('purchaseId');

        $response = $this->purchaseService->closePurchase($purchaseId);

        if (!$response['isActionNeeded']) {
            $purchaseId = $this->session->remove('purchaseId');
            return [];
        }
        
        $purchaseId = $this->session->remove('purchaseId');

        return $response;
    }
}