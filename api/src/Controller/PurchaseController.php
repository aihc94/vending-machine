<?php

declare(strict_types=1);

namespace App\Controller;

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
    ): JsonResponse
    {
        $purchase = 10;

        return new JsonResponse();
    }
}