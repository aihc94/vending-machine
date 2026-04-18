<?php

declare(strict_types=1);

namespace App\Controller;

use App\PurchaseManager\Application\UseCases\ClosePurchaseUseCase;
use App\PurchaseManager\Application\UseCases\InitializePurchaseUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendingMachineController extends AbstractController
{
    #[Route('/purchase', name: 'start-purchase', methods: ['GET'])]
    public function initiatePurchaseSession(
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
            $purchase->toArray()
        );
    }
}