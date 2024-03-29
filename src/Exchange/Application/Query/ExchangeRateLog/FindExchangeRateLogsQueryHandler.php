<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Query\ExchangeRateLog;

use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Core\Domain\Repository\CollectionIteratorInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;

final readonly class FindExchangeRateLogsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private AuthorizedUserProviderInterface $authorizedUserProvider,
        private ExchangeRateLogRepositoryInterface $exchangeRateLogRepository,
    ) {
    }

    public function __invoke(FindExchangeRateLogsQuery $query): ?CollectionIteratorInterface
    {
        $exchangeRateQuery = $this->exchangeRateLogRepository->defaultQuery(
            $this->authorizedUserProvider->provide(),
            $query->baseTicker,
            $query->quoteTicker,
        );

        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $this->exchangeRateLogRepository->pagination($exchangeRateQuery, $query->page, $query->itemsPerPage);
        }

        return $this->exchangeRateLogRepository->collection($exchangeRateQuery);
    }
}
