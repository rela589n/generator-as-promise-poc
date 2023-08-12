<?php

declare(strict_types=1);

namespace App\Service\Dynamic;

use Generator;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final readonly class DynamicExtraExpensesFromServiceBProvider
{
    public function __construct(
        private HttpClientInterface $httpClient
    ){
    }

    public function getExpenses(): Generator
    {
        $response = $this->httpClient->request('GET', 'https://127.0.0.1:8001/service/b');

        return yield $this->processResponse($response);
    }

    private function processResponse(ResponseInterface $response): array
    {
        $array = $response->toArray();

        return array_column($array, 'id');
    }
}
