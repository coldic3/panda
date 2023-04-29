<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Model\Transaction;

use Panda\Shared\Domain\Model\IdentifiableInterface;
use Panda\Trade\Domain\Model\Asset\AssetInterface;

interface OperationInterface extends IdentifiableInterface
{
    public function getAsset(): AssetInterface;

    public function getQuantity(): int;
}
