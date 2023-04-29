<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Factory;

use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Model\Transaction\Operation;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;

final class OperationFactory implements OperationFactoryInterface
{
    public function create(AssetInterface $asset, int $quantity): OperationInterface
    {
        return new Operation($asset, $quantity);
    }
}
