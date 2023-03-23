<?php

declare(strict_types=1);

namespace Panda\Asset\Domain\Model;

use Panda\Shared\Domain\Model\IdentifiableInterface;

interface AssetInterface extends IdentifiableInterface
{
    public function getTicker(): string;

    public function setTicker(string $ticker): void;

    public function getName(): string;

    public function setName(string $name): void;
}
