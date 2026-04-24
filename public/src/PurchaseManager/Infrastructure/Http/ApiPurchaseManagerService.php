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
        string $productCode
    ): array
    {
        $requestData = [
            'identifier' => $identifier,
            'productCode' => $productCode
        ];

        $data = [
            'method' => 'POST',
            'url' => '/purchase-product',
            'requestData' => $requestData
        ];

        $request = HttpRequestFactory::fromArray($data);

        $response = $this->httpClient->send($request);

        if (isset($response->body()['error'])) {
            throw new \Exception($response->body()['message']);
        }

        return [
            'product' => $response->body()['productProvided'],
            'change' => $response->body()['changeToReturn'],
            'purchaseHistory' => $response->body()['purchaseHistory'],
            'moneyFrom' => $response->body()['moneyFrom'],
        ];
    }

    public function obtainMachineStatus(): array
    {
        $data = [
            'method' => 'GET',
            'url' => '/machine-status'
        ];

        $request = HttpRequestFactory::fromArray($data);

        $response = $this->httpClient->send($request);

        return [
            'products' => $response->body()['products'],
            'change' => $response->body()['change'],
        ];
    }

    public function closePurchase(string $identifier): array
    {
        $data = [
            'method' => 'POST',
            'url' => '/close-purchase',
            'requestData' => [
                'identifier' => $identifier
            ]
        ];

        $request = HttpRequestFactory::fromArray($data);

        $response = $this->httpClient->send($request);

        return [
            'moneyFrom' => $response->body()['moneyFrom'],
            'change' => $response->body()['changeToReturn'],
        ];
    }
}