<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\CurlHttpClient;

#[AsCommand(
    name: 'app:test-async-curl-http',
    description: 'Add a short description for your command',
)]
class TestAsyncCurlHttpCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $httpClient = new CurlHttpClient();

        $startTime = hrtime(true);
        $response1 = $httpClient->request('GET', 'https://127.0.0.1:8000/service/a');
        $response2 = $httpClient->request('GET', 'https://127.0.0.1:8000/service/b');

        $response1->toArray();
        $response2->toArray();
        $endTime = hrtime(true);
        $elapsedTime = ($endTime - $startTime) / 1e6;

        // Elapsed time: 5102
        // This is what we wanted it to be (when sleep(5))
        $output->writeln('Elapsed time: '. (int)$elapsedTime);

        return Command::SUCCESS;
    }
}
