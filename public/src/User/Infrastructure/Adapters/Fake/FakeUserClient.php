<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Adapters\Fake;

use App\User\Domain\Contracts\UserClient;

//This adapter must be through Http/Api/ApiUserClient and goes to api repo and access to it's bounded context User and interact with the database
//But for the sake of simplicity and to avoid overengineering, we will implement it here as a fake client
class FakeUserClient implements UserClient
{
    public function authenticate(string $username, string $password): bool
    {
        return $username === 'admin' && $password === '1234';
    }
}