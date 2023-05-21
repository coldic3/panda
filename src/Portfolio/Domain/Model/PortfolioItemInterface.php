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

    public function getQuantity(): int;

    public function addQuantity(int $quantity): void;

    public function removeQuantity(int $quantity): void;

    public function getPortfolio(): PortfolioInterface;
}
