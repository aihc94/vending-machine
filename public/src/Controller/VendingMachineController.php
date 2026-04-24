<?php

declare(strict_types=1);

namespace App\Controller;

use App\PurchaseManager\Application\Commands\AddMoneyToPurchaseCommand;
use App\PurchaseManager\Application\Commands\PurchaseProductCommand;
use App\PurchaseManager\Application\Queries\ObtainVendingMachineStatusQuery;
use App\PurchaseManager\Application\UseCases\ClosePurchaseUseCase;
use App\PurchaseManager\Application\UseCases\InitializePurchaseUseCase;
use App\PurchaseManager\Domain\Exceptions\AmountNotValidException;
use App\PurchaseManager\Domain\Exceptions\PurchaseIdentifierNotStoredOnMemoryException;
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
        ObtainVendingMachineStatusQuery $machineStatusQuery,
    ): Response
    {
        $purchase = $initializePurchase->execute();

        if ($purchase->restartPurchase()) {
            $response = $closePurchase->execute($purchase);

            if (!empty($response['change'])) {
                return $this->render(
                    'machine-reboot-error.html.twig',
                    [
                        'moneyFrom' => $response['moneyFrom'],
                        'change' => $response['change'],
                    ]
                );
            } else {
                return $this->redirect($request->getUri());
            }
        }

        $machineStatus = $machineStatusQuery->execute();

        return $this->render(
            'vendor-machine.html.twig',
            [
                'purchase' => $purchase->toArray(),
                'products' => $machineStatus['products'],
                'change' => $machineStatus['change'],
            ]
        );
    }

    #[Route('/add-money', name: 'add-money', methods: ['POST'])]
    public function addMoneyToPurchase(
        Request $request,
        AddMoneyToPurchaseCommand $addMoneyCommand
    ): JsonResponse
    {
        $this->validateCsrf($request);

        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['money'])) {
                return new JsonResponse(
                    [
                        'amountNotValid' => true
                    ]
                );
            }
            
            $purchase = $addMoneyCommand->execute((float)$data['money']);
        } catch (AmountNotValidException $exception) {
            return new JsonResponse(
                [
                    'amountNotValid' => true
                ]
            );
        } catch (PurchaseIdentifierNotStoredOnMemoryException $exception) {
            return new JsonResponse(
                [
                    'purchaseNotStarted' => true
                ]
            );
        }

        return new JsonResponse(
            [
                'purchase' => $purchase->toArray()
            ]
        );
    }

    #[Route('/purchase-product', name: 'purchase-product', methods: ['POST'])]
    public function purchaseProduct(
        Request $request,
        PurchaseProductCommand $command
    ): JsonResponse
    {
        $this->validateCsrf($request);

        $data = json_decode($request->getContent(), true);
            
        try {
            $response = $command->execute($data['productCode']);
        } catch (\Throwable $exception) {
            return new JsonResponse(
                [
                    'failedOnPurchase' => true,
                    'message' => $exception->getMessage()
                ]
            );
        }
        
        return new JsonResponse(
            $response
        );
    }

    #[Route('/close-purchase', name: 'close-purchase', methods: ['POST'])]
    public function closePurchase(
        Request $request,
        ClosePurchaseUseCase $useCase
    ): JsonResponse
    {
        $this->validateCsrf($request);
            
        try {
            $response = $useCase->execute();
        } catch (\Throwable $exception) {
            return new JsonResponse(
                [
                    'failedOnClosure' => true,
                    'message' => $exception->getMessage()
                ]
            );
        }
        
        return new JsonResponse(
            $response
        );
    }

    private function validateCsrf(Request $request, string $tokenId = 'ajax'): void
    {
        $token = $request->headers->get('X-CSRF-Token');

        if (!$token || !$this->isCsrfTokenValid($tokenId, $token)) {
            throw new AccessDeniedHttpException('Invalid CSRF token');
        }
    }
}