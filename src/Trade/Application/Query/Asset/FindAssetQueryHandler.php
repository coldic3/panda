<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\Asset;

use Panda\Contract\AggregateRoot\Owner\OwnerInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
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
