<?php

declare(strict_types=1);

namespace App\Shared\Application\DTOs;

class HttpResponse
{
    public function __construct(
        private int $statusCode,
        private array $body,
        private array $headers = [],
    ) {}

    public function statusCode(): int { return $this->statusCode; }
    public function body(): array { return $this->body; }
    public function headers(): array { return $this->headers; }
}