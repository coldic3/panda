<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Model\Asset;

use Panda\Contract\AggregateRoot\Owner\OwnershipInterface;
use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Panda\Shared\Domain\Model\TimestampableInterface;

interface AssetInterface extends ResourceInterface, TimestampableInterface, OwnershipInterface
{
    public function getTicker(): string;

    public function setTicker(string $ticker): void;

    public function getName(): string;

    public function setName(string $name): void;
}
