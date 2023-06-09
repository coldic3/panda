<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Model;

use Panda\Portfolio\Domain\ValueObject\ResourceInterface;
use Panda\Shared\Domain\Model\IdentifiableInterface;
use Panda\Shared\Domain\Model\TimestampableInterface;

interface PortfolioItemInterface extends IdentifiableInterface, TimestampableInterface
{
    public function getResource(): ResourceInterface;

    public function setResource(ResourceInterface $resource): void;

    public function getLongQuantity(): int;

    public function addLongQuantity(int $quantity): void;

    public function removeLongQuantity(int $quantity): void;

    public function getShortQuantity(): int;

    public function addShortQuantity(int $quantity): void;

    public function removeShortQuantity(int $quantity): void;

    public function getPortfolio(): PortfolioInterface;
}
