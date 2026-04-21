<?php

declare(strict_types=1);

namespace App\Purchase\Application\UseCases;

use App\Purchase\Application\Commands\CreatePurchaseHistoryCommand;
use App\Purchase\Application\DTOs\CurrentPurchaseInformation;
use App\Purchase\Application\Factories\CurrentPurchaseInformationFactory;
use App\Purchase\Application\Services\PurchaseBalanceCalculartorService;
use App\Purchase\Domain\Entities\PurchaseHistory;

class AddMoneyToPurchaseUseCase
{
    public function __construct(
        CreatePurchaseHistoryCommand $command,
        FindAllPurchaseHistoryByIdentifierQuery $query,
        PurchaseBalanceCalculartorService $balanceService,
    ) {}

    public function execute(
        string $identifier,
        float $amount,
        string $currency,
    ): CurrentPurchaseInformation
    {
        $data = [
            'identifier' => $identifier,
            'action' => PurchaseHistory::ACTION_TYPE_CHARGE,
            'amount' => $amount,
            'currency' => $currency
        ];

        $this->command->execute($data);

        $purchaseHistoryCollection = $this->query->execute($identifier);

        $balance = $balanceService->calculateBalance($purchaseHistoryCollection);

        $purchaseInformation = [
            'identifier' => $identifier,
            'history' => $purchaseHistoryCollection->all(),
            'currentBalance' => $balance
        ];

        return CurrentPurchaseInformationFactory::fromArray(
            $purchaseInformation
        );
    }
}