<?php

declare(strict_types=1);

namespace App\Shared\Application\Factories;

use App\Shared\Application\DTOs\HttpRequest;

final class HttpRequestFactory
{
    public static function fromArray(array $data): HttpRequest
    {
        $method = strtoupper(trim($data['method']));
        $url = trim($data['url']);
        $requestData = $data['requestData'] ?? [];
        $headers = $data['headers'] ?? [];
        $timeout = $data['timeout'] ?? null;

        return new HttpRequest(
            $method,
            $url,
            $requestData,
            $headers,
            $timeout
        );
    }
}