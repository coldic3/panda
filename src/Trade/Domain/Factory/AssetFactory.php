<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Factory;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Trade\Domain\Model\Asset\Asset;
use Panda\Trade\Domain\Model\Asset\AssetInterface;

final class AssetFactory implements AssetFactoryInterface
{
    public function __construct(private AuthorizedUserProviderInterface $authorizedUserProvider)
    {
    }

    public function create(string $ticker, string $name = null, OwnerInterface $owner = null): AssetInterface
    {
        if (null === $name) {
            $name = $ticker;
        }

        $asset = new Asset($ticker, $name);

        if (null !== $owner) {
            $asset->setOwnedBy($owner);

            return $asset;
        }

        $owner = $this->authorizedUserProvider->provide();

        $asset->setOwnedBy($owner);

        return $asset;
    }
}
