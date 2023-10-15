<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Calculator;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;

interface OperationValueCalculatorInterface
{
    public function calculate(
        OperationInterface $operation,
        PortfolioInterface $portfolio,
        OwnerInterface $owner,
        \DateTimeImmutable $datetime,
    ): float;
}
