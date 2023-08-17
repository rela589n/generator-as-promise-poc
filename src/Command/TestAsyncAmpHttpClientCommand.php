<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\AmpHttpClient;

#[AsCommand(
    name: 'app:test-async-http',
    description: 'Add a short description for your command',
)]
class TestAsyncAmpHttpClientCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $httpClient = new AmpHttpClient();

        $startTime = hrtime(true);
        $response1 = $httpClient->request('GET', 'https://127.0.0.1:8000/service/a');
        $response2 = $httpClient->request('GET', 'https://127.0.0.1:8000/service/b');

        $response1->toArray();
        $response2->toArray();
        $endTime = hrtime(true);
        $elapsedTime = ($endTime - $startTime) / 1e6;

        // Elapsed time: 5079
        // This is what we wanted it to be (when sleep(5))
        $output->writeln('Elapsed time: '. (int)$elapsedTime);

        return Command::SUCCESS;
    }
}
