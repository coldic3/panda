<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Query\Portfolio;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Core\Domain\Repository\CollectionIteratorInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;

final readonly class FindPortfoliosQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private PortfolioRepositoryInterface $portfolioRepository,
        private AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindPortfoliosQuery $query): ?CollectionIteratorInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();

        $portfolioQuery = $this->portfolioRepository->defaultQuery($authorizedUser);

        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $this->portfolioRepository->pagination($portfolioQuery, $query->page, $query->itemsPerPage);
        }

        return $this->portfolioRepository->collection($portfolioQuery);
    }
}
