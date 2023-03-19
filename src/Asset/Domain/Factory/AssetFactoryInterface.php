<?php

declare(strict_types=1);

namespace Panda\Asset\Domain\Factory;

use Panda\Asset\Domain\Model\AssetInterface;

interface AssetFactoryInterface
{
    public function create(string $ticker, ?string $name = null): AssetInterface;
}
