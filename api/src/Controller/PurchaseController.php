<?php

declare(strict_types=1);

namespace App\Controller;

use App\Purchase\Application\UseCases\AddMoneyToPurchaseUseCase;
use App\Purchase\Application\UseCases\PurchaseProductUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    #[Route('/add-money-to-purchase', name: 'add-money', methods: ['POST'])]
    public function addMoneyToPurchase(
        Request $request,
        AddMoneyToPurchaseUseCase $useCase,
    ): JsonResponse
    {
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
        $response = $useCase->execute(
            $request->get('identifier'),
            (int)$request->get('productId')
        );

        return new JsonResponse(
            [
                'identifier' => $response->identifier(),
                'currentBalance' => $response->currentBalance(),
            ]
        );
    }
}