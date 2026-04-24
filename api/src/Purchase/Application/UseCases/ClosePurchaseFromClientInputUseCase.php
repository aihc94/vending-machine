<?php

declare(strict_types=1);

namespace App\Purchase\Application\UseCases;

use App\Purchase\Domain\Entities\PurchaseHistory;
use App\Purchase\Domain\Entities\PurchaseHistoryCollection;
use App\Purchase\Application\Commands\CreatePurchaseHistoryCommand;
use App\Purchase\Application\DTOs\CurrentPurchaseInformation;
use App\Purchase\Application\Factories\CurrentPurchaseInformationFactory;
use App\Purchase\Application\Queries\FindAllPurchaseHistoryByIdentifierQuery;
use App\Purchase\Application\Services\PurchaseBalanceCalculartorService;

class ClosePurchaseFromClientInputUseCase
{
    public function __construct(
        private FindAllPurchaseHistoryByIdentifierQuery $historyQuery,
        private PurchaseBalanceCalculartorService $balanceService,
        private CreatePurchaseHistoryCommand $historyCommand,
    ) {}

    public function execute(string $identifier): CurrentPurchaseInformation
    {
        $purchaseHistoryCollection = $this->historyQuery->execute($identifier);

        $balance = $this->balanceService->calculateBalance($purchaseHistoryCollection);

        if ($balance === 0) {
            return CurrentPurchaseInformationFactory::fromArray(
                [
                    'identifier' => $identifier,
                ]
            );
        }

        $returnArray = [];

        foreach ($purchaseHistoryCollection->all() as $purchaseHistory) {
            if ($purchaseHistory->action() === PurchaseHistory::ACTION_TYPE_CHARGE) {
                $returnArray[] = [
                    'amount' => $purchaseHistory->amount(),
                ];
            }
        }

        $this->historyCommand->execute(
            $identifier,
            PurchaseHistory::ACTION_TYPE_CLOSE_NO_PURCHASE,
            $balance
        );

        return CurrentPurchaseInformationFactory::fromArray(
            [
                'identifier' => $identifier,
                'moneyFrom' => CurrentPurchaseInformation::MONEY_FROM_CLIENT,
                'changeToReturn' => $returnArray,
                'currentBalance' => $balance,
            ]
        );
    }
}