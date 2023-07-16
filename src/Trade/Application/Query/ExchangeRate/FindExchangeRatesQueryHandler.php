<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\ExchangeRate;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Core\Domain\Repository\CollectionIteratorInterface;
use Panda\Trade\Domain\Repository\ExchangeRateRepositoryInterface;

final readonly class FindExchangeRatesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ExchangeRateRepositoryInterface $exchangeRateRepository,
        private AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindExchangeRatesQuery $query): ?CollectionIteratorInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();

        $assetQuery = $this->exchangeRateRepository->defaultQuery($authorizedUser);

        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $this->exchangeRateRepository->pagination($assetQuery, $query->page, $query->itemsPerPage);
        }

        return $this->exchangeRateRepository->collection($assetQuery);
    }
}
