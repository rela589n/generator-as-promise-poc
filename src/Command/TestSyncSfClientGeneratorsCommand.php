<?php

namespace App\Command;

use Generator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:test-sync-sf-client-generators',
    description: 'Add a short description for your command',
)]
class TestSyncSfClientGeneratorsCommand extends Command
{
    public function __construct(private HttpClientInterface $httpClient)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = hrtime(true);
        $response1 = $this->getResponse1();
        $response2 = $this->getResponse2();

        $array1 = array_column($response1->current(), 'id');
        $array2 = array_column($response2->current(), 'id');

        $endTime = hrtime(true);
        $elapsedTime = ($endTime - $startTime) / 1e6;

        // Elapsed time: 10056
        // Arrays: 1, 2, 3, 4, 5, 6, 7, 8
        // sleep(5) in each service
        $output->writeln('Elapsed time: '.(int)$elapsedTime);
        $output->writeln('Arrays: '.implode(', ', array_merge($array1, $array2)));

        return Command::SUCCESS;
    }

    private function getResponse1(): Generator
    {
        $response1 = $this->httpClient->request('GET', 'https://127.0.0.1:8000/service/a');

        return yield $response1->toArray();
    }

    private function getResponse2(): Generator
    {
        $response2 = $this->httpClient->request('GET', 'https://127.0.0.1:8000/service/b');

        return yield $response2->toArray();
    }
}
