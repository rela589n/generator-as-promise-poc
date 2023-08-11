<?php

declare(strict_types=1);

namespace App\Service;

final readonly class ExtraExpensesProvider
{
    public function __construct(
        private ExtraExpensesFromServiceAProvider $serviceAProvider,
        private ExtraExpensesFromServiceBProvider $serviceBProvider,
    ) {
    }

    public function getExpensesFast(): array
    {
        $serviceAPromise = $this->serviceAProvider->getExpenses();
        $serviceBPromise = $this->serviceBProvider->getExpenses();

        $serviceAExpenses = $serviceAPromise->current();
        $serviceBExpenses = $serviceBPromise->current();

        return array_merge($serviceAExpenses, $serviceBExpenses);
    }
    public function getExpensesSlow(): array
    {
        $serviceAPromise = $this->serviceAProvider->getExpenses();
        $serviceAExpenses = $serviceAPromise->current();

        $serviceBPromise = $this->serviceBProvider->getExpenses();
        $serviceBExpenses = $serviceBPromise->current();

        return array_merge($serviceAExpenses, $serviceBExpenses);
    }
}
