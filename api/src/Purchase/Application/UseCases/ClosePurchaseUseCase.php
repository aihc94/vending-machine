<?php

declare(strict_types=1);

namespace App\Purchase\Application\UseCases;

use App\Purchase\Domain\Entities\PurchaseHistory;
use App\Purchase\Domain\Entities\PurchaseHistoryCollection;
use App\Purchase\Application\Commands\CreatePurchaseHistoryCommand;
use App\Purchase\Application\DTOs\PurchaseClosure;
use App\Purchase\Application\Factories\PurchaseClosureFactory;
use App\Purchase\Application\Queries\FindAllPurchaseHistoryByIdentifierQuery;
use App\Purchase\Application\Services\ChangeGetterForValueService;
use App\Purchase\Application\Services\DecreaseChangeQuantityService;
use App\Purchase\Application\Services\PurchaseBalanceCalculartorService;

class ClosePurchaseUseCase
{
    public function __construct(
        private FindAllPurchaseHistoryByIdentifierQuery $historyQuery,
        private PurchaseBalanceCalculartorService $balanceService,
        private ChangeGetterForValueService $changeObtainerService,
        private DecreaseChangeQuantityService $changeStockUpdater,
        private CreatePurchaseHistoryCommand $historyCommand,
    ) {}

    public function execute(string $identifier): PurchaseClosure
    {
        $purchaseHistoryCollection = $this->historyQuery->execute($identifier);

        $balance = $this->balanceService->calculateBalance($purchaseHistoryCollection);

        if ($balance === 0) {
            return PurchaseClosureFactory::fromArray(
                [
                    'isActionNeeded' => false
                ]
            );
        }

        if (!$this->checkHasAnyPurchase($purchaseHistoryCollection)) {
            return $this->returnMoneyChargedByClient($purchaseHistoryCollection);
        }

        $changeToReturn = $this->changeObtainerService->getChangeForValue($balance);

        $this->changeStockUpdater->updateChangeStock($changeToReturn);

        $this->historyCommand->execute(
            $identifier,
            PurchaseHistory::ACTION_TYPE_CLOSE,
            $balance
        );

        return PurchaseClosureFactory::fromArray(
            [
                'isActionNeeded' => true,
                'moneyFrom' => PurchaseClosure::MONEY_FROM_CHANGE,
                'returnAmounts' => $changeToReturn
            ]
        );
    }

    private function checkHasAnyPurchase(PurchaseHistoryCollection $purchaseHistoryCollection): bool
    {
        $value  = false;

        foreach ($purchaseHistoryCollection->all() as $purchaseHistory) {
            if ($purchaseHistory->action() === PurchaseHistory::ACTION_TYPE_PURCHASE) {
                $value = true;
                break;
            }
        }

        return $value;
    }

    private function returnMoneyChargedByClient(
        PurchaseHistoryCollection $purchaseHistoryCollection,
        float $balance
    ): PurchaseClosure
    {
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

        return PurchaseClosureFactory::fromArray(
            [
                'isActionNeeded' => true,
                'moneyFrom' => PurchaseClosure::MONEY_FROM_CLIENT,
                'returnAmounts' => $returnArray
            ]
        );
    }
}