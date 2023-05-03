<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\Asset;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;
use Panda\Shared\Domain\Repository\CollectionIteratorInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;

final class FindAssetsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly AssetRepositoryInterface $assetRepository,
        private readonly AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindAssetsQuery $query): ?CollectionIteratorInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();
        $assetRepository = $this->assetRepository->filterBy('owner', $authorizedUser);

        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $assetRepository->pagination($query->page, $query->itemsPerPage);
        }

        return $assetRepository->collection();
    }
}
