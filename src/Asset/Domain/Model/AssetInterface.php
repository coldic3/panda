<?php

declare(strict_types=1);

namespace Panda\Asset\Domain\Model;

use Panda\Contract\AggregateRoot\Owner\OwnershipInterface;
use Panda\Shared\Domain\Model\IdentifiableInterface;
use Panda\Shared\Domain\Model\TimestampableInterface;

interface AssetInterface extends IdentifiableInterface, TimestampableInterface, OwnershipInterface
{
    public function getTicker(): string;

    public function setTicker(string $ticker): void;

    public function getName(): string;

    public function setName(string $name): void;
}
