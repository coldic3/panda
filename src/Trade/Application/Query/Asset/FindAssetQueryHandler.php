<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\Asset;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;

final readonly class FindAssetQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private AssetRepositoryInterface $assetRepository,
        private AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindAssetQuery $query): ?AssetInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();
        $asset = $this->assetRepository->findById($query->id);

        if (null === $asset) {
            return null;
        }

        $owner = $asset->getOwnedBy();

        if (null === $owner || !$owner->compare($authorizedUser)) {
            return null;
        }

        return $asset;
    }
}
