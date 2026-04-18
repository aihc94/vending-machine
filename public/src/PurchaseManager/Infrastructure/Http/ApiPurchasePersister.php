<?php

declare(strict_types=1);

namespace App\PurchaseManager\Infrastructure\Http;

use App\PurchaseManager\Domain\Contracts\PurchaseManagerService;
use App\Shared\Application\Contracts\HttpClient;

class ApiPurchaseManagerService implements PurchaseManagerService
{
    public function __construct(
        private HttpClient $httpClient
    ) {}

    public function addMoneyToPurchase(): Purchase
    {
        
    }
}