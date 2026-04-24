<?php

declare(strict_types=1);

namespace App\Controller;

use App\Purchase\Application\UseCases\AddMoneyToPurchaseUseCase;
use App\Purchase\Application\UseCases\ClosePurchaseFromClientInputUseCase;
use App\Purchase\Application\UseCases\ObtainCurrentMachineStatusUseCase;
use App\Purchase\Application\UseCases\PurchaseProductUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    //This valiues must be provided by the ServiceProvider by the construct with env variables
    public const string API_SECRET = 'your-worst-secret';

    #[Route('/add-money-to-purchase', name: 'add-money', methods: ['POST'])]
    public function addMoneyToPurchase(
        Request $request,
        AddMoneyToPurchaseUseCase $useCase,
    ): JsonResponse
    {
        if ($response = $this->checkApiSecret($request)) {
            return $response;
        }
    
        $response = $useCase->execute(
            $request->get('identifier'),
            (float)$request->get('amount'),
            $request->get('currency'),
        );

        return new JsonResponse(
            [
                'identifier' => $response->identifier(),
                'currentBalance' => $response->currentBalance(),
            ]
        );
    }

    #[Route('/purchase-product', name: 'purchase-product', methods: ['POST'])]
    public function purchaseProduct(
        Request $request,
        PurchaseProductUseCase $useCase,
    ): JsonResponse
    {
        if ($response = $this->checkApiSecret($request)) {
            return $response;
        }

        try {
            $response = $useCase->execute(
                $request->get('identifier'),
                $request->get('productCode')
            );
        } catch (\Throwable $exception) {
            return new JsonResponse(
                [
                    'error' => true,
                    'message' => $exception->getMessage(),
                ]
            );
        }

        return new JsonResponse(
            [
                'identifier' => $response->identifier(),
                'changeToReturn' => $response->changeToReturn(),
                'purchaseHistory' => $response->history()->toArray(),
                'productProvided' => $response->productBeingPurchased()?->toArray(),
                'moneyFrom' => $response->moneyFrom(),
            ]
        );
    }

    #[Route('/machine-status', name: 'machine-status', methods: ['GET'])]
    public function machineStatus(
        Request $request,
        ObtainCurrentMachineStatusUseCase $useCase,
    ): JsonResponse
    {
        if ($response = $this->checkApiSecret($request)) {
            return $response;
        }

        $response = $useCase->execute();

        return new JsonResponse(
            [
                'products' => $response['products']->toArray(),
                'change' => $response['change']->toArray(),
            ]
        );
    }

    #[Route('/close-purchase', name: 'close-purchase', methods: ['POST'])]
    public function closePurchase(
        Request $request,
        ClosePurchaseFromClientInputUseCase $useCase,
    ): JsonResponse
    {
        if ($response = $this->checkApiSecret($request)) {
            return $response;
        }

        $response = $useCase->execute($request->get('identifier'));

        return new JsonResponse(
            [
                'identifier' => $response->identifier(),
                'changeToReturn' => $response->changeToReturn(),
                'moneyFrom' => $response->moneyFrom()
            ]
        );
    }

    private function checkApiSecret(Request $request): ?JsonResponse
    {
        $secret = $request->headers->get('X-API-SECRET');

        if ($secret !== self::API_SECRET) {
            return new JsonResponse(
                ['error' => 'Unauthorized'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        return null;
    }
}