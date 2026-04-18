<?php

declare(strict_types=1);

namespace App\Shared\Application\DTOs;

class HttpRequest
{
    public function __construct(
        private string $method,
        private string $url,
        private array $requestData = [],
        private array $headers = [],
        private ?int $timeout = null,
    ) {}

    public function method(): string { return $this->method; }
    public function url(): string { return $this->url; }
    public function requestData(): array { return $this->requestData; }
    public function headers(): array { return $this->headers; }
    public function timeout(): ?int { return $this->timeout; }
}