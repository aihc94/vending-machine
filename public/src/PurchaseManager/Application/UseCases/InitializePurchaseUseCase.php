<?php

declare(strict_types=1);

namespace App\PurchaseManager\Application\UseCases;

use App\PurchaseManager\Domain\Factories\PurchaseFactory;
use App\PurchaseManager\Domain\ValueObjects\Purchase;
use App\Shared\Application\Contracts\SessionClient;

readonly class InitializePurchaseUseCase
{
    public function __construct(
        private SessionClient $session,
    ) {}
    

    public function execute(): Purchase
    {
        $data = $this->checkPurchaseAndInitializeIfNotExists();

        return PurchaseFactory::fromArray($data);
    }

    private function checkPurchaseAndInitializeIfNotExists(): array
    {
        if ($this->session->has('purchaseId')) {
            return [
                'identifier' => $this->session->get('purchaseId'),
                'restartPurchase' => true,
            ];
        }
        
        $sessionId = uniqid();
        $this->session->set('purchaseId', $sessionId);

        return [
            'identifier' => $this->session->get('purchaseId')
        ];
    }
}