<?php

declare(strict_types=1);

namespace Panda\Asset\Domain\Factory;

use Panda\Asset\Domain\Model\Asset;
use Panda\Asset\Domain\Model\AssetInterface;

final class AssetFactory implements AssetFactoryInterface
{
    public function create(string $ticker, ?string $name = null): AssetInterface
    {
        if (null === $name) {
            $name = $ticker;
        }

        return new Asset($ticker, $name);
    }
}
