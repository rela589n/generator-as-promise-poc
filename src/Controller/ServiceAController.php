<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServiceAController extends AbstractController
{
    #[Route('/service/a', name: 'app_service_a')]
    public function index(): JsonResponse
    {
        sleep(5);

        return $this->json([
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
            ['id' => 4],
        ]);
    }
}
