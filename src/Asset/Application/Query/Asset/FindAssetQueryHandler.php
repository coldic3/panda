<?php

declare(strict_types=1);

namespace Panda\Asset\Application\Query\Asset;

use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;

final class FindAssetQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly AssetRepositoryInterface $assetRepository)
    {
    }

    public function __invoke(FindAssetQuery $query): ?AssetInterface
    {
        return $this->assetRepository->findById($query->id);
    }
}
