<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Query\ExchangeRateLog;

use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLogInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;

final readonly class FindExchangeRateLogByBaseQuoteTickersAndDatetimeQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private AuthorizedUserProviderInterface $authorizedUserProvider,
        private ExchangeRateLogRepositoryInterface $exchangeRateLogRepository,
    ) {
    }

    public function __invoke(FindExchangeRateLogByBaseQuoteTickersAndDatetimeQuery $query): ?ExchangeRateLogInterface
    {
        return $this->exchangeRateLogRepository->findByDatetime(
            $this->authorizedUserProvider->provide(),
            $query->baseTicker,
            $query->quoteTicker,
            $query->datetime,
        );
    }
}
