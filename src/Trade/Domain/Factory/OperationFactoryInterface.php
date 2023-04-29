<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Factory;

use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;

interface OperationFactoryInterface
{
    public function create(AssetInterface $asset, int $quantity): OperationInterface;
}
