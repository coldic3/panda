<?php

declare(strict_types=1);

namespace Panda\Asset\Domain\Factory;

use Panda\Asset\Domain\Model\Asset;
use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Contract\AggregateRoot\Owner\OwnerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Webmozart\Assert\Assert;

final class AssetFactory implements AssetFactoryInterface
{
    public function __construct(private Security $security)
    {
    }

    public function create(string $ticker, ?string $name = null): AssetInterface
    {
        if (null === $name) {
            $name = $ticker;
        }

        $asset = new Asset($ticker, $name);

        Assert::isInstanceOf(
            $owner = $this->security->getUser(),
            OwnerInterface::class
        );

        $asset->setOwnedBy($owner);

        return $asset;
    }
}
