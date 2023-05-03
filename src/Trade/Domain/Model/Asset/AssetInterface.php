<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Model\Asset;

use Panda\AccountOHS\Domain\Model\Owner\OwnershipInterface;
use Panda\Shared\Domain\Model\IdentifiableInterface;
use Panda\Shared\Domain\Model\TimestampableInterface;

interface AssetInterface extends IdentifiableInterface, TimestampableInterface, OwnershipInterface
{
    public function getTicker(): string;

    public function setTicker(string $ticker): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function compare(AssetInterface $asset): bool;
}
