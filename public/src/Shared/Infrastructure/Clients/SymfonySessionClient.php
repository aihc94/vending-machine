<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Clients;

use App\Shared\Application\Contracts\SessionClient;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SymfonySessionClient implements SessionClient
{
    private SessionInterface $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->session->get($key, $default);
    }

    public function set(string $key, mixed $value): void
    {
        $this->session->set($key, $value);
    }

    public function has(string $key): bool
    {
        return $this->session->has($key);
    }

    public function remove(string $key): void
    {
        $this->session->remove($key);
    }

    public function invalidate(): void
    {
        $this->session->invalidate();
    }
}