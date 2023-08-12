<?php

namespace App\Command;

use App\Service\Static\StaticExtraExpensesProvider;
use Closure;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:sync-data-over-network:static',
    description: 'Add a short description for your command',
)]
class SyncStaticDataOverNetworkCommand extends Command
{
    private const ITERATIONS = 30;

    public function __construct(
        private StaticExtraExpensesProvider $expensesProvider
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->testConcurrent($output);
        $this->testConsecutive($output);

        return Command::SUCCESS;
    }

    private function testConcurrent(OutputInterface $output): void
    {
        list($fastExpenses, $avgFastTime) = $this->getExpenses($this->expensesProvider->getExpensesConcurrent(...));

        $output->writeln(sprintf('Fast Expenses: %s', implode(', ', $fastExpenses)));
        $output->writeln(sprintf('Fast Elapsed time: %d', $avgFastTime / 1e6));
    }

    private function testConsecutive(OutputInterface $output): void
    {
        list($slowExpenses, $avgSlowTime) = $this->getExpenses($this->expensesProvider->getExpensesConsecutive(...));

        $output->writeln(sprintf('Slow Expenses: %s', implode(', ', $slowExpenses)));
        $output->writeln(sprintf('Slow Elapsed time: %d', $avgSlowTime / 1e6));
    }

    private function getExpenses(Closure $callback): array
    {
        $startTime = hrtime(true);
        for ($i = 0; $i < self::ITERATIONS; ++$i) {
            $expenses = $callback();
            usleep(1e3);
        }
        $endTime = hrtime(true);
        $avgTime = ($endTime - $startTime) / self::ITERATIONS;

        return array($expenses, $avgTime);
    }
}
