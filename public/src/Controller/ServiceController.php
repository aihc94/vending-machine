<?php

declare(strict_types=1);

namespace App\Controller;

use App\Shared\Application\Contracts\SessionClient;
use App\StockManager\Application\Commands\UpdateChangeStockCommand;
use App\StockManager\Application\Commands\UpdateProductStockCommand;
use App\StockManager\Application\Queries\ObtainVendingMachineStockQuery;
use App\User\Application\UseCases\AuthenticateUserAndPasswordUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    public function __construct(
        private SessionClient $session,
    ) {}

    #[Route('/service', name: 'service', methods: ['GET'])]
    public function showServiceView(
        Request $request,
        ObtainVendingMachineStockQuery $machineStockQuery,
    ): Response
    {
        if (!$this->session->has('username')) {
            return $this->render(
                'login.html.twig',
            );
        }

        $actualStock = $machineStockQuery->execute();

        return $this->render(
            'machine-service.html.twig',
            [
                'products' => $actualStock['products'],
                'change' => $actualStock['change'],
            ]
        );
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(
        Request $request,
        AuthenticateUserAndPasswordUseCase $useCase,
    ): Response
    {  
        $username = $request->get('username');
        $password = $request->get('password');

        try {
            $useCase->execute($username, $password);
        } catch (\InvalidArgumentException $exception) {
            return $this->redirectToRoute(
                'service', 
            );
        }

        return $this->redirectToRoute(
            'service', 
        );
    }

    #[Route('/logout', name: 'logout', methods: ['POST'])]
    public function logout(
        Request $request,
    ): Response
    {  
        $this->validateSession();
        $this->validateCsrf($request);
        $this->session->remove('username');

        return $this->redirectToRoute(
            'service', 
        );
    }

    #[Route('/product-create', name: 'product-create', methods: ['POST'])]
    public function createProduct(
        Request $request,
        UpdateProductStockCommand $command,
    ): Response
    {  
        $this->validateSession();
        $this->validateCsrf($request);

        $command->execute(
            $request->get('code'),
            $request->get('name'),
            (float)$request->get('price'),
            (int)$request->get('quantity'),
        );

        return $this->redirectToRoute(
            'service', 
        );
    }

    #[Route('/change-create', name: 'change-create', methods: ['POST'])]
    public function createChange(
        Request $request,
        UpdateChangeStockCommand $command,
    ): Response
    {  
        $this->validateSession();
        $this->validateCsrf($request);

        $command->execute(
            (float)$request->get('amount'),
            (int)$request->get('quantity'),
        );

        return $this->redirectToRoute(
            'service', 
        );
    }

    private function validateCsrf(Request $request, string $tokenId = 'service'): void
    {
        $token = $request->request->get('_csrf_token');

        if (!$token || !$this->isCsrfTokenValid($tokenId, $token)) {
            throw new AccessDeniedHttpException('Invalid CSRF token');
        }
    }

    private function validateSession(): void
    {
        if (!$this->session->has('username')) {
            throw new AccessDeniedHttpException('Session has been lost login again');
        }
    }
}