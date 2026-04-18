<?php

declare(strict_types=1);

namespace App\Controller;

use App\PurchaseManager\Application\Commands\AddMoneyToPurchaseCommand;
use App\PurchaseManager\Application\UseCases\ClosePurchaseUseCase;
use App\PurchaseManager\Application\UseCases\InitializePurchaseUseCase;
use App\PurchaseManager\Domain\Exceptions\AmountNotValidException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendingMachineController extends AbstractController
{
    #[Route('/purchase', name: 'start-purchase', methods: ['GET'])]
    public function initiatePurchase(
        Request $request,
        InitializePurchaseUseCase $initializePurchase,
        ClosePurchaseUseCase $closePurchase,
    ): Response
    {
        $purchase = $initializePurchase->execute();

        if ($purchase->restartPurchase()) {
            $closePurchase->execute($purchase);
            return $this->redirect($request->getUri());
        }

        return $this->render(
            'vendor-machine.html.twig',
            [
                'purchase' => $purchase->toArray()
            ]
        );
    }

    #[Route('/add-money', name: 'add-money', methods: ['POST'])]
    public function addMoneyToPurchase(
        Request $request,
        AddMoneyToPurchaseCommand $addMoneyCommand
    ): JsonResponse
    {
        try {
            $purchase = $addMoneyCommand->execute((float)$request->get('money'));
        } catch (AmountNotValidException $exception) {
            return new JsonResponse(
                [
                    'amountNotValid' => true
                ]
            );
        }
        return new JsonResponse(
            [
                'purchase' => $purchase->toArray()
            ]
        );
    }
}