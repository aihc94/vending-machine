<?php

declare(strict_types=1);

namespace App\User\Application\UseCases;

use App\Shared\Application\Contracts\SessionClient;
use App\User\Domain\Contracts\UserClient;

class AuthenticateUserAndPasswordUseCase
{
    public function __construct(
        private UserClient $userClient,
        private SessionClient $session,
    ) {}

    public function execute(
        string $username,
        string $password
    ): void
    {
        $isAuthenticated = $this->userClient->authenticate($username, $password);

        if (!$isAuthenticated) {
            throw new \InvalidArgumentException('Invalid username or password');
        }

        $this->session->set('username', $username);
    }
}