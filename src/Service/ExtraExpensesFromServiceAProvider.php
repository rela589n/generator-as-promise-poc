<?php

declare(strict_types=1);

namespace App\Service;

use Generator;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final readonly class ExtraExpensesFromServiceAProvider
{
    public function __construct(
        private HttpClientInterface $httpClient
    ){
    }

    public function getExpenses(): Generator
    {
        $response = $this->httpClient->request('GET', 'https://my-json-server.typicode.com/typicode/demo/posts');

        return yield $this->processResponse($response);
    }

    private function processResponse(ResponseInterface $response): array
    {
        $array = $response->toArray();

        return array_column($array, 'id');
    }
}
