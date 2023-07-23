<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Query\ExchangeRate;

use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Core\Domain\Repository\CollectionIteratorInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateRepositoryInterface;

final readonly class FindExchangeRatesQueryHandler implements QueryHandlerInterface
{
    public function __construct(private ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
    }

    public function __invoke(FindExchangeRatesQuery $query): ?CollectionIteratorInterface
    {
        $exchangeRateQuery = $this->exchangeRateRepository->defaultQuery(
            $query->baseResourceTicker,
            $query->quoteResourceTicker,
        );

        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $this->exchangeRateRepository->pagination($exchangeRateQuery, $query->page, $query->itemsPerPage);
        }

        return $this->exchangeRateRepository->collection($exchangeRateQuery);
    }
}
