<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Query\Portfolio;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;

final readonly class FindPortfolioQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private PortfolioRepositoryInterface $portfolioRepository,
        private AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindPortfolioQuery $query): ?PortfolioInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();
        $portfolio = $this->portfolioRepository->findById($query->id);

        if (null === $portfolio) {
            return null;
        }

        $owner = $portfolio->getOwnedBy();

        if (null === $owner || !$owner->compare($authorizedUser)) {
            return null;
        }

        return $portfolio;
    }
}
