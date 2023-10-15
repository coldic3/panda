<?php

declare(strict_types=1);

namespace Panda\Report\Domain\Calculator;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Report\Domain\Dto\PerformanceReportConfigurationDto;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;

interface CalculatePerformanceReportDataInterface
{
    /**
     * @param iterable<TransactionInterface> $initialValueTransactions
     * @param iterable<TransactionInterface> $finalValueTransactions
     *
     * @return array<string, int|string>
     */
    public function calculate(
        iterable $initialValueTransactions,
        iterable $finalValueTransactions,
        OwnerInterface $owner,
        PortfolioInterface $portfolio,
        PerformanceReportConfigurationDto $configuration,
    ): array;
}
