<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Model\Transaction;

use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Symfony\Component\Uid\Uuid;

/** @readonly */
class Operation implements OperationInterface
{
    private Uuid $id;

    public function __construct(
        private readonly AssetInterface $asset,
        private readonly int $quantity,
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getAsset(): AssetInterface
    {
        return $this->asset;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
