<?php

declare(strict_types=1);

namespace App\Service\Dynamic;

final readonly class DynamicExtraExpensesProvider
{
    public function __construct(
        private DynamicExtraExpensesFromServiceAProvider $serviceAProvider,
        private DynamicExtraExpensesFromServiceBProvider $serviceBProvider,
    ) {
    }

    public function getExpensesConcurrent(): array
    {
        $serviceAPromise = $this->serviceAProvider->getExpenses();
        $serviceBPromise = $this->serviceBProvider->getExpenses();

        $serviceAExpenses = $serviceAPromise->current();
        $serviceBExpenses = $serviceBPromise->current();

        return array_merge($serviceAExpenses, $serviceBExpenses);
    }

    public function getExpensesConsecutive(): array
    {
        $serviceAPromise = $this->serviceAProvider->getExpenses();
        $serviceAExpenses = $serviceAPromise->current();

        $serviceBPromise = $this->serviceBProvider->getExpenses();
        $serviceBExpenses = $serviceBPromise->current();

        return array_merge($serviceAExpenses, $serviceBExpenses);
    }
}
