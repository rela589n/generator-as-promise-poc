<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:test-async-sf-http-client',
    description: 'Add a short description for your command',
)]
class TestAsyncSfHttpClientCommand extends Command
{
    public function __construct(private HttpClientInterface $httpClient)
    {
        parent::__construct();

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $httpClient = $this->httpClient;
        $startTime = hrtime(true);
        $response1 = $httpClient->request('GET', 'https://127.0.0.1:8000/service/a');
        $response2 = $httpClient->request('GET', 'https://127.0.0.1:8000/service/b');

        $array1 = array_column($response1->toArray(), 'id');
        $array2 = array_column($response2->toArray(), 'id');
        $endTime = hrtime(true);
        $elapsedTime = ($endTime - $startTime) / 1e6;

        // Elapsed time: 5203
        // Arrays: 1, 2, 3, 4, 5, 6, 7, 8
        // (when sleep(5))
        $output->writeln('Elapsed time: '.(int)$elapsedTime);
        $output->writeln('Arrays: '.implode(', ', array_merge($array1, $array2)));

        return Command::SUCCESS;
    }
}
