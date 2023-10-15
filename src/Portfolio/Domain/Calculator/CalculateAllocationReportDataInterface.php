<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Calculator;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;

interface CalculateAllocationReportDataInterface
{
    /**
     * @param iterable<TransactionInterface> $transactions
     *
     * @return array<string, array<string, int|float>|string[]>
     */
    public function calculate(
        iterable $transactions,
        OwnerInterface $owner,
        PortfolioInterface $portfolio,
        \DateTimeImmutable $datetime,
    ): array;
}
