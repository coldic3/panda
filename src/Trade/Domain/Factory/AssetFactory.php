<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Factory;

use Panda\Contract\AggregateRoot\Owner\OwnerInterface;
use Panda\Trade\Domain\Model\Asset\Asset;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Webmozart\Assert\Assert;

final class AssetFactory implements AssetFactoryInterface
{
    public function __construct(private Security $security)
    {
    }

    public function create(string $ticker, ?string $name = null, ?OwnerInterface $owner = null): AssetInterface
    {
        if (null === $name) {
            $name = $ticker;
        }

        $asset = new Asset($ticker, $name);

        if (null !== $owner) {
            $asset->setOwnedBy($owner);

            return $asset;
        }

        Assert::isInstanceOf(
            $owner = $this->security->getUser(),
            OwnerInterface::class
        );

        $asset->setOwnedBy($owner);

        return $asset;
    }
}
