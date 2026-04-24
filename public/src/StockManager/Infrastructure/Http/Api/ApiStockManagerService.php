<?php

declare(strict_types=1);

namespace App\StockManager\Infrastructure\Http\Api;

use App\Shared\Application\Contracts\HttpClient;
use App\Shared\Application\Factories\HttpRequestFactory;
use App\StockManager\Domain\Contracts\StockManagerService;

class ApiStockManagerService implements StockManagerService
{
    public function __construct(
        private HttpClient $httpClient
    ) {}

    public function obtainMachineStock(): array
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

    public function updateProductStock(
        string $code,
        string $name,
        float $price,
        int $quantity,
    ): void
    {
        $requestData = [
            'code' => $code,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
        ];

        $data = [
            'method' => 'POST',
            'url' => '/update-product-stock',
            'requestData' => $requestData
        ];

        $request = HttpRequestFactory::fromArray($data);

        $this->httpClient->send($request);
    }

    public function updateChangeStock(
        float $amount,
        int $quantity,
    ): void
    {
        $requestData = [
            'amount' => $amount,
            'quantity' => $quantity,
        ];

        $data = [
            'method' => 'POST',
            'url' => '/update-change-stock',
            'requestData' => $requestData
        ];

        $request = HttpRequestFactory::fromArray($data);

        $this->httpClient->send($request);
    }
}