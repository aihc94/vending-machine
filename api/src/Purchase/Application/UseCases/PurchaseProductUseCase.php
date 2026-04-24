<?php

declare(strict_types=1);

namespace App\Purchase\Application\UseCases;

use App\Product\Application\Commands\UpdateOrAddProductCommand;
use App\Product\Application\Queries\FindProductByCodeQuery;
use App\Product\Domain\Entities\Product;
use App\Purchase\Application\Commands\CreatePurchaseHistoryCommand;
use App\Purchase\Application\DTOs\CurrentPurchaseInformation;
use App\Purchase\Application\Factories\CurrentPurchaseInformationFactory;
use App\Purchase\Application\Queries\FindAllPurchaseHistoryByIdentifierQuery;
use App\Purchase\Application\Services\ChangeGetterForValueService;
use App\Purchase\Application\Services\DecreaseChangeQuantityService;
use App\Purchase\Application\Services\PurchaseBalanceCalculartorService;
use App\Purchase\Application\UseCases\ClosePurchaseFromClientInputUseCase;
use App\Purchase\Domain\Entities\PurchaseHistory;
use App\Purchase\Domain\Entities\PurchaseHistoryCollection;
use App\Purchase\Domain\Exceptions\ChangeNotAvailableForAmountException;

class PurchaseProductUseCase
{
    public function __construct(
        private CreatePurchaseHistoryCommand $historyCommand,
        private FindAllPurchaseHistoryByIdentifierQuery $historyQuery,
        private PurchaseBalanceCalculartorService $balanceService,
        private FindProductByCodeQuery $productQuery,
        private ChangeGetterForValueService $changeGetterService,
        private UpdateOrAddProductCommand $updateProductCommand,
        private DecreaseChangeQuantityService $changeUpdaterService,
        private ClosePurchaseFromClientInputUseCase $returnFromClientUseCase,
    ) {}

    public function execute(
        string $identifier,
        string $productCode
    ): CurrentPurchaseInformation
    {
        $purchaseHistoryCollection = $this->historyQuery->execute($identifier);

        $product = $this->productQuery->execute($productCode);

        $amountToReturn = $this->validate($product, $purchaseHistoryCollection);

        if ($amountToReturn !== 0) {
            try {
                $changeToReturn = $this->changeGetterService->getChangeForValue($amountToReturn);
            } catch (ChangeNotAvailableForAmountException) {
                return $this->returnFromClientUseCase->execute($identifier);
            }
        }

        $this->updateProductPurchaseInformation($identifier, $product);

        $this->historyCommand->execute(
            $identifier,
            PurchaseHistory::ACTION_TYPE_CLOSE,
            $amountToReturn,
            $product->currency()
        );

        if (isset($changeToReturn)) {
            $this->changeUpdaterService->updateChangeStock($changeToReturn);
        }

        $purchaseHistoryCollection = $this->historyQuery->execute($identifier);

        return CurrentPurchaseInformationFactory::fromArray(
            [
                'identifier' => $identifier,
                'history' => $purchaseHistoryCollection,
                'currentBalance' => 0,
                'product' => $product,
                'changeToReturn' => $changeToReturn ?? [],
            ]
        );
    }

    private function validate(
        Product $product,
        PurchaseHistoryCollection $purchaseHistoryCollection
    ): float
    {
        if ($product->quantity() === 0) {
            throw new \Exception('Product out of stock');
        }

        $balance = $this->balanceService->calculateBalance($purchaseHistoryCollection);

        $amountToReturn = $balance - $product->price();
        
        if ($amountToReturn < 0) {
            throw new \Exception('Put more money in the bag');
        }

        return round($amountToReturn, 2);
    }

    private function updateProductPurchaseInformation(
        string $identifier,
        Product $product,
    ): void
    {
        $this->historyCommand->execute(
            $identifier,
            PurchaseHistory::ACTION_TYPE_PURCHASE,
            $product->price(),
            $product->currency(),
            $product->code()
        );

        $this->updateProductCommand->execute(
            $product->code(),
            $product->name(),
            $product->price(),
            ($product->quantity() - 1)
        );
    }
}