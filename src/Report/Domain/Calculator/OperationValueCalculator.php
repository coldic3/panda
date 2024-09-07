<?php

declare(strict_types=1);

namespace Panda\Report\Domain\Calculator;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Report\Domain\Exception\ExchangeRateLogNotFoundException;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;

final readonly class OperationValueCalculator implements OperationValueCalculatorInterface
{
    public function __construct(private ExchangeRateLogRepositoryInterface $exchangeRateLogRepository)
    {
    }

    public function calculate(
        OperationInterface $operation,
        PortfolioInterface $portfolio,
        OwnerInterface $owner,
        \DateTimeImmutable $datetime
    ): float {
        $mainTicker = $portfolio->getMainResource()->getTicker();
        $ticker = $operation->getAsset()->getTicker();

        if ($ticker === $mainTicker) {
            return $operation->getQuantity();
        }

        $exchangeRate = $this->exchangeRateLogRepository->findByDatetime(
            $owner,
            $mainTicker,
            $ticker,
            $datetime,
        );

        if (null === $exchangeRate) {
            throw new ExchangeRateLogNotFoundException(sprintf('Exchange rate log for %s datetime not found.', $datetime->format('Y-m-d H:i:s')));
        }

        return $operation->getQuantity() * $exchangeRate->getRate();
    }
}
