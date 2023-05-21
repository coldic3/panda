<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\Asset;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;
use Panda\Shared\Domain\Repository\CollectionIteratorInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;

final readonly class FindAssetsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private AssetRepositoryInterface $assetRepository,
        private AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindAssetsQuery $query): ?CollectionIteratorInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();

        $assetQuery = $this->assetRepository->defaultQuery($authorizedUser);

        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $this->assetRepository->pagination($assetQuery, $query->page, $query->itemsPerPage);
        }

        return $this->assetRepository->collection($assetQuery);
    }
}
