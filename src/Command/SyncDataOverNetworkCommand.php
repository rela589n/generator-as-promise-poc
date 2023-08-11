<?php

namespace App\Command;

use App\Service\ExtraExpensesProvider;
use Closure;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:sync-data-over-network',
    description: 'Add a short description for your command',
)]
class SyncDataOverNetworkCommand extends Command
{
    private const ITERATIONS = 30;

    public function __construct(
        private ExtraExpensesProvider $expensesProvider
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        list($fastExpenses, $avgFastTime) = $this->getExpenses($this->expensesProvider->getExpensesFast(...));

        $output->writeln(sprintf('Fast Expenses: %s', implode(', ', $fastExpenses)));
        $output->writeln(sprintf('Fast Elapsed time: %d', $avgFastTime / 1e6));

        list($slowExpenses, $avgSlowTime) = $this->getExpenses($this->expensesProvider->getExpensesSlow(...));

        $output->writeln(sprintf('Slow Expenses: %s', implode(', ', $slowExpenses)));
        $output->writeln(sprintf('Slow Elapsed time: %d', $avgSlowTime / 1e6));

        return Command::SUCCESS;
    }

    private function getExpenses(Closure $callback): array
    {
        $startTime = hrtime(true);
        for ($i = 0; $i < self::ITERATIONS; ++$i) {
            $expenses = $callback();
            usleep(1e3);
        }
        $endTime = hrtime(true);
        $avgFastTime = ($endTime - $startTime) / self::ITERATIONS;

        return array($expenses, $avgFastTime);
    }
}
