<?php

declare(strict_types=1);

namespace App\User\Domain\Contracts;

interface UserClient
{
    public function authenticate(string $username, string $password): bool;
}