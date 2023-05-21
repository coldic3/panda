<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Shared\Domain\Model\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

class Portfolio implements PortfolioInterface
{
    use TimestampableTrait;

    private Uuid $id;
    private ?OwnerInterface $owner = null;

    /** @var Collection<array-key, PortfolioItemInterface> */
    private Collection $items;

    public function __construct(private string $name, private bool $default = false)
    {
        $this->id = Uuid::v4();

        $this->items = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function setDefault(bool $default): void
    {
        $this->default = $default;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(PortfolioItemInterface $item): void
    {
        if ($this->items->contains($item)) {
            return;
        }

        $this->items->add($item);
    }

    public function removeItem(PortfolioItemInterface $item): void
    {
        if (!$this->items->contains($item)) {
            return;
        }

        $this->items->removeElement($item);
    }

    public function getOwnedBy(): ?OwnerInterface
    {
        return $this->owner;
    }

    public function setOwnedBy(OwnerInterface $owner): void
    {
        $this->owner = $owner;
    }
}
