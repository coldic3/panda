<?php

declare(strict_types=1);

namespace Panda\Asset\Application\Query\Asset;

use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;
use Panda\Shared\Domain\Repository\CollectionIteratorInterface;

final class FindAssetsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly AssetRepositoryInterface $assetRepository)
    {
    }

    public function __invoke(FindAssetsQuery $query): ?CollectionIteratorInterface
    {
        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $this->assetRepository->pagination($query->page, $query->itemsPerPage);
        }

        return $this->assetRepository->collection();
    }
}
