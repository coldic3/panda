<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Query\ExchangeRateLog;

use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLogInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;

final readonly class FindExchangeRateLogQueryHandler implements QueryHandlerInterface
{
    public function __construct(private ExchangeRateLogRepositoryInterface $exchangeRateLogRepository)
    {
    }

    public function __invoke(FindExchangeRateLogQuery $query): ?ExchangeRateLogInterface
    {
        return $this->exchangeRateLogRepository->findById($query->id);
    }
}
