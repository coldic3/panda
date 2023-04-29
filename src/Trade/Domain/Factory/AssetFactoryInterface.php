<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Factory;

use Panda\Contract\AggregateRoot\Owner\OwnerInterface;
use Panda\Trade\Domain\Model\Asset\AssetInterface;

interface AssetFactoryInterface
{
    public function create(string $ticker, ?string $name = null, ?OwnerInterface $owner = null): AssetInterface;
}
