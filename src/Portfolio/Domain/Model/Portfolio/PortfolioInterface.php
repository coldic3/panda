<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Model\Portfolio;

use Doctrine\Common\Collections\Collection;
use Panda\AccountOHS\Domain\Model\Owner\OwnershipInterface;
use Panda\Core\Domain\Model\IdentifiableInterface;
use Panda\Core\Domain\Model\TimestampableInterface;
use Panda\Portfolio\Domain\ValueObject\ResourceInterface;

interface PortfolioInterface extends IdentifiableInterface, TimestampableInterface, OwnershipInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    public function isDefault(): bool;

    public function setDefault(bool $default): void;

    public function getMainResource(): ResourceInterface;

    /** @return Collection<array-key, PortfolioItemInterface> */
    public function getItems(): Collection;

    public function addItem(PortfolioItemInterface $item): void;

    public function removeItem(PortfolioItemInterface $item): void;
}
