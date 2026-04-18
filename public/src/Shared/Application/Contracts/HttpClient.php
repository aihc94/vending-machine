<?php

declare(strict_types=1);

namespace App\Shared\Application\Contracts;

use App\Shared\Application\DTOs\HttpRequest;
use App\Shared\Application\DTOs\HttpResponse;

interface HttpClient
{
    public function send(HttpRequest $request): HttpResponse;
}