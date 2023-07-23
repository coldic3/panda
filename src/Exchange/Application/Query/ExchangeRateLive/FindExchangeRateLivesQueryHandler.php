<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Query\ExchangeRateLive;

use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Core\Domain\Repository\CollectionIteratorInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLiveRepositoryInterface;

final readonly class FindExchangeRateLivesQueryHandler implements QueryHandlerInterface
{
    public function __construct(private ExchangeRateLiveRepositoryInterface $exchangeRateRepository)
    {
    }

    public function __invoke(FindExchangeRateLivesQuery $query): ?CollectionIteratorInterface
    {
        $exchangeRateQuery = $this->exchangeRateRepository->defaultQuery(
            $query->baseTicker,
            $query->quoteTicker,
        );

        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $this->exchangeRateRepository->pagination($exchangeRateQuery, $query->page, $query->itemsPerPage);
        }

        return $this->exchangeRateRepository->collection($exchangeRateQuery);
    }
}
