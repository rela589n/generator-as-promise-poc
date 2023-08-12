<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServiceBController extends AbstractController
{
    #[Route('/service/b', name: 'app_service_b')]
    public function index(): JsonResponse
    {
        sleep(1);

        return $this->json([
            ['id' => 5],
            ['id' => 6],
            ['id' => 7],
            ['id' => 8],
        ]);
    }
}
