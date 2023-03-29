<?php

declare(strict_types=1);

namespace Panda\Asset\Application\Query\Asset;

use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Panda\Contract\AggregateRoot\Owner\OwnerInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class FindAssetQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly AssetRepositoryInterface $assetRepository,
        private readonly Security $security,
    ) {
    }

    public function __invoke(FindAssetQuery $query): ?AssetInterface
    {
        $asset = $this->assetRepository->findById($query->id);

        if (null === $asset) {
            return null;
        }

        /** @var OwnerInterface|null $authorizedUser */
        $authorizedUser = $this->security->getUser();
        $owner = $asset->getOwnedBy();

        if (null === $authorizedUser || null === $owner || !$owner->compare($authorizedUser)) {
            return null;
        }

        return $asset;
    }
}
