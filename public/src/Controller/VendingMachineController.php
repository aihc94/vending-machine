<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendingMachineController
{
    #[Route('/', name: '')]
    public function mostrar(): Response
    {
        return new Response('<h1>Mensaje X</h1>');
    }
}