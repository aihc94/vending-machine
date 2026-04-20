<?php

declare(strict_types=1);

namespace App\PurchaseManager\Application\Commands;

use App\PurchaseManager\Domain\Contracts\PurchaseManagerService;
use App\PurchaseManager\Domain\Exceptions\AmountNotValidException;
use App\PurchaseManager\Domain\ValueObjects\Purchase;
use App\Shared\Application\Contracts\SessionClient;
use App\Shared\Domain\Exceptions\PurchaseIdentifierNotStoredOnMemoryException;

class AddMoneyToPurchaseCommand
{
    public function __construct(
        private SessionClient $session,
        private PurchaseManagerService $purchaseService,
    ) {}

    public function execute(float $money): Purchase
    {
        $this->validate($money);
        $purchaseIdentifier = $this->session->get('purchaseId');
        return $this->purchaseService->addMoneyToPurchase(
            $purchaseIdentifier,
            $money,
            Purchase::CURRENCY
        );
    }

    private function validate(float $money): void 
    {
        if (!in_array($money, Purchase::VALID_AMOUNTS)) {
            throw new AmountNotValidException('Not valid amount');
        }

        if (!$this->session->has('purchaseId')) {
            throw new PurchaseIdentifierNotStoredOnMemoryException();
        }
    }
}