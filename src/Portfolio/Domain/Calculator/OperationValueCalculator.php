<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Calculator;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;
use Webmozart\Assert\Assert;

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
        Assert::notNull($exchangeRate);

        return $operation->getQuantity() * $exchangeRate->getRate();
    }
}
