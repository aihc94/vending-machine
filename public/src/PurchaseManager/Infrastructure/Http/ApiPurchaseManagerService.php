<?php

declare(strict_types=1);

namespace App\PurchaseManager\Infrastructure\Http;

use App\PurchaseManager\Domain\Contracts\PurchaseManagerService;
use App\PurchaseManager\Domain\Factories\PurchaseFactory;
use App\PurchaseManager\Domain\ValueObjects\Purchase;
use App\Shared\Application\Contracts\HttpClient;
use App\Shared\Application\Factories\HttpRequestFactory;

class ApiPurchaseManagerService implements PurchaseManagerService
{
    public function __construct(
        private HttpClient $httpClient
    ) {}

    public function addMoneyToPurchase(
        string $indentifier,
        float $amount,
        string $currency
    ): Purchase
    {
        $requestData = [
            'identifier' => $indentifier,
            'amount' => $amount,
            'currency' => $currency,
        ];

        $data = [
            'method' => 'POST',
            'url' => '/add-money-to-purchase',
            'requestData' => $requestData
        ];

        $request = HttpRequestFactory::fromArray($data);

        $response = $this->httpClient->send($request);

        return PurchaseFactory::fromArray([
            'identifier' => $response->body()['identifier'],
            'totalAmount' => $response->body()['currentBalance'],
        ]);
    }

    public function purchaseProduct(
        string $identifier,
        int $productId
    ): Purchase
    {
        $requestData = [
            'identifier' => $indentifier,
            'productId' => $productId
        ];

        $data = [
            'method' => 'POST',
            'url' => '/purchase-product',
            'requestData' => $requestData
        ];

        $request = HttpRequestFactory::fromArray($data);

        $response = $this->httpClient->send($request);

        return PurchaseFactory::fromArray([
            'identifier' => $response->body()['identifier'],
            'totalAmount' => $response->body()['currentBalance'],
        ]);
    }
}