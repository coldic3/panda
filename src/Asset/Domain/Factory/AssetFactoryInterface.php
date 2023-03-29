<?php

declare(strict_types=1);

namespace Panda\Asset\Domain\Factory;

use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Contract\AggregateRoot\Owner\OwnerInterface;

interface AssetFactoryInterface
{
    public function create(string $ticker, ?string $name = null, ?OwnerInterface $owner = null): AssetInterface;
}
