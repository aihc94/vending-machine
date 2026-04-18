<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Clients;

use App\Shared\Application\Contracts\HttpClient;
use App\Shared\Application\DTOs\HttpRequest;
use App\Shared\Application\DTOs\HttpResponse;
use GuzzleHttp\Client;

class ApiHttpClient implements HttpClient
{
    public const string BASE_URL='http://localhost:8081';

    private Client $client;

    public function __construct() {
        $this->client = new Client(
            [
                'base_uri' => self::BASE_URL,  
                'timeout' => 5.0,
            ]
        );
    }

    public function send(HttpRequest $request): HttpResponse
    {
        $options = [
            'headers' => $request->headers(),
        ];

        if ($request->timeout() !== null) {
            $options['timeout'] = $request->timeout();
        }

        $options['data'] = $request->requestData();

        try {
            $guzzleResponse = $this->client->request(
                strtoupper($request->method()),
                $request->url(),
                $options
            );

            return new HttpResponse(
                $guzzleResponse->getStatusCode(),
                $this->normalizeBody(
                    $guzzleResponse->getBody()->getContents()
                ),
                $guzzleResponse->getHeaders()
            );

        } catch (\Throwable $e) {
            return new HttpResponse(
                500,
                ['error' => $e->getMessage()],
                []
            );
        }
    }

    private function normalizeBody(string $body): array
    {
        $decoded = json_decode($body, true);
    
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
    
        return [
            'raw' => $body
        ];
    }
}